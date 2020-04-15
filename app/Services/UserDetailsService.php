<?php

namespace App\Services;

use Illuminate\Http\Request;

class UserDetailsService
{
    public function store(Request $request)
    {
        return $request->user()->details()->update([
            'name' => $request->name,
            'address' => $request->address,
            'postal' => $request->postal,
            'city' => $request->city,
            'country' => $request->country
        ]);
    }
}
