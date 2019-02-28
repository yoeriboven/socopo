<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

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

    /** @test */
    public function it_can_update_the_avatar()
    {
        $profile = factory('App\Profile')->create([
            'avatar' => 'instagramURL'
        ]);

        $profile->updateAvatar('new link');

        $this->assertEquals('new link', $profile->avatar);
    }
}
