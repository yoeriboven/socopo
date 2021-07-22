<?php

namespace Tests\Unit;

use App\Profile;
use Tests\TestCase;
use Illuminate\Support\Carbon;
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
            'user_id' => $user->id,
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
            'user_id' => $user->id,
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
            'avatar' => 'instagramURL',
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
    public function it_returns_the_correct_profile_url()
    {
        $profile = factory('App\Profile')->create(['username' => 'afcajax']);

        $this->assertEquals('https://www.instagram.com/afcajax', $profile->url);
    }

    /** @test */
    public function it_notifies_followers_of_new_posts()
    {
        $this->withoutExceptionHandling();
        Notification::fake();

        // Given
        $profile = factory('App\Profile')->create();
        $users = factory('App\User', 2)->create();
        $profile->attachUser($users[0]);
        $profile->attachUser($users[1]);

        // Notifications won't be sent if you don't have a slack_url to send it to
        $users[0]->settings->update(['slack_url' => 'Something']);
        $users[1]->settings->update(['slack_url' => 'Something']);

        $post = factory('App\Post')->create(['profile_id' => $profile->id, 'posted_at' => Carbon::now()->addDays(1)]);

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
        $user->settings->update(['slack_url' => 'Something']);

        $post = factory('App\Post')->create(['profile_id' => $profile->id]);

        // When
        $profile->notifyFollowers($post);

        // Then
        Notification::assertNotSentTo($user, NewPostAdded::class);
    }

    /** @test */
    public function it_doesnt_notify_users_who_dont_have_slack_set_up()
    {
        Notification::fake();

        // Given
        $user = factory('App\User')->create();
        $user->settings()->update(['slack_url' => null]);

        $profile = factory('App\Profile')->create();
        $profile->attachUser($user);

        $post = factory('App\Post')->create(['profile_id' => $profile->id]);

        // When
        $profile->notifyFollowers($post);

        // Then
        Notification::assertNotSentTo($user, NewPostAdded::class);
    }

    /** @test */
    public function it_doesnt_notify_users_who_follow_the_profile_after_the_new_post_has_been_made()
    {
        Notification::fake();

        // Given we have a user,
        $user = factory('App\User')->create();
        $user->settings()->update(['slack_url' => 'Something']);

        // who follows a profile
        $profile = factory('App\Profile')->create();
        $profile->attachUser($user);
        // dump($profile->followers()->where('user_id', $user->id)->first()->pivot->created_at);
        // and the new post is added before the user followed the profile
        $post = factory('App\Post')->create(['profile_id' => $profile->id, 'posted_at' => Carbon::now()->subDays(1)]);

        // When
        $profile->notifyFollowers($post);

        // Then there should be no notification sent
        Notification::assertNotSentTo($user, NewPostAdded::class);
    }
}
