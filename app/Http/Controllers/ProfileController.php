<?php

namespace App\Http\Controllers;

use App\Profile;
use Illuminate\Http\Request;
use App\Http\Requests\ProfileRequest;
use App\Repositories\ProfileRepository;
use App\Libraries\Instagram\InstagramDownloader;

class ProfileController extends Controller
{
    /**
     * Provides access to the profiles
     *
     * @var ProfileRepository
     */
    protected $profiles;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(ProfileRepository $profiles)
    {
        $this->profiles = $profiles;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->profiles->forUser(auth()->user());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProfileRequest $request, InstagramDownloader $instagram)
    {
        if ($this->profiles->attached($request->username)) {
            return response(['message' => 'Profile has already been added.'], 202);
        }

        try {
            $profile = Profile::firstOrNew(['username' => $request->username]);

            if (!$profile->avatar) {
                $profile->avatar = $instagram->getAvatar($request->username);
            }

            $profile->save();
            $profile->attachUser();
        } catch (\Exception $e) {
            // Sentry include username
            return response(['message' => 'Profile not found for this username'], 500);
        }

        return response(['message' => 'Profile added', 'profile' => $profile->toArray()], 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Profile  $profile
     * @return \Illuminate\Http\Response
     */
    public function destroy(Profile $profile)
    {
        // Checks if the $profile is attached to the signed in user
        if (auth()->user()->canNot('delete', $profile)) {
            return response([], 401);
        }

        $profile->detachUser();
        return response([], 204);
    }
}
