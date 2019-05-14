<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PostTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_has_a_profile()
    {
        $user = $this->signIn();

        $profile = factory('App\Profile')->create();
        $profile->attachUser($user);

        $post = factory('App\Post')->create(['profile_id' => $profile->id]);

        $this->assertTrue($profile->is($post->profile));
        $this->assertInstanceOf('App\Profile', $post->profile);
    }

    /** @test */
    public function it_returns_the_correct_image_url()
    {
        $user = $this->signIn();

        $profile = factory('App\Profile')->create();
        $profile->attachUser($user);

        $post = factory('App\Post')->create(['post_url' => 'http://www.instagram.com/p/eiaoei/', 'profile_id' => $profile->id]);

        $this->assertEquals('http://www.instagram.com/p/eiaoei/media/?size=l', $post->image_url);
    }
}
