<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    /**
     * Signs in a user.
     *
     * @param  App\User $user An optional user to sign in
     * @return App\User       Signed in user
     */
    public function signIn($user = null)
    {
        if (!$user) {
            $user = factory('App\User')->create();
        }

        $this->user = $user;

        $this->actingAs($this->user);

        return $this->user;
    }

    protected function subscribe($planId = 627813)
    {
        $this->user->createAsCustomer();
        $this->user->subscriptions()->create([
            'name' => 'default',
            'paddle_id' => 244,
            'paddle_plan' => $planId,
            'paddle_status' => 'active',
            'quantity' => 1,
        ]);
    }
}
