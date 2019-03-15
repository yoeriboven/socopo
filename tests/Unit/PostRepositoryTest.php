<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Support\Carbon;
use App\Repositories\PostRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PostRepositoryTest extends TestCase
{
    use RefreshDatabase;

    protected $posts;

    public function setUp()
    {
        parent::setUp();

        $this->posts = new PostRepository();
    }

    /** @test */
    public function it_fetches_the_posts_for_a_given_user()
    {
        $this->withoutExceptionHandling();

        // Given we have two users, it should get the posts attached to the profiles the given user follows
        // and not those of the other user
        $authUser = factory('App\User')->create();
        $otherUser = factory('App\User')->create();

        // Both posts are attached to a profile $authUser follows
        $profileOne = factory('App\Profile')->create();
        $profileOne->attachUser($authUser);
        $postOne = factory('App\Post')->create(['profile_id' => $profileOne->id]);
        $postTwo = factory('App\Post')->create(['profile_id' => $profileOne->id]);

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
    public function it_fetches_the_latest_post_for_the_given_profiles()
    {
        $this->withoutExceptionHandling();

        // Given there are two profiles each with two posts.
        $profileOne = factory('App\Profile')->create();
        $postOne = factory('App\Post')->create(['posted_at' => Carbon::now(), 'profile_id' => $profileOne->id]);
        $postTwo = factory('App\Post')->create(['posted_at' => Carbon::now()->subWeek(), 'profile_id' => $profileOne->id]);

        $profileTwo = factory('App\Profile')->create();
        $postThree = factory('App\Post')->create(['posted_at' => Carbon::now()->subWeek(), 'profile_id' => $profileTwo->id]);
        $postFour = factory('App\Post')->create(['posted_at' => Carbon::now(), 'profile_id' => $profileTwo->id]);

        // When we fetch them
        $posts = $this->posts->latestForProfiles(collect([$profileOne, $profileTwo]));

        // Then it should contain the latest post for both users
        $this->assertTrue($posts->contains($postOne));
        $this->assertFalse($posts->contains($postTwo));
        $this->assertFalse($posts->contains($postThree));
        $this->assertTrue($posts->contains($postFour));
    }
}
