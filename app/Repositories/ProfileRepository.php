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
}
