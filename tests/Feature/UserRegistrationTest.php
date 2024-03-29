<?php

namespace Tests\Feature;

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
}
