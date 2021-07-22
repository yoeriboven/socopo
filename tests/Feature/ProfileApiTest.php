<?php

namespace Tests\Feature;

use App\Profile;
use App\Services\Instagram\InstagramDownloader;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfileApiTest extends TestCase
{
    use RefreshDatabase;

    // The same avatar_url as in fixtures/valid.html
    const AVATAR = 'https://scontent-cdg2-1.cdninstagram.com/vp/f49bc1ac9af43314d3354b4c4a987c6d/5B5BB12E/t51.2885-19/10483606_1498368640396196_604136733_a.jpg';

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
        // Given we have a signed in user with a profile
        $user = $this->signIn();
        $profile = factory('App\Profile')->create()->attachUser();

        // When they reach the profile delete endpoint
        $this->delete('api/profiles/'.$profile->id)
            ->assertNoContent();

        // Then it should be deleted
        $this->assertCount(0, $user->profiles);
        $this->assertDatabaseMissing('profile_user', [
            'profile_id' => $profile->id,
            'user_id' => $user->id,
        ]);
    }

    /** @test */
    public function a_user_can_only_detach_their_own_profiles()
    {
        // Given we have a signed in user
        $user = $this->signIn();

        // and a profile that is not attached to them
        $profile = factory('App\Profile')->create();

        $this->delete('api/profiles/'.$profile->id)
            ->assertStatus(401);
    }

    /** @test */
    public function an_authenticated_user_can_add_profiles()
    {
        $user = $this->signIn();

        InstagramDownloader::fake();

        // Create the profile
        $profile = $this->publishProfile(['username' => 'yoeriboven']);

        // Assert it has been created
        $this->assertDatabaseHas('profiles', [
            'username' => $profile->username,
            'avatar' => self::AVATAR,
        ]);

        // Assert it is attached to the signed in user
        $createdProfile = Profile::where('username', $profile->username)->first();
        $this->assertDatabaseHas('profile_user', [
            'profile_id' => $createdProfile->id,
            'user_id' => $user->id,
        ]);
    }

    /** @test */
    public function it_returns_the_profile_after_it_is_created()
    {
        InstagramDownloader::fake();

        $this->signIn();

        $profile = factory('App\Profile')->make();

        $response = $this->post('/api/profiles', $profile->toArray());
        $response->assertJson([
            'profile' => [
                'username' => $profile->username,
            ],
        ]);
    }

    /** @test */
    public function a_profile_requires_a_valid_username()
    {
        // Limit - 30 symbols. Username must contains only letters, numbers, periods and underscores.

        $this->signIn();

        /*
         * 1. Too short (minimum 3)
         * 2. Too long (maximum 30)
         * 3 - ... Character not allowed
         */
        $usernames = [
            'd',
            'thisstringismorethanthirtychars',
            'yoeri%',
            'yoeri(',
            'yoeri!',
            'yoeri&',
            'yo eri',
        ];

        foreach ($usernames as $username) {
            $this->json('POST', '/api/profiles', ['username' => $username])
                ->assertStatus(422);
        }
    }

    /** @test */
    public function it_shows_an_error_if_the_username_doesnt_exist_on_instagram()
    {
        $this->signIn();

        InstagramDownloader::fake()->nonExistentProfile();

        $this->json('POST', '/api/profiles', ['username' => 'eioaienf'])
            ->assertJson(['message' => 'Profile not found for this username.']);
    }

    /** @test */
    public function it_shows_an_error_if_the_instagram_profile_is_private()
    {
        $this->signIn();

        InstagramDownloader::fake()->privateProfile();

        $this->json('POST', '/api/profiles', ['username' => 'yoeriboven'])
            ->assertJson(['message' => 'This Instagram account is private.']);
    }

    /** @test */
    public function a_username_is_unique()
    {
        InstagramDownloader::fake();

        $this->signIn();

        $this->assertCount(0, Profile::all());

        $this->publishProfile(['username' => 'daviddobrik']);
        $this->publishProfile(['username' => 'daviddobrik']);

        $this->assertCount(1, Profile::all());
    }

    /** @test */
    public function a_status_code_is_shown_when_an_account_tries_to_be_attached_again()
    {
        InstagramDownloader::fake();

        $this->signIn();

        $profile = factory('App\Profile')->make();

        $this->post('/api/profiles', $profile->toArray());

        $this->post('/api/profiles', $profile->toArray())
            ->assertJson(['message' => 'Profile has already been added.']);
    }

    /** @test */
    public function it_shows_an_error_if_the_max_profiles_of_your_subscription_was_found()
    {
        $user = $this->signIn();

        // Attach the max amount of profiles on a user's plan
        factory('App\Profile', $user->plan()->maxProfiles)
            ->create()
            ->each(function($profile) use ($user) {
                $profile->attachUser($user);
            });

        // Check for an error if you attach more
        $profile = factory('App\Profile')->make();
        $this->post('/api/profiles', $profile->toArray())
            ->assertStatus(403)
            ->assertJson(['message' => 'You have reached your maximum amount of profiles. Upgrade your account.']);
    }

    protected function publishProfile($overrides = [])
    {
        $profile = factory('App\Profile')->make($overrides);

        $this->post('/api/profiles', $profile->toArray());

        return $profile;
    }
}
