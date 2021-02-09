<?php

namespace Tests\Unit;

use App\Repositories\PostRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class PostRepositoryTest extends TestCase
{
    use RefreshDatabase;

    protected $posts;

    public function setUp(): void
    {
        parent::setUp();

        $this->posts = new PostRepository();
    }

    /** @test */
    public function it_fetches_the_posts_for_a_given_user()
    {
        // Given we have two users, it should get the posts attached to the profiles the given user follows
        // and not those of the other user
        $authUser = factory('App\User')->create();
        $otherUser = factory('App\User')->create();

        // Both posts are attached to a profile $authUser follows
        $profileOne = factory('App\Profile')->create();
        $profileOne->attachUser($authUser);
        [$postOne, $postTwo] = factory('App\Post', 2)->create(['profile_id' => $profileOne->id]);

        // Not attached to $authUser
        $profileTwo = factory('App\Profile')->create();
        $profileTwo->attachUser($otherUser);
        $postThree = factory('App\Post')->create(['profile_id' => $profileTwo->id]);

        // Post is attached to a profile both $authUser and $otherUser follow
        $profileThree = factory('App\Profile')->create();
        $profileThree->attachUser($authUser);
        $profileThree->attachUser($otherUser);
        $postFour = factory('App\Post')->create(['profile_id' => $profileThree->id]);

        $posts = $this->posts->forUser($authUser);

        $this->assertTrue($posts->contains($postOne));
        $this->assertTrue($posts->contains($postTwo));
        $this->assertFalse($posts->contains($postThree));
        $this->assertTrue($posts->contains($postFour));
    }

    /** @test */
    public function it_fetches_the_latest_post_for_the_given_profile()
    {
        $profile = factory('App\Profile')->create();

        $post = factory('App\Post')->create(['posted_at' => Carbon::now(), 'profile_id' => $profile->id]);
        factory('App\Post')->create(['posted_at' => Carbon::now()->subWeek(), 'profile_id' => $profile->id]);

        $storedPost = $this->posts->latestForProfile($profile);

        $this->assertTrue($storedPost->is($post));
    }

    /** @test */
    public function it_fetches_the_post_based_on_posted_at_and_not_on_the_id()
    {
        $profile = factory('App\Profile')->create();

        factory('App\Post')->create(['posted_at' => Carbon::now()->subWeek(), 'profile_id' => $profile->id]);
        $post = factory('App\Post')->create(['posted_at' => Carbon::now(), 'profile_id' => $profile->id]);

        $storedPost = $this->posts->latestForProfile($profile);

        $this->assertTrue($storedPost->is($post));
    }
}
