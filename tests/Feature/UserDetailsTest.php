<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserDetailsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_saves_the_users_details()
    {
        $this->withoutExceptionHandling();
        $user = $this->signIn();

        $attributes = [
            'vat_id' => 'NL019301B01',
            'name' => 'Yoeri.me',
            'address' => 'De Werf 9',
            'postal' => '9514CN',
            'city' => 'Gasselternijveen',
            'country' => 'NL'
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
        $details = factory('App\UserDetails')->create(['user_id' => $user->id, 'vat_id' => 'NL102910397B01']);

        $this->get('settings')
            ->assertSee($details->name)
            ->assertSee($details->vat_id)
            ->assertSee($details->address)
            ->assertSee($details->postal)
            ->assertSee($details->city);
    }
}
