<?php

namespace App\Http\Controllers\asisten;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class CutiBersamaController extends Controller
{
    public function index()
    {
        $idUser = Auth::user()->id;
        $user = User::find($idUser);
        $karyawan = $user->karyawan;

        $namaUser = $user->karyawan->nama;
        $jabatan = $user->karyawan->posisi->jabatan;

        return view('asisten.cuti-bersama', [
            'nama' => $namaUser,
            'jabatan' => $jabatan,
        ]);
    }
}
