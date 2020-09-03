<?php

namespace App;

use Laravel\Paddle\Billable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements MustVerifyEmail
{
    use Billable, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Boot the model
     */
    public static function boot()
    {
        parent::boot();

        /*
           Objects should be created in the database
           when a new user is created
         */
        static::created(function (User $user) {
            $user->settings()->save(new Settings());
            $user->details()->save(new UserDetails());
        });
    }

    /**
     * A user belongs to many profiles
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongsToMany
     */
    public function profiles()
    {
        return $this->belongsToMany('App\Profile')->withTimestamps();
    }

    /**
     * Fetches all profiles the user follows in the correct order
     *
     * @return \Illuminate\Database\Eloquent\Collection;
     */
    public function getProfilesAttribute()
    {
        return $this->profiles()->orderBy('pivot_created_at', 'desc')->get();
    }

    /**
     * Returns whether a profile is attached to the user already
     *
     * @param  String $username
     * @return boolean
     */
    public function attached($username)
    {
        return !! $this->profiles()->where('username', $username)->exists();
    }

    /**
     * Returns whether a Profile was attached to this user after a Post was published on IG
     *
     * Could be used to avoid notifying users of posts published in the past
     *
     * @param  Post   $post
     * @return boolean
     */
    public function attachedAfterPostPublished(Post $post)
    {
        return !! $this->pivot->created_at->gt($post->posted_at);
    }

    /**
     * A user has details (name, address, country, etc.)
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function details()
    {
        return $this->hasOne('App\UserDetails');
    }

    /**
     * A user has settings
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function settings()
    {
        return $this->hasOne('App\Settings');
    }

    /**
     * Gets the Plan object with the stripe_plan ID stored in the database
     *
     * @return \App\Plans\Plan
     */
    public function plan()
    {
        if (! $this->subscribed()) {
            return new \App\Plans\FreePlan();
        }

        return (new \App\Plans\PlanCollection())->withPaddleId($this->subscription()->paddle_plan);
    }

    /**
     * Sets the slack_url on the settings object
     *
     * @param string $url
     */
    public function setSlackUrl($url)
    {
        $this->settings->update(['slack_url' => $url]);
    }

    /**
     * Returns whether slack_url is set on settings
     *
     * @return boolean
     */
    public function hasSlackSetup()
    {
        return !! $this->settings->slack_url;
    }

    /**
     * Route notifications for the Slack channel.
     *
     * @param  \Illuminate\Notifications\Notification  $notification
     * @return string
     */
    public function routeNotificationForSlack($notification)
    {
        return $this->settings->slack_url;
    }
}
