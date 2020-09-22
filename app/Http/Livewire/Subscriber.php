<?php

namespace App\Http\Livewire;

use Plans;
use App\UserDetails;
use Livewire\Component;
use App\Actions\UpdateUserDetails;

class Subscriber extends Component
{
    public $plan_id;
    public $userDetails;

    public function mount()
    {
        $this->userDetails = auth()->user()->details()
            ->select('name', 'address', 'postal', 'city', 'country')
            ->first()
            ->toArray();
    }

    public function subscribe(UpdateUserDetails $updater)
    {
        $updater->update(auth()->user(), $this->userDetails);

        $plan = Plans::withId($this->plan_id);

        $payLink = auth()->user()->newSubscription('default', $plan->paddle_id)
            ->returnTo(route('subscription.waiting-for-confirmation'))
            ->create();

        $this->emit('readyForPaddle', $payLink);
    }

    public function render()
    {
        return view('livewire.subscriber');
    }
}
