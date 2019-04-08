<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ChangePasswordTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_change_the_password_of_the_authenticated_user()
    {
        $this->withoutExceptionHandling();

        $user = $this->signIn();
        $user->password = Hash::make('old_password');

        $data = [
            'old_password' => 'old_password',
            'password' => 'new_password',
            'password_confirmation' => 'new_password'
        ];

        $this->post('settings/change_password', $data);

        $this->assertTrue(Hash::check('new_password', $user->password));
    }
}
