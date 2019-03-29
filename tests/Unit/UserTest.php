<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp()
    {
        parent::setUp();

        $this->user = $this->signIn();
    }

    /** @test */
    public function it_is_attached_to_profiles()
    {
        $profile = factory('App\Profile')->create();

        \DB::table('profile_user')->insert([
            'profile_id' => $profile->id,
            'user_id' => $this->user->id
        ]);

        $this->assertTrue($this->user->profiles->contains($profile));
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $this->user->profiles);
    }

    /** @test */
    public function it_can_have_details()
    {
        $details = factory('App\UserDetails')->create(['user_id' => $this->user->id]);

        $this->assertInstanceOf('App\UserDetails', $this->user->details);
    }
}
