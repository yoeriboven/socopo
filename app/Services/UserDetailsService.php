<?php

namespace App\Services;

use App\UserDetails;
use Illuminate\Http\Request;

class UserDetailsService
{
    public function store(Request $request)
    {
        return UserDetails::updateOrCreate(['user_id' => $request->user()->id], [
            'name' => $request->name,
            'vat_id' => $request->vat_id,
            'address' => $request->address,
            'postal' => $request->postal,
            'city' => $request->city,
            'country' => $request->country
        ]);
    }
}
