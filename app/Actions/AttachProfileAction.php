<?php

namespace App\Actions;

use App\User;
use App\Profile;
use App\Exceptions\DuplicateAttachmentException;
use Facades\App\Services\Instagram\InstagramDownloader;

class AttachProfileAction
{
    public function attach(User $user, string $username)
    {
        if ($user->attached($username)) {
            throw new DuplicateAttachmentException();
        }

        $profile = Profile::firstOrNew(['username' => $username]);

        if (!$profile->avatar) {
            $profile->avatar = InstagramDownloader::getAvatar($username);
        }

        $profile->save();
        $profile->attachUser();

        return $profile;
    }
}
