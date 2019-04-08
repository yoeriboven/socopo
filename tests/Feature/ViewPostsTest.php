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

    protected function createPostForUser($user)
    {
        $profile = factory('App\Profile')->create();
        $profile->attachUser($user);

        return factory('App\Post')->create(['profile_id' => $profile->id]);
    }
}
