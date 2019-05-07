<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ViewPostsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guests_cant_view_posts()
    {
        $this->get('/')
             ->assertRedirect('login');
    }

    /** @test */
    public function an_authorized_user_can_only_view_its_own_posts()
    {
        // Given we have a user that follows a profile with a post
        $user = $this->signIn();
        $user->settings->update(['slack_url' => 'something']);

        // The user should see this post
        $post = $this->createPostForUser($user);

        // The user doesn't follow the next profile so shouldn't see it's post
        $hiddenProfile = factory('App\Profile')->create();
        $hiddenPost = factory('App\Post')->create(['profile_id' => $hiddenProfile->id]);

        // When he visits the posts page
        // Then he sees the posts
        $this->get(route('posts.index'))
            ->assertSee($post->caption)
            ->assertDontSee($hiddenPost->caption);
    }

    /** @test */
    public function if_an_authorized_user_doesnt_follow_profiles_it_should_see_a_setup_view()
    {
        // Given we have a user that follows 0 profiles
        $user = $this->signIn();

        // When he visits the posts page
        // Then he should see set up
        $this->get(route('posts.index'))
            ->assertSee('Set up');
    }

    /** @test */
    public function if_an_authorized_user_doesnt_have_slack_setup_it_should_see_a_setup_view()
    {
        // Given we have a user that follows a profile with a post but doesnt have slack setup
        $user = $this->signIn();

        // The user should see this post
        $post = $this->createPostForUser($user);

        // When he visits the posts page
        // Then he shouldn't see the post
        $this->get(route('posts.index'))
            ->assertSee('Set up')
            ->assertDontSee($post->caption);
    }

    protected function createPostForUser($user)
    {
        $profile = factory('App\Profile')->create();
        $profile->attachUser($user);

        return factory('App\Post')->create(['profile_id' => $profile->id]);
    }
}
