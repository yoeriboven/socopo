<?php

namespace App\Listeners;

use App\Events\NewPostFound;

class UpdateAvatarURL
{
    /**
     * Handle the event.
     *
     * @param  NewPostFound  $event
     * @return void
     */
    public function handle(NewPostFound $event)
    {
        $event->profile->updateAvatar($event->profile->feed->profilePicture);
    }
}
