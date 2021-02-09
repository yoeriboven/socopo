<?php

namespace App\Repositories;

use App\Post;

class PostRepository
{
    /**
     * Fetches posts from accounts followed by the user.
     *
     * @param  User $user
     * @return \Illuminate\Database\Eloquent\Collection;
     */
    public function forUser($user, $take = 15)
    {
        // Get the IDs of the profiles the user follows
        $profiles = $user->profiles()->get()->keyBy('id');

        // Get all the posts which belong to the profiles
        $posts = Post::whereIn('profile_id', $profiles->modelKeys())->latest('posted_at')->paginate($take);

        // Add the profiles to the posts
        $posts->transform(function ($post) use ($profiles) {
            return $post->setRelation('profile', $profiles->get($post->profile_id));
        });

        return $posts;
    }

    /**
     * Gets the latest post for the given profiles.
     *
     * @param  App\Profile $profile
     * @return App\Post
     */
    public function latestForProfile($profile)
    {
        // Gets every unique profile_id with the latest date
        $sub = Post::select('profile_id', \DB::raw('MAX(posted_at) AS max_date'))->where('profile_id', $profile->id)->groupBy('profile_id');

        // Grabs one post for every profile_id in $sub that's equal to the max_date
        $post = Post::joinSub($sub->toSql(), 'max_table', function ($join) {
            $join->on('max_table.profile_id', '=', 'posts.profile_id');
            $join->on('max_table.max_date', '=', 'posts.posted_at');
        })
            ->addBinding($sub->getBindings(), 'join')
            ->first();

        return $post;
    }
}
