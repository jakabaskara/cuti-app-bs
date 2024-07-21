<?php

namespace App\Http\Controllers\admin;

use App\Models\Karyawan;
use App\Models\Posisi;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminKaryawanController extends Controller
{
    public function index()
    {
        $positions = Posisi::with('unitKerja')->get();

        $idUser = Auth::user()->id;
        $user = User::find($idUser);
        $jabatan = $user->karyawan->posisi->jabatan;

        $namaUser = $user->karyawan->nama;

        $idPosisi = $user->karyawan->posisi->id;
        $karyawan = Karyawan::all();
        return view('admin.karyawan', [
            'karyawan' => $karyawan,
            'jabatan' => $jabatan,
            'nama' => $namaUser,
            'positions' => $positions,
        ]);
    }

    public function tambahKaryawan(Request $request)
    {
        $validateData = $request->validate(
            [
                'nik' => 'required',
                'nama_karyawan' => 'required',
                'jabatan' => 'required',
                'tmt_bekerja' => 'required',
                'tgl_diangkat_staf' => 'nullable',
                'id_posisi' => 'required|exists:posisi,id',
            ]
        );


        Karyawan::create([
            'nik' => $validateData['nik'],
            'nama' => $validateData['nama_karyawan'],
            'jabatan' => $validateData['jabatan'],
            'tmt_bekerja' => $validateData['tmt_bekerja'],
            'tgl_diangkat_staf' => $validateData['tgl_diangkat_staf'],
            'id_posisi' => $validateData['id_posisi'],
        ]);
        session()->flash('pesan', "Penambahan data {$validateData['nama_karyawan']} berhasil");
        return redirect()->route('admin.karyawan.index');
    }
}
