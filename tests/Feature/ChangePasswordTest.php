<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ChangePasswordTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guests_may_not_change_passwords()
    {
        $this->post('settings/change_password')
            ->assertRedirect(route('login'));
    }

    /** @test */
    public function it_can_change_the_password_of_the_authenticated_user()
    {
        $user = $this->signIn();
        $user->password = Hash::make('old_password');

        $this->post('settings/change_password', $this->getData());

        $this->assertTrue(Hash::check('new_password', $user->password));
    }

    /** @test */
    public function it_validates_that_the_old_password_equals_the_password_stored_in_the_database()
    {
        $user = $this->signIn();
        $user->password = Hash::make('old_password');

        $this->post('settings/change_password', $this->getData(['old_password' => 'not_the_correct_old_password']))
            ->assertSessionHasErrors('old_password');
    }

    /** @test */
    public function it_validates_the_length_of_the_new_password()
    {
        $user = $this->signIn();

        $this->post('settings/change_password', $this->getData(['password' => 'shortps', 'password_confirmation' => 'shortps']))
            ->assertSessionHasErrors('password');
    }

    /** @test */
    public function it_validates_old_password_is_required()
    {
        $user = $this->signIn();

        $this->post('settings/change_password', $this->getData(['old_password' => null]))
            ->assertSessionHasErrors('old_password');
    }

    /** @test */
    public function it_validates_new_password_is_required()
    {
        $user = $this->signIn();

        $this->post('settings/change_password', $this->getData(['password' => '']))
            ->assertSessionHasErrors('password');
    }

    /** @test */
    public function it_validates_that_password_and_password_confirmation_are_equal()
    {
        $user = $this->signIn();

        $this->post('settings/change_password', $this->getData(['password_confirmation' => 'not_equal']))
            ->assertSessionHasErrors('password');
    }

    protected function getData($overrides = [])
    {
        $data = [
            'old_password' => 'old_password',
            'password' => 'new_password',
            'password_confirmation' => 'new_password'
        ];

        return array_merge($data, $overrides);
    }
}
