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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(ProfileRepository $profiles)
    {
        return $profiles->forUser(auth()->user());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProfileRequest $request, InstagramDownloader $instagram)
    {
        try {
            $profile = Profile::firstOrCreate(['username' => request('username')]);

            if (!$profile->avatar) {
                $profile->avatar = $instagram->getAvatar(request('username'));
            }

            $profile->attachUser()->save();
        } catch (\Exception $e) {
            return response(['Profile not found for this username'], 500);
        }

        return response(['message' => 'Profile added', 'profile' => $profile->toArray()], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Profile  $profile
     * @return \Illuminate\Http\Response
     */
    public function show(Profile $profile)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Profile  $profile
     * @return \Illuminate\Http\Response
     */
    public function edit(Profile $profile)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Profile  $profile
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Profile $profile)
    {
        //
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
