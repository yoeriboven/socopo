<?php

namespace App\Http\Controllers;

use App\UserDetails;
use Illuminate\Http\Request;

class UserDetailsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        UserDetails::create([
            'user_id' => auth()->id(),
            'vat_id' => request('vat_id'),
            'name' => request('name'),
            'address' => request('address'),
            'postal' => request('postal'),
            'city' => request('city'),
            'country' => request('country')
        ]);

        return back();
    }
}
