<?php

namespace Tests\Unit;

use App\Profile;
use Tests\TestCase;
use App\Notifications\NewPostAdded;
use Illuminate\Support\Facades\Notification;
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

        // It shouldn't exist in the database
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

    /** @test */
    public function it_can_have_followers()
    {
        $user = factory('App\User')->create();
        $profile = factory('App\Profile')->create();
        $profile->attachUser($user);

        $this->assertTrue($profile->followers->contains($user));
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $profile->followers);
    }

    /** @test */
    public function it_notifies_followers_of_new_posts()
    {
        Notification::fake();

        // Given
        $profile = factory('App\Profile')->create();
        $users = factory('App\User', 2)->create();

        $profile->attachUser($users[0]);
        $profile->attachUser($users[1]);

        $post = factory('App\Post')->create(['profile_id' => $profile->id]);

        // When
        $profile->notifyFollowers($post);

        // Then
        Notification::assertSentTo($users[0], NewPostAdded::class);
        Notification::assertSentTo($users[1], NewPostAdded::class);
    }

    /** @test */
    public function it_doesnt_notify_users_who_dont_follow_the_profile()
    {
        Notification::fake();

        // Given
        $profile = factory('App\Profile')->create();
        $user = factory('App\User')->create();

        $post = factory('App\Post')->create(['profile_id' => $profile->id]);

        // When
        $profile->notifyFollowers($post);

        // Then
        Notification::assertNotSentTo($user, NewPostAdded::class);
    }
}
