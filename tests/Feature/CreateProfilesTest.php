<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateProfilesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function an_authenticated_user_can_add_a_profile()
    {
        $this->withoutExceptionHandling();
        $user = $this->signIn();

        // Create the profile
        $profile = factory('App\Profile')->make();
        $this->post('/profiles', $profile->toArray());

        // Assert it has been created
        $this->assertDatabaseHas('profiles', ['username' => $profile->username]);

        // Assert it is attached to the signed in user
        $createdProfile = \App\Profile::where('username', $profile->username)->get()->first();
        $this->assertDatabaseHas('profile_user', [
            'profile_id' => $createdProfile->id,
            'user_id' => $user->id
        ]);
    }
}
