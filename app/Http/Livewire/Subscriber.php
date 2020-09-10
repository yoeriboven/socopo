<?php

namespace App\Http\Livewire;

use Plans;
use Livewire\Component;

class Subscriber extends Component
{
    public $plan_id;

    public function subscribe()
    {
        $plan = Plans::withId($this->plan_id);

        $payLink = auth()->user()->newSubscription('default', $plan->paddle_id)
            ->returnTo(route('home'))
            ->create();

        $this->emit('readyForPaddle', $payLink);
    }

    public function render()
    {
        return view('livewire.subscriber');
    }
}
