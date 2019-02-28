<?php

namespace App\Repositories;

use App\Post;

class PostRepository
{
    /**
     * Fetches posts from accounts followed by the user
     *
     * @param  User $user
     * @return \Illuminate\Database\Eloquent\Collection;
     */
    // public function forUser($user = null)
    // {
    //     // Use the given user or the authenticated user
    //     $user = $user ?? auth()->user();

    //     // Get the IDs of the profiles the user follows
    //     $profiles = $user->profiles->pluck('id');

    //     // Fetch the latest posts, restricted to those by profiles followed by the user
    //     return Post::whereIn('profile_id', $profiles)->latest()->get();
    // }
    //
    public function forUser($user = null)
    {
        // Use the given user or the authenticated user
        $user = $user ?? auth()->user();

        // Get the IDs of the profiles the user follows
        $profiles = $user->profiles->pluck('id');

        // Fetch the latest posts, restricted to those by profiles followed by the user
        return Post::whereIn('profile_id', $profiles)->latest()->get();
    }
}
