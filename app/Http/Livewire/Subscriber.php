<?php

namespace App\Http\Livewire;

use App\UserDetails;
use Livewire\Component;
use App\Plans\Facades\Plans;
use Illuminate\Validation\Rule;
use App\Actions\UpdateUserDetails;

class Subscriber extends Component
{
    public $plan_id;
    public $userDetails;

    public function mount()
    {
        $this->plan_id = auth()->user()->plan()->id;
        $this->userDetails = auth()->user()->details()
            ->select('name', 'address', 'postal', 'city', 'country')
            ->first()
            ->toArray();
    }

    public function submit(UpdateUserDetails $updater)
    {
        $this->validate([
            'plan_id' => ['required', Rule::in(Plans::pluck('id'))],
        ]);

        $updater->update(auth()->user(), $this->userDetails);

        $plan = Plans::withId($this->plan_id);

        if (auth()->user()->subscribed()) {
            if (auth()->user()->subscribedToPlan($plan->paddle_id)) {
                $this->addError('plan_id', "You're already subscribed to this plan.");
                return;
            } else {
                auth()->user()->subscription()->swap($plan->paddle_id);

                session()->flash('success', "You have upgraded to the $plan->name plan. Awesome!");

                $this->redirect(route('settings'));
            }
        }

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
