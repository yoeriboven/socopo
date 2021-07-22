<?php

namespace Tests\Feature\Livewire;

use Tests\TestCase;
use Livewire\Livewire;
use App\Http\Livewire\WaitingForConfirmation;
use Illuminate\Foundation\Testing\RefreshDatabase;

class WaitingForConfirmationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_redirects_if_the_user_is_subscribed()
    {
        $this->signIn();

        Livewire::test(WaitingForConfirmation::class)
            ->assertSee('This might take a minute.');

        $this->subscribe();

        Livewire::test(WaitingForConfirmation::class)
            ->assertRedirect('/posts');
    }
}
