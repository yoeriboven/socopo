<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;

class ChangePasswordController extends Controller
{
    public function change(Request $request)
    {
        request()->validate([
            'old_password' => 'required',
            'password' => 'required|confirmed|min:6'
        ]);

        $user = auth()->user();

        // Checks if the old_password (from the form) equals the current password of the user
        if (! Hash::check($request->old_password, $user->password)) {
            abort(400);
        }

        $user->password = Hash::make($request->password);
        $user->save();
    }
}
