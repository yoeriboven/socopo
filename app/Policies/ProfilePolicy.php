<?php

namespace App\Policies;

use App\Profile;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProfilePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can create profiles.
     *
     * Creating a new profile is not allowed if it means you will exceed
     * the maximum amount of profiles allowed on your plan
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->profiles()->count() < $user->plan()->maxProfiles;
    }

    /**
     * Determine whether the user can delete the profile.
     *
     * @param  \App\User  $user
     * @param  \App\Profile  $profile
     * @return mixed
     */
    public function delete(User $user, Profile $profile)
    {
        return $user->profiles()->where('profile_id', $profile->id)->exists();
    }
}
