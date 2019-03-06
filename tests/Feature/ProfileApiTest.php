<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProfileApiTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guest_cant_access_profile_controller()
    {
        $this->get('api/profiles')
            ->assertRedirect('login');

        $this->delete('api/profiles/1')
            ->assertRedirect('login');
    }

    /** @test */
    public function a_user_can_view_the_profiles_they_follow()
    {
        $this->signIn();

        $attachedOne = factory('App\Profile')->create()->attachUser();
        $attachedTwo = factory('App\Profile')->create()->attachUser();
        $notAttached = factory('App\Profile')->create();

        $this->get('api/profiles')
            ->assertJson(collect([$attachedOne, $attachedTwo])->toArray());
    }

    /** @test */
    public function a_user_can_detach_a_profile()
    {
        $this->withoutExceptionHandling();

        // Given we have a signed in user with a profile
        $user = $this->signIn();
        $profile = factory('App\Profile')->create()->attachUser();

        // When they reach the profile delete endpoint
        $this->delete('api/profiles/'.$profile->id);

        // Then it should be deleted
        $this->assertCount(0, $user->profiles);
    }

    /** @test */
    public function a_user_can_only_detach_their_own_profiles()
    {
        $this->withoutExceptionHandling();

        // Given we have a signed in user
        // and a profile that is not attached to them
        $user = $this->signIn();
        $profile = factory('App\Profile')->create();

        $this->delete('api/profiles/'.$profile->id)
            ->assertStatus(401);
    }
}
