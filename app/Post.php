<?php

namespace App;

use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    /**
     * The attributes that should be cast to a Carbon instance.
     *
     * @var array
     */
    protected $dates = [
        'posted_at',
    ];

    /**
     * Don't auto-apply mass assignment protection.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'ig_post_id' => 'integer',
    ];

    /**
     * The profile this post belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function profile()
    {
        return $this->belongsTo('App\Profile');
    }

    /**
     * Returns the url to the image.
     *
     * @return string
     */
    public function getImageUrlAttribute()
    {
        return $this->post_url.'media/?size=l';
    }

    /**
     * Stores new posts with Instagram data.
     *
     * @param  Services\Instagram\Hydrator\Component\Media $post
     * @param  App\Profile $profile
     * @return App\Post
     */
    public static function storeFromInstagram(Profile $profile)
    {
        $post = $profile->feed->getLatestMedia();

        return static::create([
            'profile_id' => $profile->id,
            'ig_post_id' => $post->id,
            'caption' => $post->caption,
            'type' => $post->typeName,
            'post_url' => $post->link,
            'posted_at' => Carbon::instance($post->date),
        ]);
    }

    /**
     * Gets the latest post for the given profiles.
     *
     * @param  App\Profile $profile
     * @return App\Post
     */
    public static function latestForProfile($profile)
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
