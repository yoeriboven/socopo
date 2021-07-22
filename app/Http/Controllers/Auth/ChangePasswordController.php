<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class ChangePasswordController extends Controller
{
    public function store(Request $request)
    {
        request()->validate([
            'old_password' => 'required',
            'password' => 'required|confirmed|min:8',
        ]);

        $user = auth()->user();

        // Checks if the old_password (from the form) equals the current password of the user
        if (! Hash::check($request->old_password, $user->password)) {
            return back()->withErrors(['old_password' => 'Current password is incorrect.']);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        return back()->with('change_password.success', 'Slack authorization succeeded.');
    }
}
