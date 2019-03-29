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
            'business' => true,
            'country' => 'NL',
            'vat_id' => 'NL019301B01',
            'name' => 'Yoeri.me',
            'street' => 'De Werf 9',
            'postal' => '9514CN',
            'city' => 'Gasselternijveen'
        ];

        $details = factory('App\UserDetails')->make($attributes);

        $this->post('settings/details', $details->toArray());

        $this->assertDatabaseHas('user_details', $details->toArray());
        $this->assertEquals($user->details->country, $attributes['country']);
    }
}
