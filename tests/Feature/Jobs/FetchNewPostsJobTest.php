<?php

namespace Tests\Feature\Jobs;

use App\Post;
use Tests\TestCase;
use App\Jobs\FetchNewPostsJob;
use Illuminate\Support\Carbon;
use App\Notifications\NewPostAdded;
use Illuminate\Support\Facades\Notification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Services\Instagram\Transport\TransportFeed;

class FetchNewPostsJobTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->createMocks();
    }

    /**
     * @test
     * Has one post in DB and a new post on IG (so should store new post)
    */
    public function it_stores_posts_scenario_one()
    {
        $historicPostedAt = Carbon::now()->subYears(50);

        $profile = factory('App\Profile')->create(['username' => 'daviddobrik']);
        factory('App\Post')->create(['profile_id' => $profile->id, 'ig_post_id' => 1996822142546072191, 'posted_at' => $historicPostedAt]);

        // When we call the command
        (new FetchNewPostsJob($profile))->handle();

        // Then we should see new posts
        $posts = Post::latestForProfile($profile);

        $this->assertEquals(2003941048976752983, $posts->ig_post_id);
    }

    /**
     * @test
     * Has one post in DB but no new post on IG (so should not store new post)
     */
    public function it_stores_posts_scenario_two()
    {
        $historicPostedAt = Carbon::now()->subYears(50);

        $profile = factory('App\Profile')->create(['username' => 'fcbarcelona']);
        factory('App\Post')->create(['profile_id' => $profile->id, 'ig_post_id' => 2005270146746340578, 'posted_at' => $historicPostedAt]);

        // When we call the command
        (new FetchNewPostsJob($profile))->handle();

        // Then we should see new posts
        $post = Post::latestForProfile($profile);

        $this->assertEquals(2005270146746340578, $post->ig_post_id);
    }

    /**
     * @test
     * Has no post in DB and also no new post on IG (so should have nothing in db)
     */
    public function it_stores_posts_scenario_three()
    {
        $profile = factory('App\Profile')->create(['username' => 'yoeriboven']);

        // When we call the command
        (new FetchNewPostsJob($profile))->handle();

        // Then we should see new posts
        $posts = Post::latestForProfile($profile);

        $this->assertNull($posts);
    }

    /**
     * @test
     * Has no post in DB but multiple new posts on IG (so should store the latest post)
    */
    public function it_stores_posts_scenario_four()
    {
        $profile = factory('App\Profile')->create(['username' => 'afcajax']);

        // When we call the command
        (new FetchNewPostsJob($profile))->handle();

        // Then we should see new posts
        $posts = Post::latestForProfile($profile);

        $this->assertEquals(2004749804235801150, $posts->ig_post_id);
    }

    /** @test */
    public function it_notifies_followers_when_profiles_have_uploaded_a_new_post()
    {
        Notification::fake();

        // Given
        $user = factory('App\User')->create();
        $user->settings->update(['slack_url' => 'Something']);

        $profile = factory('App\Profile')->create(['username' => 'daviddobrik']);
        $profile->attachUser($user);

        // This line is purely because the profile has to be attached before the post was posted
        $profile->followers()->where('user_id', $user->id)->first()->pivot->update(['created_at' => Carbon::now()->subYears(50)]);

        // When we call the command
        (new FetchNewPostsJob($profile))->handle();

        // Then a notification should be sent
        Notification::assertSentTo($user, NewPostAdded::class);
    }

    /** @test */
    public function it_stores_the_correct_date()
    {
        $profile = factory('App\Profile')->create(['username' => 'daviddobrik']);

        (new FetchNewPostsJob($profile))->handle();

        $post = Post::latestForProfile($profile);

        $this->assertEquals($post->posted_at, new Carbon('2019-03-20 19:00:10'));
    }

    /** @test */
    public function it_updates_the_avatar_url()
    {
        $profile = factory('App\Profile')->create(['username' => 'daviddobrik', 'avatar' => '']);
        $this->assertEquals('', $profile->avatar);

        (new FetchNewPostsJob($profile))->handle();

        $this->assertEquals('https://scontent-ams3-1.cdninstagram.com/vp/5ce2d22d0fe7989c48f79cc6f2014a99/5D328198/t51.2885-19/s320x320/40374841_2171773369523155_4420619227124203520_n.jpg?_nc_ht=scontent-ams3-1.cdninstagram.com', $profile->fresh()->avatar);
    }

    private function createMocks()
    {
        $this->mock(TransportFeed::class, function ($mock) {
            $mock->shouldReceive('setClient');
            $mock->shouldReceive('fetchData')->andReturnUsing(function ($username) {
                return $this->jsonToFeedData($username);
            });
        });
    }

    private function jsonToFeedData($username)
    {
        $jsonData = file_get_contents(__DIR__ . '/fixtures/'.$username.'.json');

        $data = json_decode($jsonData);

        return $data->entry_data->ProfilePage[0]->graphql->user;
    }
}
