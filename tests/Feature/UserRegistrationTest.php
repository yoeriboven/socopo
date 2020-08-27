<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserRegistrationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_user_needs_to_be_verified()
    {
        $user = factory('App\User')->create(['email_verified_at' => null]);
        $this->actingAs($user);

        $this->get(route('home'))
            ->assertRedirect('/email/verify');
    }

    /** @test */
    public function a_customer_is_created_upon_registration()
    {
        $this->post(route('register'), [
            'email' => 'test@test.com',
            'password' => 'password',
            'password_confirmation' => 'password'
        ]);

        $user = User::whereEmail('test@test.com')->first();

        $this->assertDatabaseHas('customers', [
            'billable_id' => $user->id,
            'billable_type' => 'App\User',
            'trial_ends_at' => now()->addDays(14),
        ]);
    }
}
