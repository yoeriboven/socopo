<?php

namespace Tests\Feature;

use Tests\TestCase;
use Tests\Traits\InteractsWithStripe;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CancelPlanTest extends TestCase
{
    use RefreshDatabase, InteractsWithStripe;

    /** @test */
    public function unauthorized_users_cant_access_the_endpoints()
    {
        $this->delete('subscription/cancel/1')
            ->assertRedirect(route('login'));
    }

    /** @test */
    public function an_authorized_user_can_cancel_a_subscription()
    {
        $user = $this->signIn();

        $subscription = $user->newSubscription('Pro', 'plan_ErRIL8fIR4sfRt')->create($this->getStripeToken());

        $this->assertFalse($user->subscription()->cancelled());

        $this->delete('subscription/cancel/'.$subscription->id);

        $this->assertTrue($user->fresh()->subscription()->cancelled());
    }
}
