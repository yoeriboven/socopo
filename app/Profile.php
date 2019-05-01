<?php

namespace App;

use App\Notifications\NewPostAdded;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
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
        'avatar' => 'string'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'pivot',
        'created_at',
        'updated_at'
    ];

    /**
     * Attaches the current profile to the given user
     *
     * @param  User $user
     */
    public function attachUser(User $user = null)
    {
        $user = $user ?? auth()->user();

        $user->profiles()->syncWithoutDetaching([$this->id]);

        return $this;
    }

    /**
     * Detaches the current profile to the given user
     *
     * @param  User $user
     */
    public function detachUser(User $user = null)
    {
        $user = $user ?? auth()->user();

        $user->profiles()->detach($this);

        return $this;
    }

    /**
     * Avatar gets updated if it's changed
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
     * A user belongs to many followers (Users)
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongsToMany
     */
    public function followers()
    {
        return $this->belongsToMany('App\User');
    }

    /**
     * Notifies followers when a new post is stored
     *
     * @param  Post $post
     */
    public function notifyFollowers(Post $post)
    {
        $this->followers->filter->hasSlackSetup()->each->notify(new NewPostAdded($post));
    }
}
