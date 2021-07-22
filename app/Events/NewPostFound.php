<?php

namespace App\Events;

use App\Profile;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewPostFound
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * The Profile the new post belongs to.
     *
     * @var \App\Profile
     */
    public $profile;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Profile $profile)
    {
        $this->profile = $profile;
    }
}
