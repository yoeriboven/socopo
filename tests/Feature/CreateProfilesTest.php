<?php

namespace Tests\Feature;

use App\Profile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreateProfilesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guests_cant_add_profiles()
    {
        $this->post('profiles')
             ->assertRedirect('login');
    }

    /** @test */
    public function an_authenticated_user_can_add_a_profile()
    {
        $this->withoutExceptionHandling();
        $user = $this->signIn();

        // Create the profile
        $profile = $this->publishProfile();

        // Assert it has been created
        $this->assertDatabaseHas('profiles', ['username' => $profile->username]);

        // Assert it is attached to the signed in user
        $createdProfile = Profile::where('username', $profile->username)->get()->first();
        $this->assertDatabaseHas('profile_user', [
            'profile_id' => $createdProfile->id,
            'user_id' => $user->id
        ]);
    }

    /** @test */
    public function a_username_is_unique()
    {
        $this->withoutExceptionHandling();
        $this->signIn();

        $this->assertCount(0, Profile::all());

        $this->publishProfile(['username' => 'brucewayne']);
        $this->publishProfile(['username' => 'brucewayne']);

        $this->assertCount(1, Profile::all());
    }

    protected function publishProfile($overrides = [])
    {
        $profile = factory('App\Profile')->make($overrides);

        $this->post('/profiles', $profile->toArray());

        return $profile;
    }
}
