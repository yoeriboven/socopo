<?php

namespace App\Http\Controllers;

class SettingsController extends Controller
{
    /**
     * Display the settings page
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('settings');
    }
}
