<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Actions\UpdateUserDetails;
use App\Services\UserDetailsService;

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
     * @param  \Illuminate\Http\Request         $request
     * @param  App\Actions\UpdateUserDetails    $updater
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, UpdateUserDetails $updater)
    {
        $updater->update($request->user(), $request->all());

        return back()->with('user_details.success', 'Details changed succesfully.');
    }
}
