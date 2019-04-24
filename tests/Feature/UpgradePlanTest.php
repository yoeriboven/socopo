<?php

namespace Tests\Feature;

use Tests\TestCase;
use Tests\Traits\InteractsWithStripe;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpgradePlanTest extends TestCase
{
    use RefreshDatabase, InteractsWithStripe;

    /** @test */
    public function unauthorized_users_cant_access_the_upgrade_pages()
    {
        $this->get('upgrade')
            ->assertRedirect('login');

        $this->post('upgrade')
            ->assertRedirect('login');
    }

    /** @test */
    public function an_authorized_user_can_upgrade_their_plan()
    {
        $this->withoutExceptionHandling();
        $this->signIn();

        $user_details = $this->getUserDetails();
        $subscription_data = $this->getSubscriptionData(['stripeToken' => $this->getStripeToken()]);

        $this->assertFalse($this->user->subscribed('Pro'));

        $this->post('upgrade', array_merge($user_details, $subscription_data));

        // Subscription should be registered on our end
        $this->assertTrue($this->user->fresh()->subscribed('Pro'));

        // Subscription should be registered on Stripe's end
        try {
            \Stripe\Subscription::retrieve($this->user->fresh()->subscriptions->first()->stripe_id);
        } catch (Exception $e) {
            $this->fail('Failed to retrieve Stripe subscription.');
        }

        // User Details need to be updated on our end
        $this->assertDatabaseHas('user_details', $user_details);
    }

    /** @test */
    public function a_stripe_token_is_required()
    {
        $this->signIn();

        $user_details = $this->getUserDetails();
        $subscription_data = $this->getSubscriptionData(['stripeToken' => '']);

        $this->post('upgrade', array_merge($user_details, $subscription_data))
            ->assertSessionHasErrors('stripeToken');
    }

    /** @test */
    public function a_stripe_token_needs_to_be_valid()
    {
        $this->withoutExceptionHandling();
        $this->signIn();

        $user_details = $this->getUserDetails();
        $subscription_data = $this->getSubscriptionData(['stripeToken' => 'invalid_stripe_token']);

        $this->post('upgrade', array_merge($user_details, $subscription_data))
            ->assertSessionHasErrors('stripeToken');
    }

    /**
     * Validation is tested in ChangeUserDetailsTest
     * This test is to check if it is implemented
     */
    /** @test */
    public function it_uses_the_validation_rules_for_user_details()
    {
        $this->signIn();

        $user_details = $this->getUserDetails(['name' => '']);
        $subscription_data = $this->getSubscriptionData();

        $this->post('upgrade', array_merge($user_details, $subscription_data))
            ->assertSessionHasErrors('name');
    }

    /** @test */
    public function a_plan_is_required_and_needs_to_exist_in_the_plans_list()
    {
        $user = $this->signIn();

        $subscription_data = $this->getSubscriptionData(['plan' => null]);
        $this->post('upgrade', array_merge($this->getUserDetails(), $subscription_data))
            ->assertSessionHasErrors('plan');

        $subscription_data = $this->getSubscriptionData(['plan' => 'plan_1']);
        $this->post('upgrade', array_merge($this->getUserDetails(), $subscription_data))
            ->assertSessionDoesntHaveErrors('plan');

        $subscription_data = $this->getSubscriptionData(['plan' => 'plan_391']);
        $this->post('upgrade', array_merge($this->getUserDetails(), $subscription_data))
            ->assertSessionHasErrors('plan');
    }

    /** @test */
    public function it_subscribes_to_the_correct_plan()
    {
        $user = $this->signIn();

        $planKey = array_key_first(config('plans'));
        $plan = config('plans')[$planKey];

        $subscription_data = $this->getSubscriptionData(['plan' => $planKey, 'stripeToken' => $this->getStripeToken()]);
        $this->post('upgrade', array_merge($this->getUserDetails(), $subscription_data));

        $this->assertEquals($plan['id'], $user->fresh()->subscription($plan['name'])->stripe_plan);
    }

    /** @test */
    public function it_has_errors_when_a_user_is_already_subscribed_to_the_requested_plan()
    {
        $this->withoutExceptionHandling();

        $user = $this->signIn();

        $planKey = array_key_first(config('plans'));
        $plan = config('plans')[$planKey];

        $subscription = factory('App\Billing\Subscription')->create(['user_id' => $user->id, 'name' => $plan['name']]);

        $subscription_data = $this->getSubscriptionData(['plan' => $planKey]);

        $this->post('upgrade', array_merge($this->getUserDetails(), $subscription_data))
            ->assertSessionHasErrors();
    }

    /** @test */
    public function if_a_user_is_already_subscribed_to_another_plan_the_old_plan_will_be_cancelled()
    {
        $this->withoutExceptionHandling();
        $user = $this->signIn();

        // Subscribe to plan
        $planKey = array_keys(config('plans'))[0];
        $planOne = config('plans')[$planKey];

        $subscription_data = $this->getSubscriptionData(['plan' => $planKey, 'stripeToken' => $this->getStripeToken()]);
        $this->post('upgrade', array_merge($this->getUserDetails(), $subscription_data));

        $user->refresh();

        // Subscribe to different plan
        $planKey = array_keys(config('plans'))[1];
        $planTwo = config('plans')[$planKey];

        $subscription_data = $this->getSubscriptionData(['plan' => $planKey, 'stripeToken' => $this->getStripeToken()]);
        $this->post('upgrade', array_merge($this->getUserDetails(), $subscription_data));

        $this->assertEquals($user->fresh()->subscription()->name, $planTwo['name']);
        $this->assertTrue($user->fresh()->subscription($planOne['name'])->cancelled());
    }


    private function getUserDetails($overrides = [])
    {
        $user_details = [
            'vat_id' => 'NL852924574B01',
            'name' => 'Yoeri.me',
            'address' => 'De Werf 9',
            'postal' => '9514CN',
            'city' => 'Gasselternijveen',
            'country' => 'NL'
        ];

        return array_merge($user_details, $overrides);
    }

    private function getSubscriptionData($overrides = [])
    {
        $subscription_data = [
            'plan' => 'plan_1',
            'stripeToken' => 'placeholder_stripe_token'
        ];

        return array_merge($subscription_data, $overrides);
    }
}
