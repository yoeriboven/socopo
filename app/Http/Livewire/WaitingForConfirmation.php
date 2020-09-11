<?php

namespace App\Http\Livewire;

use Livewire\Component;

class WaitingForConfirmation extends Component
{
    public function render()
    {
        if (auth()->user()->subscribed()) {
            session()->flash('success', 'Your account has been upgraded. Welcome!');

            $this->redirect(route('home'));
        }

        return view('livewire.waiting-for-confirmation');
    }
}
