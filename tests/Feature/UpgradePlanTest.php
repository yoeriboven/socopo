<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpgradePlanTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        \Stripe\Stripe::setApiKey("sk_test_bnPhXRyMBzrzKzneJ0MWrxu800Cw50DbP1");
    }

    /** @test */
    public function unauthorized_users_cant_access_the_upgrade_pages()
    {
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

    // Plan in array
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
            'plan' => 'plan_ErRIL8fIR4sfRt',
            'stripeToken' => 'placeholder_stripe_token'
        ];

        return array_merge($subscription_data, $overrides);
    }

    private function getStripeToken()
    {
        return \Stripe\Token::create([
                    'card' => [
                        'number' => '4242424242424242',
                        'exp_month' => 1,
                        'exp_year' => 2025,
                        'cvc' => 123
                    ]
                ])->id;
    }
}
