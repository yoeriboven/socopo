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
    protected $fillable = ['username'];

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
}
