<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    public function index()
    {
        // if(session_status()){
        //     session_destroy();
        // }
        session_reset();
        // if(session_status)
        return view('login.index');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        $userId = User::where('username', $request->username)->get();

        $credential = $request->only([
            'username',
            'password',
        ]);
        if (Auth::attempt($credential)) {
            $role = $userId->first()->karyawan->posisi->role->nama_role;
            if ($role == 'kerani') {
                return redirect()->route('kerani.index');
            } elseif ($role == 'kabag') {
                return redirect()->route('kabag.index');
            } elseif ($role == 'manajer') {
                return redirect()->route('manajer.index');
            } elseif ($role == 'admin') {
                return redirect()->route('admin.index');
            } elseif ($role == 'asisten') {
                return redirect()->route('asisten.index');
            } elseif ($role == 'gm') {
                return redirect()->route('gm.index');
            } elseif ($role == 'brm') {
                return redirect()->route('sevp.index');
            } else {
                return back()->with('failed', 'Akun Belum Terdaftar');
            }
        } else {
            return back()->with('failed', 'Username atau Password Anda Salah');
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
