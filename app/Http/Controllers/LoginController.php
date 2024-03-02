<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function index()
    {
        return view('login.index');
    }

    public function ceklogin(Request $request)
    {
        $request->validate([
            'email' => ['required'],
            'password' => ['required'],
        ]);
        $user = \App\Models\User::where('email', $request->email)->first();
        if (!Hash::check($request->password, $user?->password)) {
            throw ValidationException::withMessages([
                'password' => 'Email atau Password Salah!',
            ]);
        }
        Auth::login($user, $request->remember);

        return to_route('admin.index');
    }
}
