<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

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
     * A user has details (name, address, country, etc.)
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function details()
    {
        return $this->hasOne('App\UserDetails');
    }

    /**
     * A user has settings (slack_url)
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function settings()
    {
        return $this->hasOne('App\Settings');
    }
}
