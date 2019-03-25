<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Repositories\PostRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Libraries\Instagram\Transport\TransportFeed;

class CheckNewPosts extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_stores_new_posts_from_instagram()
    {
        $this->withoutExceptionHandling();
        $this->createMocks();

        /*
            Profiles:
            1. Has one post in DB and a new post on IG
            2. Has one post in DB but no new post on IG
            3. Has no post in DB and also no new post on IG
            4. Has no post in DB but multiple new posts on IG
        */
        $profileOne = factory('App\Profile')->create(['username' => 'daviddobrik']);
        factory('App\Post')->create(['profile_id' => $profileOne->id, 'ig_post_id' => 1996822142546072191]);

        $profileTwo = factory('App\Profile')->create(['username' => 'fcbarcelona']);
        factory('App\Post')->create(['profile_id' => $profileTwo->id, 'ig_post_id' => 2005270146746340578]);

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
