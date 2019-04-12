<?php

namespace App\Services;

use App\Profile;
use Illuminate\Http\Request;
use App\Repositories\ProfileRepository;
use App\Exceptions\DuplicateAttachmentException;
use App\Libraries\Instagram\InstagramDownloader;

class ProfileService
{
    /**
     * Provides access to the profiles
     *
     * @var ProfileRepository
     */
    protected $profiles;

    /**
     * Interacts with the Instagram API
     *
     * @var InstagramDownloader
     */
    protected $instagram;

    /**
     * Creates a new instance of this class
     */
    public function __construct(ProfileRepository $profiles, InstagramDownloader $instagram)
    {
        $this->profiles = $profiles;
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
        if ($this->profiles->attached($request->username)) {
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
