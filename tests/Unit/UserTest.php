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
    public function it_can_check_if_a_post_was_uploaded_before_the_profile_user_attachment()
    {
        $user = $this->signIn();

        $profile = factory('App\Profile')->create();
        $profile->attachUser();

        // This post was posted on IG before the profile was attached to the user
        $post = factory('App\Post')->create(['profile_id' => $profile->id, 'posted_at' => Carbon::now()->subDays(1)]);

        // Now $user contains a pivot table
        $user = $profile->followers()->find($user->id);

        $this->assertTrue($user->attachedAfterPostPublished($post));
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
        $this->assertInstanceOf('App\UserDetails', $this->user->details);
    }

    /** @test */
    public function a_user_details_record_is_created_upon_creating_a_user()
    {
        $this->assertNotNull($this->user->details);
    }

    /** @test */
    public function it_can_have_settings()
    {
        $this->assertInstanceOf('App\Settings', $this->user->settings);
    }

    /** @test */
    public function a_settings_record_is_created_upon_creating_a_user()
    {
        $this->assertNotNull($this->user->settings);
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
    public function it_returns_the_correct_plan()
    {
        $this->assertInstanceOf('\App\Plans\Plans\FreePlan', $this->user->plan());

        $this->user->createAsCustomer();
        $this->user->subscriptions()->create([
            'name' => 'default',
            'paddle_id' => 244,
            'paddle_plan' => 629570,
            'paddle_status' => 'active',
            'quantity' => 1,
        ]);

        $this->assertInstanceOf('\App\Plans\Plans\ProPlan', $this->user->plan());
    }
}
