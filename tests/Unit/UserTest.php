<?php

namespace Tests\Unit;

use App\Plans\Facades\Plans;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

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

        $profile->attachUser($user);

        $this->assertTrue($user->attached($profile->username));
    }

    /** @test */
    public function it_can_check_if_a_post_was_uploaded_before_the_profile_user_attachment()
    {
        $user = $this->signIn();

        $profile = factory('App\Profile')->create();
        $profile->attachUser($user);

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

        $plan = Plans::first();

        $this->subscribe($plan->paddle_id);

        $this->assertInstanceOf(get_class($plan), $this->user->fresh()->plan());
    }

    /** @test */
    public function it_returns_the_feed()
    {
        // Given we have two users, it should get the posts attached to the profiles the given user follows
        // and not those of the other user
        $authUser = factory('App\User')->create();
        $otherUser = factory('App\User')->create();

        // Both posts are attached to a profile $authUser follows
        $profileOne = factory('App\Profile')->create();
        $profileOne->attachUser($authUser);
        [$postOne, $postTwo] = factory('App\Post', 2)->create(['profile_id' => $profileOne->id]);

        // Not attached to $authUser
        $profileTwo = factory('App\Profile')->create();
        $profileTwo->attachUser($otherUser);
        $postThree = factory('App\Post')->create(['profile_id' => $profileTwo->id]);

        // Post is attached to a profile both $authUser and $otherUser follow
        $profileThree = factory('App\Profile')->create();
        $profileThree->attachUser($authUser);
        $profileThree->attachUser($otherUser);
        $postFour = factory('App\Post')->create(['profile_id' => $profileThree->id]);

        $posts = $authUser->feed();

        $this->assertTrue($posts->contains($postOne));
        $this->assertTrue($posts->contains($postTwo));
        $this->assertFalse($posts->contains($postThree));
        $this->assertTrue($posts->contains($postFour));
    }
}
