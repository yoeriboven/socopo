<?php

namespace App\Actions;

use App\Exceptions\DuplicateAttachmentException;
use App\Profile;
use App\User;
use Facades\App\Services\Instagram\InstagramDownloader;

class AttachProfileAction
{
    public function attach(User $user, string $username)
    {
        if ($user->attached($username)) {
            throw new DuplicateAttachmentException();
        }

        $ig = InstagramDownloader::getFeed($username);

        if (!$profile->avatar) {
            $profile->avatar = InstagramDownloader::getAvatar($username);
        }

        $profile = Profile::createFromIG($username, $ig);

        $profile->attachUser();

        return $profile;
    }
}
