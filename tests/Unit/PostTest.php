<?php

namespace Tests\Unit;

use App\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class PostTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_has_a_profile()
    {
        $profile = factory('App\Profile')->create();
        $post = factory('App\Post')->create(['profile_id' => $profile->id]);

        $this->assertTrue($profile->is($post->profile));
        $this->assertInstanceOf('App\Profile', $post->profile);
    }

    /** @test */
    public function it_returns_the_correct_image_url()
    {
        $profile = factory('App\Profile')->create();
        $post = factory('App\Post')->create([
            'post_url' => 'http://www.instagram.com/p/eiaoei/',
            'profile_id' => $profile->id,
        ]);

        $this->assertEquals('http://www.instagram.com/p/eiaoei/media/?size=l', $post->image_url);
    }

    /** @test */
    public function it_fetches_the_latest_post_for_the_given_profile()
    {
        $profile = factory('App\Profile')->create();

        $post = factory('App\Post')->create(['posted_at' => Carbon::now(), 'profile_id' => $profile->id]);
        factory('App\Post')->create(['posted_at' => Carbon::now()->subWeek(), 'profile_id' => $profile->id]);

        $storedPost = Post::latestForProfile($profile);

        $this->assertTrue($storedPost->is($post));
    }

    /** @test */
    public function it_fetches_the_post_based_on_posted_at_and_not_on_the_id()
    {
        $profile = factory('App\Profile')->create();

        factory('App\Post')->create(['posted_at' => Carbon::now()->subWeek(), 'profile_id' => $profile->id]);
        $post = factory('App\Post')->create(['posted_at' => Carbon::now(), 'profile_id' => $profile->id]);

        $storedPost = Post::latestForProfile($profile);

        $this->assertTrue($storedPost->is($post));
    }
}
