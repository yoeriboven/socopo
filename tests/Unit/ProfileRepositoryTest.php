<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Repositories\ProfileRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProfileRepositoryTest extends TestCase
{
    use RefreshDatabase;

    protected $profiles;

    public function setUp()
    {
        parent::setUp();

        $this->profiles = new ProfileRepository();
    }

    /** @test */
    public function it_can_check_if_a_profile_is_attached_already()
    {
        $user = $this->signIn();

        $profile = factory('App\Profile')->create();

        $this->assertFalse($this->profiles->attached($profile->username));

        $profile->attachUser();

        $this->assertTrue($this->profiles->attached($profile->username));
    }
}
