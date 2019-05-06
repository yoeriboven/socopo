<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserRegistrationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_name_is_not_required()
    {
        $attributes = [
            'email' => 'test@yoeri.me',
            'password' => 'test_password',
            'password_confirmation' => 'test_password'
        ];

        $this->post('register', $attributes);

        $this->assertDatabaseHas('users', [
            'email' => 'test@yoeri.me',
            'name' => null
        ]);
    }

    /** @test */
    public function a_user_needs_to_be_verified()
    {
        $user = factory('App\User')->create(['email_verified_at' => null]);
        $this->actingAs($user);

        $this->get('/')
            ->assertRedirect('/email/verify');
    }
}
