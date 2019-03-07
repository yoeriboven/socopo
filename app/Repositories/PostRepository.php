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
    public function forUser($user = null)
    {
        // Use the given user or the authenticated user
        $user = $user ?? auth()->user();

        // Get the IDs of the profiles the user follows
        $profiles = $user->profiles()->get()->keyBy('id');

        // Get all the posts which belong to the profiles
        $posts = Post::whereIn('profile_id', $profiles->modelKeys())->latest()->get();

        // Add the profiles to the posts
        $posts->transform(function ($post) use ($profiles) {
            return $post->setRelation('profile', $profiles->get($post->profile_id));
        });

        return $posts;
    }
}
