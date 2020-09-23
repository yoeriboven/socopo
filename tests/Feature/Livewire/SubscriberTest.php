<?php

namespace Tests\Feature\Livewire;

use Tests\TestCase;
use Livewire\Livewire;
use App\Plans\Facades\Plans;
use App\Plans\PlanCollection;
use App\Http\Livewire\Subscriber;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SubscriberTest extends TestCase
{
    /** @test */
    public function it_requires_a_plan_id()
    {
        $this->signIn();

        Livewire::test(Subscriber::class)
            ->set('plan_id', '')
            ->call('subscribe')
            ->assertHasErrors(['plan_id' => 'required']);
    }

    /** @test */
    public function it_requires_a_valid_plan_id()
    {
        $this->signIn();

        Livewire::test(Subscriber::class)
            ->set('plan_id', 'invalid_plan_id')
            ->call('subscribe')
            ->assertHasErrors('plan_id');
    }
}
