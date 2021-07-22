<?php

namespace App\Actions;

use App\User;
use App\Profile;
use App\Exceptions\PrivateProfileException;
use App\Exceptions\DuplicateAttachmentException;
use Facades\App\Services\Instagram\InstagramDownloader;

class AttachProfileAction
{
    public function attach(User $user, string $username)
    {
        if ($user->attached($username)) {
            throw new DuplicateAttachmentException();
        }

        $ig = InstagramDownloader::getFeed($username);

        if ($ig->private) {
            throw new PrivateProfileException();
        }

        $profile = Profile::createFromIG($username, $ig);

        $profile->attach($user);

        return $profile;
    }
}
