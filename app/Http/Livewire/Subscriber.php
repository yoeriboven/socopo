<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Plans\Facades\Plans;
use Illuminate\Validation\Rule;
use App\Actions\UpdateUserDetails;
use Illuminate\Validation\ValidationException;

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

        $this->subscribe();
    }

    protected function subscribe()
    {
        $plan = Plans::withId($this->plan_id);

        if (auth()->user()->subscribed()) {
            $this->swapSubscription($plan);
        }

        $payLink = auth()->user()->newSubscription('default', $plan->paddle_id)
            ->returnTo(route('subscription.waiting-for-confirmation'))
            ->create();

        $this->emit('readyForPaddle', $payLink);
    }

    protected function swapSubscription($plan)
    {
        if (auth()->user()->subscribedToPlan($plan->paddle_id)) {
            throw ValidationException::withMessages([
                'plan_id' => "You're already subscribed to this plan.",
            ]);
        }

        auth()->user()->subscription()->swap($plan->paddle_id);

        session()->flash('success', "You're subscription has changed to the $plan->name plan. Awesome!");

        $this->redirect(route('settings'));
    }

    public function render()
    {
        return view('livewire.subscriber');
    }
}
