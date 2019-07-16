<?php

namespace Tests\Feature;

use Tests\TestCase;
use Tests\Traits\InteractsWithStripe;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CancelPlanTest extends TestCase
{
    use RefreshDatabase, InteractsWithStripe;

    /** @test */
    public function unauthorized_users_cant_unsubscribe()
    {
        $this->delete('subscription/cancel/1')
            ->assertRedirect(route('login'));
    }

    /** @test */
    public function an_authorized_user_can_cancel_a_subscription()
    {
        $user = $this->signIn();

        $plan_id = app('plans')->first()->stripe_id;
        $subscription = $user->newSubscription('Pro', $plan_id)->create($this->getStripeToken());

        $this->assertFalse($user->subscription('Pro')->cancelled());

        $this->delete('subscription/cancel/'.$subscription->id);

        $this->assertTrue($user->fresh()->subscription('Pro')->cancelled());
    }
}
