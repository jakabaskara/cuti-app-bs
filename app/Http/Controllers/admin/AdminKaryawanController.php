<?php

namespace App\Http\Controllers\admin;

use App\Models\Karyawan;
use App\Models\Posisi;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


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
        $validate = $request->validate([
            // 'nik' => 'required|unique:karyawan,nik',
            'nik' => 'required|numeric|unique:karyawan,nik',
            'nama' => 'required',
            'jabatan' => 'required',
            'tmt_bekerja' => 'required',
            'tgl_diangkat_staf' => 'nullable',
            'id_posisi' => 'required|exists:posisi,id',
        ]);


            DB::transaction(function () use ($validate) {
                $karyawan = Karyawan::create([
                    'nik' => $validate['nik'],
                    'nama' => $validate['nama'],
                    'jabatan' => $validate['jabatan'],
                    'tmt_bekerja' => $validate['tmt_bekerja'],
                    'tgl_diangkat_staf' => $validate['tgl_diangkat_staf'],
                    'id_posisi' => $validate['id_posisi'],
                ]);
            });

            return redirect()->back()->with('message', 'Karyawan berhasil ditambahkan!');
    }


    public function edit($id)
    {
        $employee = Karyawan::with('posisi.unitKerja')->findOrFail($id);
        return response()->json($employee);
    }



public function updateKaryawan(Request $request)
{
    $request->validate([
        'id' => 'required|exists:karyawan,id',
        // 'nik' => 'required|unique:karyawan,nik,' . $request->id,
        'nik' => 'required|numeric|unique:karyawan,nik,' . $request->id,
        'nama_karyawan' => 'required',
        'jabatan' => 'required',
        'tmt_bekerja' => 'required',
        'tgl_diangkat_staf' => 'nullable',
        'id_posisi' => 'required|exists:posisi,id',
    ]);

    $karyawan = Karyawan::findOrFail($request->id);
    $karyawan->nik = $request->nik;
    $karyawan->nama = $request->nama_karyawan;
    $karyawan->jabatan = $request->jabatan;
    $karyawan->tmt_bekerja = $request->tmt_bekerja;
    $karyawan->tgl_diangkat_staf = $request->tgl_diangkat_staf;
    $karyawan->id_posisi = $request->id_posisi;
    $karyawan->save();

    return redirect()->route('admin.karyawan.index')->with('message', 'Data karyawan berhasil diperbarui.');

}




    public function delete($id)
    {
        DB::transaction(function () use ($id) {
            $karyawan = Karyawan::find($id);
            $karyawan->delete();
        });

        return redirect()->back()->with('error_message', 'Data karyawan berhasil dihapus');
    }




}
