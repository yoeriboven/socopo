<?php

namespace App\Actions;

use App\User;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class UpdateUserDetails
{
    public function update(User $user, array $input)
    {
        Validator::make($input, [
            'name' => 'required|min:3|max:50',
            'vat_id' => 'nullable|vat_number',
            'address' => 'required|min:5|max:100',
            'postal' => 'required|max:15',
            'city' => 'required|max:50',
            'country' => ['required', Rule::in(array_keys(config('countries')))],
        ])->validate();

        return $user->details()->update([
            'name' => $input['name'],
            'address' => $input['address'],
            'postal' => $input['postal'],
            'city' => $input['city'],
            'country' => $input['country'],
        ]);
    }
}
