<?php

namespace Tests\Unit;

use App\Profile;
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
        $profile->attachUser($user);

        // It should show up in the database
        $this->assertDatabaseHas('profile_user', [
            'profile_id' => $profile->id,
            'user_id' => $user->id
        ]);
    }

    /** @test */
    public function a_profile_can_be_detached_from_a_user()
    {
        // Given we have a user and a profile
        $user = factory('App\User')->create();
        $profile = factory('App\Profile')->create();

        $profile->attachUser($user);

        $profile->detachUser($user);

        // It should show up in the database
        $this->assertDatabaseMissing('profile_user', [
            'profile_id' => $profile->id,
            'user_id' => $user->id
        ]);
    }

    /** @test */
    public function a_profile_and_user_can_be_attached_only_once()
    {
        $user = factory('App\User')->create();
        $profile = factory('App\Profile')->create();

        $profile->attachUser($user);
        $profile->attachUser($user);

        $this->assertCount(1, $user->profiles);
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
