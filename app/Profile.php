<?php

namespace App;

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
     * Attaches the current profile to the given user
     *
     * @param  User $user
     */
    public function attachToUser($user = null)
    {
        $user = $user ?? auth()->user();

        $user->profiles()->attach($this);
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
}
