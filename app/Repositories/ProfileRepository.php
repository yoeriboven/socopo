<?php

namespace App\Repositories;

class ProfileRepository
{
    /**
     * Fetches all profiles the given user follows
     *
     * @param  User $user
     * @return \Illuminate\Database\Eloquent\Collection;
     */
    public function forUser($user)
    {
        return $user->profiles()->orderBy('pivot_created_at', 'desc')->get();
    }

    /**
     * Returns whether a profile is attached to the user already
     *
     * @param  String $username
     * @return Boolean
     */
    public function attached($username)
    {
        return !! auth()->user()->profiles()->where('username', $username)->exists();
    }
}
