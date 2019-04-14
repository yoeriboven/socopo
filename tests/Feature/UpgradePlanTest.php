<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpgradePlanTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function unauthorized_users_cant_access_the_upgrade_pages()
    {
        $this->post('upgrade')
            ->assertRedirect('login');
    }

    /** @test */
    public function an_authorized_user_can_change_their_user_details_on_the_upgrade_page()
    {
        $this->withoutExceptionHandling();
        $this->signIn();

        $user_details = [
            'vat_id' => 'NL852924574B01',
            'name' => 'Yoeri.me',
            'address' => 'De Werf 9',
            'postal' => '9514CN',
            'city' => 'Gasselternijveen',
            'country' => 'NL'
        ];

        $subscription_data = [
            'plan' => 'the_plan',
            'token' => ''
        ];

        $this->post('upgrade', array_merge($user_details, $subscription_data));
        $this->assertDatabaseHas('user_details', $user_details);
    }

    // Plan in array
    /**
     * Validation is tested in ChangeUserDetailsTest
     * This test is to check if it is implemented
     */
    /** @test */
    public function it_uses_the_validation_rules_for_user_details()
    {
        $this->signIn();

        $token = '';

        $user_details = [
            'vat_id' => 'NL852924574B01',
            'name' => '',
            'address' => 'De Werf 9',
            'postal' => '9514CN',
            'city' => 'Gasselternijveen',
            'country' => 'NL'
        ];

        $subscription_data = [
            'plan' => 'the_plan',
            'token' => $token
        ];

        $this->post('upgrade', array_merge($user_details, $subscription_data))
            ->assertSessionHasErrors('name');
    }
}
