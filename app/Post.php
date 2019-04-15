<?php

namespace App;

use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Model;
use App\Libraries\Instagram\Hydrator\Component\Media;

class Post extends Model
{
    /**
     * The attributes that should be cast to a Carbon instance
     *
     * @var array
     */
    protected $dates = [
        'posted_at'
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
        'ig_post_id' => 'integer'
    ];

    /**
     * The profile this post belongs to
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function profile()
    {
        return $this->belongsTo('App\Profile');
    }

    /**
     * Stores new posts with Instagram data
     *
     * @param  Libraries\Instagram\Hydrator\Component\Media $post
     * @param  App\Profile $profile
     * @return App\Post
     */
    public static function storeFromInstagram(Media $post, Profile $profile)
    {
        return static::create([
            'profile_id' => $profile->id,
            'ig_post_id' => $post->id,
            'caption' => $post->caption,
            'type' => $post->typeName,
            'image_url' => $post->displaySrc,
            'post_url' => $post->link,
            'posted_at' => Carbon::instance($post->date)
        ]);
    }
}
