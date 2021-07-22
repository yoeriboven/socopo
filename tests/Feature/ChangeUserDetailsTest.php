<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ChangeUserDetailsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guests_may_not_change_details()
    {
        $this->post('settings/details')
            ->assertRedirect(route('login'));
    }

    /** @test */
    public function it_saves_the_users_details()
    {
        $user = $this->signIn();

        $attributes = [
            'name' => 'Yoeri.me',
            'address' => 'De Werf 9',
            'postal' => '9514CN',
            'city' => 'Gasselternijveen',
            'country' => 'NL',
        ];

        $details = factory('App\UserDetails')->make($attributes);

        $this->post('settings/details', $details->toArray());

        $this->assertDatabaseHas('user_details', $details->toArray());
        $this->assertEquals($user->details->country, $attributes['country']);
    }

    /** @test */
    public function it_shows_the_current_details()
    {
        $user = $this->signIn();
        $details = factory('App\UserDetails')->make();
        $user->details()->update($details->toArray());

        $this->get('settings')
            ->assertSee($details->name)
            ->assertSee($details->address)
            ->assertSee($details->postal)
            ->assertSee($details->city);
    }

    /** @test */
    public function it_can_update_details()
    {
        $user = $this->signIn();

        // Initial details
        $details = factory('App\UserDetails')->make();
        $this->post('settings/details', $details->toArray());

        $this->assertEquals($user->details->name, $details->name);

        // Updated details
        $details->name = 'Yoeri.me';
        $this->post('settings/details', $details->toArray());

        $this->assertEquals($user->details->fresh()->name, 'Yoeri.me');
    }
}
