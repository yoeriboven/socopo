<?php

namespace App;

use App\Notifications\NewPostAdded;
use App\Services\Instagram\Hydrator\Component\Feed;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    /**
     * The IG Feed connected to this profile.
     *
     * @var \App\Services\Instagram\Hydrator\Component\Feed
     */
    public $feed;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['username', 'avatar'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'avatar' => 'string',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'pivot',
        'created_at',
        'updated_at',
    ];

    /**
     * Creates a profile using data from the Instagram Feed.
     *
     * @param  String $username
     * @param  \App\Services\Instagram\Hydrator\Component\Feed   $feed     [description]
     * @return Profile
     */
    public static function createFromIG($username, Feed $feed)
    {
        $profile = static::firstOrNew(['username' => $username]);

        if (!$profile->avatar) {
            $profile->avatar = $feed->profilePicture;
        }

        $profile->save();

        return $profile;
    }

    /**
     * Attaches the current profile to the given user.
     *
     * @param  User $user
     */
    public function attachUser(User $user = null)
    {
        $user = $user ?? auth()->user();

        $this->followers()->syncWithoutDetaching([$user->id]);

        return $this;
    }

    /**
     * Detaches the current profile from the given user.
     *
     * @param  User $user
     */
    public function detachUser(User $user = null)
    {
        $user = $user ?? auth()->user();

        $this->followers()->detach($user);

        return $this;
    }

    /**
     * Avatar gets updated if it's changed.
     *
     * @param  String $link
     */
    public function updateAvatar($link)
    {
        if ($link != $this->avatar) {
            $this->update(['avatar' => $link]);
        }
    }

    /**
     * A profile belongs to many followers (Users).
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongsToMany
     */
    public function followers()
    {
        return $this->belongsToMany('App\User')->withTimestamps();
    }

    /**
     * Notifies followers when a new post is stored.
     *
     * @param  Post $post
     */
    public function notifyFollowers(Post $post)
    {
        $this->followers()->chunk(100, function($followers) use ($post) {
            $followers->filter(function ($follower) {
                return $follower->hasSlackSetup();
            })->reject(function ($follower) use ($post) {
                return $follower->attachedAfterPostPublished($post);
            })->each(function ($follower) use ($post) {
                $follower->notify(new NewPostAdded($post));
            });
        });
    }

    /**
     * Returns the url to the profile on Instagram.
     *
     * @return string
     */
    public function getUrlAttribute()
    {
        return 'https://www.instagram.com/'.$this->username;
    }
}
