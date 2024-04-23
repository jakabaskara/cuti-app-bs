<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\JenisCuti;
use App\Models\Keanggotaan;
use App\Models\PermintaanCuti;
use App\Models\SisaCuti;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdminBeritaCutiController extends Controller
{
    // public function index()
    // {
    //     return view('admin.cuti');
    // }

    public function index()
    {
        // $idUser = 1;
        $idUser = Auth::user()->id;
        $user = User::find($idUser);
        $idPosisi = $user->karyawan->posisi->id;
        // $dataPairing = Pairing::getDaftarKaryawanCuti($idUser)->get();
        $namaUser = $user->karyawan->nama;
        $jabatan = $user->karyawan->posisi->jabatan;

        $tanggal_cuti = PermintaanCuti::select('tanggal_mulai as tanggal', 'tanggal_selesai', 'id_karyawan')
            ->where('is_approved', 1)
            ->unionAll(function ($query) {
                $query->selectRaw('DATE_ADD(tanggal_mulai, INTERVAL 1 DAY) as tanggal, tanggal_selesai, id_karyawan')
                    ->from('permintaan_cuti')
                    ->whereColumn('tanggal_mulai', '<', 'tanggal_selesai');
            });

        // Menghitung jumlah karyawan yang cuti pada setiap tanggal
        $query = DB::table(DB::raw("({$tanggal_cuti->toSql()}) as tanggal_cuti"))
            ->mergeBindings($tanggal_cuti->getQuery())
            ->select('tanggal_cuti.tanggal', DB::raw('COUNT(DISTINCT tanggal_cuti.id_karyawan) as jumlah_karyawan_cuti'))
            ->groupBy('tanggal_cuti.tanggal')
            ->orderBy('tanggal_cuti.tanggal')
            ->get();

        $output = $query->map(function ($item, $key) {
            return [
                'start' => $item->tanggal, // Ubah nama kolom 'tanggal' menjadi 'start'
                'title' => $item->jumlah_karyawan_cuti . ' orang', // Ubah nama kolom 'jumlah_karyawan_cuti' menjadi 'title'
            ];
        })->toJson();


        return view('admin.cuti', [
            'idPosisi' => $idPosisi,
            'nama' => $namaUser,
            'jabatan' => $jabatan,
            'dataKalender' => $output,
        ]);
    }
}
