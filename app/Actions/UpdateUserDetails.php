<?php

namespace App\Actions;

use App\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class UpdateUserDetails
{
    public function update(User $user, array $input)
    {
        $attributes = Validator::make($input, [
            'name' => 'required|min:3|max:50',
            'address' => 'required|min:5|max:100',
            'postal' => 'required|max:15',
            'city' => 'required|max:50',
            'country' => ['required', Rule::in(array_keys(config('countries')))],
        ])->validate();

        return $user->details()->update($attributes);
    }
}
