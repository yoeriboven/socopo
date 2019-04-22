<?php

namespace App;

use App\Billing\Billable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Mpociot\VatCalculator\Facades\VatCalculator;
use Mpociot\VatCalculator\Traits\BillableWithinTheEU;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable, Billable, BillableWithinTheEU {
        BillableWithinTheEU::taxPercentage insteadof Billable;
    }

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

    public static function boot()
    {
        parent::boot();

        /*
            A settings row on the settings table should be created
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
     * @return Boolean
     */
    public function attached($username)
    {
        return !! $this->profiles()->where('username', $username)->exists();
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
     * @return bool
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

    /**
     * Checks whether the user is a business based on whether the vat_id is valid
     *
     * @return bool
     */
    public function isBusiness()
    {
        return VatCalculator::isValidVATNumber($this->details->vat_id);
    }
}
