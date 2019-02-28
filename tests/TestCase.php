<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    /**
     * Signs in a user
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
}
