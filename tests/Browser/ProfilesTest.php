<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

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
                    ->visit('/')
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
                    ->visit('/')
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
}
