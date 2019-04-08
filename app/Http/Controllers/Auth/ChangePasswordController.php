<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;

class ChangePasswordController extends Controller
{
    public function change(Request $request)
    {
        $request->user->password = Hash::make($request->password);
        $request->user->save();
    }
}
