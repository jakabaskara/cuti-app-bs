<?php

namespace App\Http\Controllers\sevp;

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

class SevpBeritaCutiController extends Controller
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

        // Konversi hasil ke dalam format yang sesuai
        $output = collect($tanggalCutiMap)->map(function ($jumlah, $tanggal) {
            return [
                'start' => $tanggal,
                'title' => $jumlah . ' orang',
            ];
        })->values()->toJson();

        return view('sevp.cuti', [
            'idPosisi' => $idPosisi,
            'nama' => $namaUser,
            'jabatan' => $jabatan,
            'dataKalender' => $output,
        ]);
    }
}
