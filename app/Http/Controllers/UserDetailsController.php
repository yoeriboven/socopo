<?php

namespace App\Http\Controllers;

use App\Actions\UpdateUserDetails;
use Illuminate\Http\Request;

class UserDetailsController extends Controller
{
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
