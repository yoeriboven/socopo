<?php

namespace Tests\Unit;

use Tests\TestCase;

class ProfileTest extends TestCase
{
    /** @test */
    public function a_profile_can_be_attached_to_a_user()
    {
        // Given we have a user and a profile
        $user = factory('App\User')->create();
        $profile = factory('App\Profile')->create();

        // When they are attached
        $profile->attachToUser($user);

        // It should show up in the database
        $this->assertDatabaseHas('profile_user', [
            'profile_id' => $profile->id,
            'user_id' => $user->id
        ]);
    }
}
