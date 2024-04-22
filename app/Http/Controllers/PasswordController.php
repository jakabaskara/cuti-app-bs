<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;

class PasswordController extends Controller
{
    public function index()
    {
        $idUser = Auth::user()->id;
        $user = User::find($idUser);
        $karyawan = $user->karyawan;

        $namaUser = $user->karyawan->nama;
        $jabatan = $user->karyawan->posisi->jabatan;
        $sidebar = $karyawan->posisi->role->nama_role;
        if ($sidebar == 'brm') {
            $sidebar = 'sevp';
        }
        return view('change-password', [
            'jabatan' => $jabatan,
            'nama' => $namaUser,
            'sidebar' => $sidebar,
        ]);
    }

    public function showChangePasswordForm()
    {
        return view('auth.password.change');
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => [
                'required',
                'confirmed',
                Password::min(8)->mixedCase()->numbers(), // Requires at least one number
            ],
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Password Salah']);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        return redirect()->back()->with('success', 'Password Berhasil Diubah!');
    }
}
