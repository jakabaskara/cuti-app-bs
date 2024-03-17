<?php

namespace App\Http\Controllers;

use App\Models\Keanggotaan;
use App\Models\Pairing;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminPairingController extends Controller
{

    public $nama;
    public $jabatan;

    public function index()
    {

        $idUser = Auth::user()->id;
        $user = User::find($idUser);
        $karyawan = $user->karyawan;
        $this->nama = $karyawan->nama;
        $this->jabatan = $karyawan->jabatan;


        $pairing = Pairing::get();

        return view('admin.pairing', [
            'jabatan' => $this->jabatan,
            'nama' => $this->nama,
            'pairings' => $pairing,
        ]);
    }

    public function keanggotaan()
    {
        $idUser = Auth::user()->id;
        $user = User::find($idUser);
        $karyawan = $user->karyawan;
        $this->nama = $karyawan->nama;
        $this->jabatan = $karyawan->jabatan;

        $keanggotaan = Keanggotaan::get();

        return view('admin.keanggotaan', [
            'jabatan' => $this->jabatan,
            'nama' => $this->nama,
            'keanggotaans' => $keanggotaan,
        ]);
    }
}
