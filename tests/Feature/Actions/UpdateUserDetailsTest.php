<?php

namespace Tests\Feature\Actions;

use Tests\TestCase;
use Facades\App\Actions\UpdateUserDetails;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdateUserDetailsTest extends TestCase
{
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

        $details = factory('App\UserDetails')->make($attributes)->toArray();

        UpdateUserDetails::update($user, $details);

        $this->assertDatabaseHas('user_details', $details);
        $this->assertEquals($user->details->country, $attributes['country']);
    }

    /** @test */
    public function it_updates_the_users_details()
    {
        $user = $this->signIn();
        $details = factory('App\UserDetails')->make();

        // Initial details
        $details->name = 'Batman';
        UpdateUserDetails::update($user, $details->toArray());

        $this->assertEquals($user->details->name, $details->name);

        // Updated details
        $details->name = 'Superman';
        UpdateUserDetails::update($user, $details->toArray());

        $this->assertEquals($user->details->fresh()->name, 'Superman');
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
                $details = factory('App\UserDetails')->make([$field => $value])->toArray();

                try {
                    UpdateUserDetails::update($user, $details);
                } catch (ValidationException $e) {
                    $this->assertNotNull($e->errors($field));
                }
            }
        }

        $this->assertTrue(true);
    }

    /** @test */
    public function a_country_is_required_and_needs_to_exist_in_the_countries_list()
    {
        $user = $this->signIn();

        // Null is not allowed
        $details = factory('App\UserDetails')->make(['country' => null]);
        try {
            UpdateUserDetails::update($user, $details->toArray());
        } catch (ValidationException $e) {
            $this->assertNotNull($e->errors('country'));
        }

        // NL is a valid country code
        $details = factory('App\UserDetails')->make(['country' => 'NL']);
        try {
            UpdateUserDetails::update($user, $details->toArray());
        } catch (ValidationException $e) {
            $this->assertFail('Validation should pass.');
        }

        // XOXO is not a valid country code
        $details = factory('App\UserDetails')->make(['country' => 'XOXO']);
        try {
            UpdateUserDetails::update($user, $details->toArray());
        } catch (ValidationException $e) {
            $this->assertNotNull($e->errors('country'));
        }
    }
}
