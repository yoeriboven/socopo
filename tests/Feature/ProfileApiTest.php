<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProfileApiTest extends TestCase
{
    use RefreshDatabase;

    const EXPECTED_AVATAR = "https://scontent-ams3-1.cdninstagram.com/vp/939c87a37fda9a5a14cb6fb2a160f562/5D11BFD4/t51.2885-19/s150x150/13774452_308754809468576_1008534704_a.jpg?_nc_ht=scontent-ams3-1.cdninstagram.com";

    /** @test */
    public function guest_cant_access_profile_controller()
    {
        $this->get('api/profiles')
            ->assertRedirect('login');

        $this->delete('api/profiles/1')
            ->assertRedirect('login');

        $this->post('api/profiles')
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
        $this->assertDatabaseMissing('profile_user', [
            'profile_id' => $profile->id,
            'user_id' => $user->id
        ]);
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

    /** @test */
    public function an_authenticated_user_can_add_profiles()
    {
        $this->withoutExceptionHandling();
        $user = $this->signIn();

        // Create the profile
        $profile = $this->publishProfile(['username' => 'yoeriboven']);

        // Assert it has been created
        $this->assertDatabaseHas('profiles', [
            'username' => $profile->username,
            'avatar' => self::EXPECTED_AVATAR
        ]);

        // Assert it is attached to the signed in user
        $createdProfile = Profile::where('username', $profile->username)->get()->first();
        $this->assertDatabaseHas('profile_user', [
            'profile_id' => $createdProfile->id,
            'user_id' => $user->id
        ]);
    }

    protected function publishProfile($overrides = [])
    {
        $profile = factory('App\Profile')->make($overrides);

        $this->post('/api/profiles', $profile->toArray());

        return $profile;
    }
}
