<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = $this->signIn();
    }

    /** @test */
    public function it_is_attached_to_profiles()
    {
        $profile = factory('App\Profile')->create();
        $profile->attachUser($this->user);

        $this->assertTrue($this->user->profiles->contains($profile));
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $this->user->profiles);
    }

    /** @test */
    public function it_can_have_details()
    {
        factory('App\UserDetails')->create(['user_id' => $this->user->id]);

        $this->assertInstanceOf('App\UserDetails', $this->user->details);
    }

    /** @test */
    public function it_can_have_settings()
    {
        factory('App\Settings')->create(['user_id' => $this->user->id]);

        $this->assertInstanceOf('App\Settings', $this->user->settings);
    }

    /** @test */
    public function it_can_set_the_slack_url()
    {
        $this->user->setSlackUrl('The new Slack url');

        $this->assertEquals('The new Slack url', $this->user->settings->slack_url);
    }

    /** @test */
    public function it_returns_whether_slack_is_setup()
    {
        $this->user->settings->update(['slack_url' => null]);
        $this->assertFalse($this->user->hasSlackSetup());

        $this->user->settings->update(['slack_url' => 'Not null']);
        $this->assertTrue($this->user->hasSlackSetup());
    }
}
