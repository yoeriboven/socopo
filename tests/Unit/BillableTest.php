<?php

namespace Tests\Unit;

use Tests\TestCase;
use Tests\Traits\InteractsWithStripe;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BillableTest extends TestCase
{
    use RefreshDatabase, InteractsWithStripe;

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
        $user = $this->signIn();

        $subscriptionOne = factory('App\Billing\Subscription')->create(['user_id' => $user->id]);

        $this->assertInstanceOf('App\Billing\Subscription', $user->subscriptions()->first());
    }

    /** @test */
    public function it_returns_the_correct_subscription_builder_class()
    {
        $user = $this->signIn();

        $this->assertInstanceOf('App\Billing\SubscriptionBuilder', $user->newSubscription(null, null));
    }

    /** @test */
    public function it_can_return_a_subscription_if_no_name_is_given()
    {
        $user = $this->signIn();

        $subscriptionOne = factory('App\Billing\Subscription')->create(['user_id' => $user->id, 'name' => 'Pro', 'stripe_plan' => 'one']);

        $this->assertEquals($user->subscription()->stripe_plan, 'one');
    }

    /** @test */
    public function it_can_return_a_subscription_if_a_name_is_given()
    {
        $user = $this->signIn();

        $subscriptionOne = factory('App\Billing\Subscription')->create(['user_id' => $user->id, 'name' => 'Pro', 'stripe_plan' => 'one']);
        $subscriptionTwo = factory('App\Billing\Subscription')->create(['user_id' => $user->id, 'name' => 'Enterprise', 'stripe_plan' => 'two']);

        $this->assertEquals($user->subscription('Pro')->stripe_plan, 'one');
        $this->assertEquals($user->subscription('Enterprise')->stripe_plan, 'two');
    }

    /** @test */
    public function it_stores_the_current_period_end_when_a_new_subscription_is_created()
    {
        $user = $this->signIn();

        $subscription = $user->newSubscription('Pro', 'plan_ErRIL8fIR4sfRt')->create($this->getStripeToken());

        $subscription = $subscription->asStripeSubscription();

        $this->assertEquals($user->subscription('Pro')->current_period_end->timestamp, $subscription->current_period_end);
    }
}
