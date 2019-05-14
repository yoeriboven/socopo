<?php

namespace App\Http\Controllers;

use Exception;
use App\Profile;
use Illuminate\Http\Request;
use App\Services\ProfileService;
use App\Http\Requests\ProfileRequest;
use App\Exceptions\PrivateProfileException;
use App\Exceptions\DuplicateAttachmentException;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return auth()->user()->profiles;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProfileRequest $request, ProfileService $service)
    {
        // Checks if the user is at their max profiles
        if (auth()->user()->canNot('create', Profile::class)) {
            return response(['message' => 'You have reached your maximum amount of profiles. Upgrade your account.'], 303);
        }

        try {
            $profile = $service->store($request);
        } catch (DuplicateAttachmentException $e) {
            return response(['message' => 'Profile has already been added.'], 202);
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            return response(['message' => 'Profile not found for this username.'], 503);
        } catch (PrivateProfileException $e) {
            return response(['message' => 'This Instagram account is private.'], 503);
        } catch (Exception $e) {
            app('sentry')->captureException($e);

            return response(['message' => 'Something failed on our end. We\'ve been notified and will fix this. You can also try again.'], 500);
        }

        return response(['message' => 'Profile added to your account.', 'profile' => $profile->toArray()], 201);
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
