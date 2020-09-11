<?php

namespace Tests\Feature\Livewire;

use Tests\TestCase;
use Livewire\Livewire;
use Illuminate\Foundation\Testing\WithFaker;
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

    protected function subscribe()
    {
        $this->user->createAsCustomer();
        $this->user->subscriptions()->create([
            'name' => 'default',
            'paddle_id' => 244,
            'paddle_plan' => 627813,
            'paddle_status' => 'active',
            'quantity' => 1,
        ]);
    }
}
