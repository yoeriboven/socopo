<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Support\Carbon;
use Tests\Traits\InteractsWithStripe;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BillableTest extends TestCase
{
    use RefreshDatabase, InteractsWithStripe;

    public function setUp(): void
    {
        parent::setUp();

        $this->signIn();
    }

    /** @test */
    public function it_can_determine_if_there_is_a_subscription_active()
    {
        $user = $this->signIn();

        $this->assertFalse($user->isSubscribed());

        $user->newSubscription('Pro', 'plan_ErRIL8fIR4sfRt')->create($this->getStripeToken());

        $this->assertTrue($user->fresh()->isSubscribed());

        $user->fresh()->subscription('Pro')->cancelNow();

        $this->assertFalse($user->fresh()->isSubscribed());
    }

    /** @test */
    public function it_returns_the_correct_subscription_class()
    {
        $subscriptionOne = factory('App\Billing\Subscription')->create(['user_id' => $this->user->id]);

        $this->assertInstanceOf('App\Billing\Subscription', $this->user->subscriptions()->first());
    }

    /** @test */
    public function it_returns_the_correct_subscription_builder_class()
    {
        $this->assertInstanceOf('App\Billing\SubscriptionBuilder', $this->user->newSubscription(null, null));
    }

    /** @test */
    public function it_can_return_a_subscription_if_no_name_is_given()
    {
        $subscriptionOne = factory('App\Billing\Subscription')->create(['user_id' => $this->user->id, 'name' => 'Pro', 'stripe_plan' => 'one']);

        $this->assertEquals($this->user->subscription()->stripe_plan, 'one');
    }

    /** @test */
    public function it_can_return_a_subscription_if_a_name_is_given()
    {
        $user = $this->user;

        $subscriptionOne = factory('App\Billing\Subscription')->create(['user_id' => $user->id, 'name' => 'Pro', 'stripe_plan' => 'one']);
        $subscriptionTwo = factory('App\Billing\Subscription')->create(['user_id' => $user->id, 'name' => 'Enterprise', 'stripe_plan' => 'two']);

        $this->assertEquals($user->subscription('Pro')->stripe_plan, 'one');
        $this->assertEquals($user->subscription('Enterprise')->stripe_plan, 'two');
    }

    /** @test */
    public function it_stores_the_current_period_end_when_a_new_subscription_is_created()
    {
        $subscription = $this->user->newSubscription('Pro', 'plan_ErRIL8fIR4sfRt')->create($this->getStripeToken());

        $subscription = $subscription->asStripeSubscription();

        $this->assertEquals($this->user->subscription('Pro')->current_period_end->timestamp, $subscription->current_period_end);
    }

    /** @test */
    public function it_can_verify_vat_numbers()
    {
        $this->user->details->update(['vat_id' => 'NL812334966B01']);
        $this->assertTrue($this->user->isBusiness());

        $this->user->details->update(['vat_id' => 'invalid_vat_id']);
        $this->assertFalse($this->user->isBusiness());
    }

    /** @test */
    public function it_sets_the_correct_tax_rate()
    {
        $user = $this->user;

        $user->details->update(['country' => 'NL', 'vat_id' => null]);
        $this->assertEquals(21, $user->getTaxPercent());

        $user->details->update(['country' => 'NL', 'vat_id' => 'NL812334966B01']);
        $this->assertEquals(21, $user->getTaxPercent());

        $user->details->update(['country' => 'IE', 'vat_id' => null]);
        $this->assertEquals(23, $user->getTaxPercent());

        $user->details->update(['country' => 'IE', 'vat_id' => 'IE6388047V']);
        $this->assertEquals(0, $user->getTaxPercent());

        $user->details->update(['country' => 'US', 'vat_id' => null]);
        $this->assertEquals(0, $user->getTaxPercent());
    }

    /** @test */
    public function it_can_subscribe_a_user()
    {
        $user = $this->user;

        // Subscribe to plan
        $planOne = app('plans')->get(0);

        $user->subscribeToPlan($this->getStripeToken(), $planOne);

        $user->refresh();

        // Subscribe to different plan
        $planTwo = app('plans')->get(1);

        $user->subscribeToPlan($this->getStripeToken(), $planTwo);

        $this->assertEquals($user->fresh()->subscription()->name, $planTwo->name);
        $this->assertTrue($user->fresh()->subscription($planOne->name)->cancelled());
    }

    /** @test */
    public function it_can_cancel_all_subscriptions()
    {
        $user = $this->user;

        // Subscribe to plan
        $plan = app('plans')->first();

        $user->subscribeToPlan($this->getStripeToken(), $plan);

        $this->assertTrue($user->fresh()->isSubscribed());

        $user->refresh();

        $user->cancelAllSubscriptions();

        $this->assertCount(0, $user->subscriptions()->notCancelled()->get());
    }

    /** @test */
    public function it_returns_the_correct_plan()
    {
        $this->assertInstanceOf('App\Plans\FreePlan', $this->user->plan());

        // Assign a plan
        $plan = app('plans')->first();
        factory('App\Billing\Subscription')->create(['user_id' => $this->user->id, 'stripe_plan' => $plan->stripe_id]);

        $this->assertInstanceOf(get_class($plan), $this->user->fresh()->plan());
    }

    /** @test */
    public function it_returns_an_active_subscription_if_it_has_one()
    {
        $user = $this->user;

        $subscriptionOne = factory('App\Billing\Subscription')->create([
            'user_id' => $user->id,
            'name' => 'Pro',
            'created_at' => Carbon::now(),
            'ends_at' => Carbon::now()->subDays(1)
        ]);

        $subscriptionTwo = factory('App\Billing\Subscription')->create([
            'user_id' => $user->id,
            'name' => 'Enterprise',
            'created_at' => Carbon::now()->subDays(1)
        ]);

        $subscription = $user->activeSubscription();

        $this->assertEquals('Enterprise', $subscription->name);
    }

    /** @test */
    public function it_returns_a_new_object_if_no_active_subscription_is_found()
    {
        $subscription = $this->user->activeSubscription();

        $this->assertInstanceOf('App\Billing\Subscription', $subscription);
        $this->assertEmpty($subscription->all());
    }
}
