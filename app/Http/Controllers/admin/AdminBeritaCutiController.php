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
use Carbon\Carbon;


class AdminBeritaCutiController extends Controller
{

    public function index()
    {
        $idUser = Auth::user()->id;
        $user = User::find($idUser);
        $idPosisi = $user->karyawan->posisi->id;
        $namaUser = $user->karyawan->nama;
        $jabatan = $user->karyawan->posisi->jabatan;

        // Mengambil semua permintaan cuti yang disetujui
        $cutiDisetujui = PermintaanCuti::where('is_approved', 1)->get();

        // Koleksi untuk menyimpan tanggal dan jumlah karyawan cuti
        $tanggalCutiMap = [];


        // Iterasi setiap permintaan cuti yang disetujui
        foreach ($cutiDisetujui as $cuti) {
            $tanggalMulai = Carbon::parse($cuti->tanggal_mulai);
            $tanggalSelesai = Carbon::parse($cuti->tanggal_selesai);

            // Iterasi setiap hari dari tanggal mulai sampai tanggal selesai
            for ($date = $tanggalMulai; $date->lte($tanggalSelesai); $date->addDay()) {
                $tanggalKey = $date->format('Y-m-d');

                // Jika tanggal sudah ada di dalam array, tambahkan jumlah karyawan
                if (isset($tanggalCutiMap[$tanggalKey])) {
                    $tanggalCutiMap[$tanggalKey]++;
                } else {
                    // Jika tidak, inisialisasi dengan 1
                    $tanggalCutiMap[$tanggalKey] = 1;
                }
            }
        }

        // $tanggal_cuti = PermintaanCuti::select('tanggal_mulai as tanggal', 'tanggal_selesai', 'id_karyawan')
        //     ->where('is_approved', 1)
        //     ->unionAll(function ($query) {
        //         $query->selectRaw('DATE_ADD(tanggal_mulai, INTERVAL 1 DAY) as tanggal, tanggal_selesai, id_karyawan')
        //             ->from('permintaan_cuti')
        //             ->whereColumn('tanggal_mulai', '<', 'tanggal_selesai');
        //     });

        // $query = DB::table(DB::raw("({$tanggal_cuti->toSql()}) as tanggal_cuti"))
        //     ->mergeBindings($tanggal_cuti->getQuery())
        //     ->select('tanggal_cuti.tanggal', DB::raw('COUNT(DISTINCT tanggal_cuti.id_karyawan) as jumlah_karyawan_cuti'))
        //     ->groupBy('tanggal_cuti.tanggal')
        //     ->orderBy('tanggal_cuti.tanggal')
        //     ->get();

        // $output = $query->map(function ($item, $key) {
        //     return [
        //         'start' => $item->tanggal,
        //         'title' => $item->jumlah_karyawan_cuti . ' orang',
        //     ];
        // })->toJson();

         // Konversi hasil ke dalam format yang sesuai
         $output = collect($tanggalCutiMap)->map(function ($jumlah, $tanggal) {
            return [
                'start' => $tanggal,
                'title' => $jumlah . ' orang',
            ];
        })->values()->toJson();


        return view('admin.cuti', [
            'idPosisi' => $idPosisi,
            'nama' => $namaUser,
            'jabatan' => $jabatan,
            'dataKalender' => $output,
        ]);
    }
}
