<?php

namespace App\Services;

use App\Profile;
use Illuminate\Http\Request;
use App\Exceptions\DuplicateAttachmentException;
use App\Libraries\Instagram\InstagramDownloader;

class ProfileService
{
    /**
     * Interacts with the Instagram API
     *
     * @var InstagramDownloader
     */
    protected $instagram;

    /**
     * Creates a new instance of this class
     */
    public function __construct(InstagramDownloader $instagram)
    {
        $this->instagram = $instagram;
    }

    /**
     * Creates a new instance of Profile
     *
     * @param  Request $request
     * @return Profile
     */
    public function store(Request $request)
    {
        if ($request->user()->attached($request->username)) {
            throw new DuplicateAttachmentException();
        }

        $profile = Profile::firstOrNew(['username' => $request->username]);

        if (!$profile->avatar) {
            $profile->avatar = $this->instagram->getAvatar($request->username);
        }

        $profile->save();
        $profile->attachUser();

        return $profile;
    }
}
