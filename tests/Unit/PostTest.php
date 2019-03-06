<?php

namespace Tests\Unit;

use Tests\TestCase;

class PostTest extends TestCase
{
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
}
