<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function index()
    {
        return view('login.index');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        $userId = User::where('username', $request->username)->get();
        dd($userId);

        $credential = $request->only([
            'username',
            'password',
        ]);

        if (Auth::attempt($credential)) {
        }
    }
}
