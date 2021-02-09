<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class ProfilesTest extends DuskTestCase
{
    use DatabaseMigrations;

    /** @test */
    public function it_loads_the_profiles_attached_to_the_authorized_user()
    {
        $user = $this->signIn();

        $attachedOne = factory('App\Profile')->create();
        $attachedTwo = factory('App\Profile')->create();

        $attachedOne->attachUser();
        $attachedTwo->attachUser();

        $this->browse(function (Browser $browser) use ($user, $attachedOne, $attachedTwo) {
            $browser->loginAs($user)
                ->visit('/posts')
                ->click('#modalOpener')
                ->whenAvailable('.modal', function ($modal) use ($attachedOne, $attachedTwo) {
                        $modal->assertSee($attachedOne->username);
                        $modal->assertSee($attachedTwo->username);
                    });
        });
    }

    /** @test */
    public function a_user_can_detach_a_profile()
    {
        $user = $this->signIn();

        $attached = factory('App\Profile')->create();
        $attached->attachUser();

        $this->browse(function (Browser $browser) use ($user, $attached) {
            $browser->loginAs($user)
                ->visit('/posts')
                ->click('#modalOpener')
                ->pause(500)
                ->whenAvailable('.modal', function ($modal) use ($attached) {
                        $modal->assertSee($attached->username)
                            ->click("@delete-profile-{$attached->id}")
                            ->pause(500)
                            ->assertDontSee($attached->username);
                    });
        });
    }

    /** @test */
    public function a_user_can_add_a_profile()
    {
        $user = $this->signIn();

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                ->visit('/posts')
                ->click('#modalOpener')
                ->pause(500)
                ->whenAvailable('.modal', function ($modal) {
                        $modal->type('username', 'daviddobrik')
                            ->click('@add-username')
                            ->waitFor('.alert-success')
                            ->assertSee('@daviddobrik');
                    });
        });
    }
}
