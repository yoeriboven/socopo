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
        $notAttached = factory('App\Profile')->create();

        $attachedOne->attachToUser();
        $attachedTwo->attachToUser();

        $this->browse(function (Browser $browser) use ($user, $attachedOne, $attachedTwo, $notAttached) {
            $browser->loginAs($user)
                    ->visit('/')
                    ->click('#modalOpener')
                    ->whenAvailable('.modal', function ($modal) use ($attachedOne, $attachedTwo, $notAttached) {
                        $modal->assertSee($attachedOne->username);
                        $modal->assertSee($attachedTwo->username);
                        $modal->assertDontSee($notAttached->username);
                    });
        });
    }
}
