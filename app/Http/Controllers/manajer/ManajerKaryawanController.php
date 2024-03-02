<?php

namespace App\Http\Controllers\manajer;

use App\Http\Controllers\Controller;
use App\Models\Karyawan;
use App\Models\Pairing;
use Illuminate\Http\Request;

class ManajerKaryawanController extends Controller
{
    public function index()
    {
        $karyawan = Karyawan::all();
        return view('manajer.karyawan',['karyawan' => $karyawan]);
    }

    public function pengajuanCuti()
    {
        $dataPairing = Pairing::getDaftarKaryawan(1)->get();
        return view('manajer.pengajuan-cuti', [
            'dataPairing' => $dataPairing
        ]);
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
                'nama_karyawan'=> $validateData['nama_karyawan'],
                'jabatan'=> $validateData['jabatan'],
                'tmt_bekerja'=> $validateData['tmt_bekerja'],
                'tgl_diangkat_staf'=> $validateData['tgl_diangkat_staf'],
                'id_posisi'=> $validateData['id_posisi'],
            ]);
            session()->flash('pesan', "Penambahan data {$validateData['nama_karyawan']} berhasil");
            return redirect()->route('manajer.karyawan.index');
    }
  
}
