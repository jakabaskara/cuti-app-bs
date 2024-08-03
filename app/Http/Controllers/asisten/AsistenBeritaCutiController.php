<?php

namespace App\Http\Controllers\asisten;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\JenisCuti;
use App\Models\Keanggotaan;
use App\Models\PermintaanCuti;
use App\Models\SisaCuti;
use App\Models\User;
use App\Models\UnitKerja;
use App\Models\Posisi;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AsistenBeritaCutiController extends Controller
{

    // public function index()
    // {
    //     $idUser = Auth::user()->id;
    //     $user = User::find($idUser);
    //     $karyawan = $user->karyawan;
    //     $idPosisi = $user->karyawan->posisi->id;
    //     $namaUser = $user->karyawan->nama;
    //     $jabatan = $user->karyawan->posisi->jabatan;

    //     // Mengambil semua permintaan cuti yang disetujui
    //     $cutiDisetujui = PermintaanCuti::where('is_approved', 1)
    //     ->whereHas('karyawan.posisi.unitKerja', function ($query) use ($karyawan) {
    //         $query->where('nama_unit_kerja', $karyawan->posisi->unitKerja->nama_unit_kerja);
    //     })
    //     ->get();

    //     // Koleksi untuk menyimpan tanggal dan jumlah karyawan cuti
    //     $tanggalCutiMap = [];

    //     // Iterasi setiap permintaan cuti yang disetujui
    //     foreach ($cutiDisetujui as $cuti) {
    //         $tanggalMulai = Carbon::parse($cuti->tanggal_mulai);
    //         $tanggalSelesai = Carbon::parse($cuti->tanggal_selesai);

    //         // Iterasi setiap hari dari tanggal mulai sampai tanggal selesai
    //         for ($date = $tanggalMulai; $date->lte($tanggalSelesai); $date->addDay()) {
    //             $tanggalKey = $date->format('Y-m-d');

    //             // Jika tanggal sudah ada di dalam array, tambahkan jumlah karyawan
    //             if (isset($tanggalCutiMap[$tanggalKey])) {
    //                 $tanggalCutiMap[$tanggalKey]++;
    //             } else {
    //                 // Jika tidak, inisialisasi dengan 1
    //                 $tanggalCutiMap[$tanggalKey] = 1;
    //             }
    //         }
    //     }

    //     // Konversi hasil ke dalam format yang sesuai
    //     $output = collect($tanggalCutiMap)->map(function ($jumlah, $tanggal) {
    //         return [
    //             'start' => $tanggal,
    //             'title' => $jumlah . ' orang',
    //         ];
    //     })->values()->toJson();

    //     return view('asisten.cuti', [
    //         'idPosisi' => $idPosisi,
    //         'nama' => $namaUser,
    //         'jabatan' => $jabatan,
    //         'dataKalender' => $output,
    //     ]);
    // }


    public function index()
    {
        $idUser = Auth::user()->id;
        $user = User::find($idUser);
        $karyawan = $user->karyawan;
        $idPosisi = $user->karyawan->posisi->id;
        $namaUser = $user->karyawan->nama;
        $jabatan = $user->karyawan->posisi->jabatan;

        // Mengambil semua permintaan cuti yang disetujui dan dibuat oleh pengguna yang sama
        // $cutiDisetujui = PermintaanCuti::where('is_approved', 1)
        //     ->where('id_posisi_pembuat', $idPosisi)
        //     ->get();
        $cutiDisetujui = PermintaanCuti::withTrashed()->with(['karyawan' => function ($query) {
            $query->withTrashed();
        }])->where('is_approved', 1)
            ->where('id_posisi_pembuat', $idPosisi)
            ->get();

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

        // Konversi hasil ke dalam format yang sesuai
        $output = collect($tanggalCutiMap)->map(function ($jumlah, $tanggal) {
            return [
                'start' => $tanggal,
                'title' => $jumlah . ' orang',
            ];
        })->values()->toJson();

        return view('asisten.cuti', [
            'idPosisi' => $idPosisi,
            'nama' => $namaUser,
            'jabatan' => $jabatan,
            'dataKalender' => $output,
        ]);
    }
}
