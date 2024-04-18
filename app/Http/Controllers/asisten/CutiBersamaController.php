<?php

namespace App\Http\Controllers\asisten;

use App\Http\Controllers\Controller;
use App\Models\KaryawanCutiBersama;
use App\Models\SisaCuti;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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

    public function storeHadir(Request $request)
    {
        $validate = $request->validate(
            [
                'idKaryawanTidakCuti' => 'required|array|min:1',
                'idKaryawanTidakCuti.*' => 'required|numeric'
            ],
            [
                'idKaryawanTidakCuti.required' => 'Karyawan harus diisi.',
            ]
        );

        DB::transaction(function () use ($request) {
            foreach ($request['idKaryawanTidakCuti'] as $data) {
                $dataTidakCuti = KaryawanCutiBersama::find($data);
                if ($dataTidakCuti) {
                    $dataCuti = SisaCuti::where('id_karyawan', $dataTidakCuti->id_karyawan)->where('id_jenis_cuti', 2)->first();
                    if ($dataCuti) {
                        $dataCuti->jumlah += 1;
                        $dataCuti->save();
                    }

                    $dataTidakCuti->delete();
                }
            }
        });

        return redirect()->back()->with('message', 'Data Berhasil Diupdate!');
    }
}
