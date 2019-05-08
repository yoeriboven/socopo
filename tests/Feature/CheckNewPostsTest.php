<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Carbon;
use App\Notifications\NewPostAdded;
use App\Repositories\PostRepository;
use Illuminate\Support\Facades\Notification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Libraries\Instagram\Transport\TransportFeed;

class CheckNewPostsTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->createMocks();
    }

    /** @test */
    public function it_stores_new_posts_from_instagram()
    {
        // This is used as the posted_at date because `latestForProfiles` returns the post with the most recent posted_at
        $historicPostedAt = Carbon::now()->subYear();

        /*
            Profiles:
            1. Has one post in DB and a new post on IG
            2. Has one post in DB but no new post on IG
            3. Has no post in DB and also no new post on IG
            4. Has no post in DB but multiple new posts on IG
        */
        $profileOne = factory('App\Profile')->create(['username' => 'daviddobrik']);
        factory('App\Post')->create(['profile_id' => $profileOne->id, 'ig_post_id' => 1996822142546072191, 'posted_at' => $historicPostedAt]);

        $profileTwo = factory('App\Profile')->create(['username' => 'fcbarcelona']);
        factory('App\Post')->create(['profile_id' => $profileTwo->id, 'ig_post_id' => 2005270146746340578, 'posted_at' => $historicPostedAt]);

        $profileThree = factory('App\Profile')->create(['username' => 'yoeriboven']);

        $profileFour = factory('App\Profile')->create(['username' => 'afcajax']);

        // When we call the command
        (new \App\InstagramCommand)->handle();

        // Then we should see new posts
        $posts = (new PostRepository)->latestForProfiles(collect([$profileOne, $profileTwo, $profileThree, $profileFour]));

        $this->assertEquals($posts->get($profileOne->id)->ig_post_id, 2003941048976752983);
        $this->assertEquals($posts->get($profileTwo->id)->ig_post_id, 2005270146746340578);
        $this->assertFalse($posts->has($profileThree->id));
        $this->assertEquals($posts->get($profileFour->id)->ig_post_id, 2004749804235801150);
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
        $profile->followers()->where('user_id', $user->id)->first()->pivot->update(['created_at' => Carbon::now()->subYears(20)]);

        // When we call the command
        (new \App\InstagramCommand)->handle();

        // Then a notification should be sent
        Notification::assertSentTo($user, NewPostAdded::class);
    }

    /** @test */
    public function it_stores_the_correct_date()
    {
        $this->withoutExceptionHandling();

        $profile = factory('App\Profile')->create(['username' => 'daviddobrik']);

        (new \App\InstagramCommand)->handle();

        $posts = (new PostRepository)->latestForProfiles(collect([$profile]));

        $this->assertEquals($posts->get($profile->id)->posted_at, new Carbon("2019-03-20 19:00:10"));
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
        $jsonData = file_get_contents(__DIR__ . '/stubs/'.$username.'.json');

        $data = json_decode($jsonData);

        return $data->entry_data->ProfilePage[0]->graphql->user;
    }
}
