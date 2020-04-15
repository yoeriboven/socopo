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
        $details = factory('App\UserDetails')->make();
        $user->details()->update($details->toArray());

        $this->get('settings')
            ->assertSee(e($details->name))
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

    /** @test */
    public function it_has_simple_validation_in_place()
    {
        $user = $this->signIn();

        $minTester = 'D';
        $max15Tester = 'Fifteen is the max.';
        $max50Tester = 'Fifty should be the maximum amount of characters for this field.';
        $max100Tester = 'This text is longer than one hundred characters and therefore should be marked invalid by the validator.';

        $invalidValues = [
            'name' => [null, '', $minTester, $max50Tester],
            'address' => [null, '', $minTester, $max100Tester],
            'postal' => [null, '', $max15Tester],
            'city' => [null, '', $max50Tester]
        ];

        foreach ($invalidValues as $field => $values) {
            foreach ($values as $value) {
                $details = factory('App\UserDetails')->make([$field => $value]);

                $this->post('settings/details', $details->toArray())
                    ->assertSessionHasErrors($field);
            }
        }
    }

    /** @test */
    public function a_country_is_required_and_needs_to_exist_in_the_countries_list()
    {
        $user = $this->signIn();

        $details = factory('App\UserDetails')->make(['country' => null]);
        $this->post('settings/details', $details->toArray())
            ->assertSessionHasErrors('country');

        $details = factory('App\UserDetails')->make(['country' => 'NL']);
        $this->post('settings/details', $details->toArray())
            ->assertSessionDoesntHaveErrors('country');

        $details = factory('App\UserDetails')->make(['country' => 'XOXO']);
        $this->post('settings/details', $details->toArray())
            ->assertSessionHasErrors('country');
    }
}
