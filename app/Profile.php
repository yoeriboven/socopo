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
     * Finds or creates a profile based on the username and then attaches it to the user
     *
     * @param  String $username
     * @param  String $avatar
     * @return User
     */
    public static function createAndAttach($username, $avatar)
    {
        return static::firstOrCreate([
            'username' => $username
        ], [
            'avatar' => $avatar
        ])->attachUser();
    }

    /**
     * Attaches the current profile to the given user
     *
     * @param  User $user
     */
    public function attachUser($user = null)
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
    public function detachUser($user = null)
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
}
