<?php

namespace App\Http\Controllers;

use App\Services\UserDetailsService;
use App\Http\Requests\UserDetailsRequest;

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
    public function store(UserDetailsRequest $request, UserDetailsService $service)
    {
        $service->store($request);

        return back()->with('user_details.success', 'Details changed succesfully.');
    }
}
