<?php

namespace App\Http\Controllers\admin;

use App\Models\Karyawan;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminKaryawanController extends Controller
{   
       public function index()
    {
        $karyawan = Karyawan::all();
        return view('admin.karyawan',['karyawan' => $karyawan]);
    }

    public function tambahKaryawan(Request $request)
    {
        $validateData = $request->validate(
            [
                'nik'=> 'required',
                'nama_karyawan'=> 'required',
                'jabatan'=> 'required',
                'tmt_bekerja'=> 'required',
                'tgl_diangkat_staf'=> 'required',
                'id_posisi'=> 'required',
            ]
            );


            Karyawan::create([
                'nik'=> $validateData['nik'],
                'nama'=> $validateData['nama_karyawan'],
                'jabatan'=> $validateData['jabatan'],
                'tmt_bekerja'=> $validateData['tmt_bekerja'],
                'tgl_diangkat_staf'=> $validateData['tgl_diangkat_staf'],
                'id_posisi'=> $validateData['id_posisi'],
            ]);
            session()->flash('pesan', "Penambahan data {$validateData['nama_karyawan']} berhasil");
            return redirect()->route('manajer.karyawan.index');
    }
}
