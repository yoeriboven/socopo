<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Support\Carbon;
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
    public function it_can_check_if_a_profile_is_attached_already()
    {
        $user = $this->signIn();

        $profile = factory('App\Profile')->create();

        $this->assertFalse($user->attached($profile->username));

        $profile->attachUser();

        $this->assertTrue($user->attached($profile->username));
    }

    /** @test */
    public function profiles_are_ordered_in_descending_order_by_when_they_were_attached()
    {
        $user = $this->signIn();

        $profiles_ids = [];

        for ($i = 3; $i>=1; $i--) {
            $profile = factory('App\Profile')->create()->attachUser($user);
            $profiles_ids[] = $profile->id;

            // Because all Profiles in this test are attached at the same time
            // the date of the attachment needs to change so this test works correctly
            $one = $user->profiles()->where('id', $profile->id)->first();
            $one->pivot->created_at = Carbon::now()->subDays($i);
            $one->pivot->save();
        }

        $this->assertEquals(array_reverse($profiles_ids), $user->profiles->pluck('id')->toArray());
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

    /** @test */
    public function it_can_verify_vat_numbers()
    {
        $this->user->details->update(['vat_id' => 'NL812334966B01']);
        $this->assertTrue($this->user->isBusiness());

        $this->user->details->update(['vat_id' => 'invalid_vat_id']);
        $this->assertFalse($this->user->isBusiness());
    }
}
