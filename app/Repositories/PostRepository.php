<?php

namespace App\Repositories;

use App\Post;
use Illuminate\Support\Carbon;

class PostRepository
{
    /**
     * Fetches posts from accounts followed by the user
     *
     * @param  User $user
     * @return \Illuminate\Database\Eloquent\Collection;
     */
    public function forUser($user)
    {
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

    /**
     * Gets the latest post for the given profiles
     *
     * @param  \Illuminate\Database\Eloquent\Collection $profiles
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function latestForProfiles($profiles)
    {
        // Creates array with just the id from every profile
        $ids = $profiles->pluck('id');

        // Gets every unique profile_id with the latest date
        $sub = Post::select('profile_id', \DB::raw('MAX(posted_at) AS max_date'))->whereIn('profile_id', $ids)->groupBy('profile_id');

        // Grabs one post for every profile_id in $sub that's equal to the max_date
        $posts = Post::joinSub($sub->toSql(), 'max_table', function ($join) {
            $join->on('max_table.profile_id', '=', 'posts.profile_id');
            $join->on('max_table.max_date', '=', 'posts.posted_at');
        })
        ->addBinding($sub->getBindings(), 'join')
        ->get()
        ->keyBy('profile_id');

        return $posts;
    }

    public function create($post, $profile)
    {
        Post::create([
            'profile_id' => $profile->id,
            'ig_post_id' => $post->id,
            'caption' => $post->caption,
            'type' => $post->typeName,
            'image_url' => $post->displaySrc,
            'post_url' => $post->link,
            'posted_at' => Carbon::now()
        ]);
    }
}
