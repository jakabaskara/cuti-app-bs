<?php

namespace App\Http\Controllers\admin;

use App\Models\Karyawan;
use App\Models\SisaCutiPanjang;
use App\Http\Controllers\Controller;
use App\Models\JenisCuti;
use App\Models\Keanggotaan;
use App\Models\PermintaanCuti;
use App\Models\SisaCuti;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // $idUser = 1;
        $idUser = Auth::user()->id;
        $user = User::find($idUser);
        $idPosisi = $user->karyawan->posisi->id;
        // $dataPairing = Pairing::getDaftarKaryawanCuti($idUser)->get();
        $riwayat = PermintaanCuti::getHistoryCuti($idPosisi)->get();
        $namaUser = $user->karyawan->nama;
        $jabatan = $user->karyawan->posisi->jabatan;
        $jenisCuti = JenisCuti::get();
        $dataPairing = Keanggotaan::getAnggota($idPosisi);
        $sisaCuti = $dataPairing->each(function ($data) {
            $data->sisa_cuti_panjang = SisaCuti::where('id_karyawan', $data->id)->where('id_jenis_cuti', 1)->first()->jumlah ?? '0';
            $data->sisa_cuti_tahunan = SisaCuti::where('id_karyawan', $data->id)->where('id_jenis_cuti', 2)->first()->jumlah ?? '0';
        });

        $getKaryawanCuti = PermintaanCuti::getTodayKaryawanCuti($idPosisi);


        // $getDisetujui = PermintaanCuti::where('is_approved', 1)->count();
        // $getPending = PermintaanCuti::where('is_checked', 1)
        // ->where('is_approved', 0)
        // ->where('is_rejected', 0)
        // ->count();
        // $getDitolak = PermintaanCuti::where('is_rejected', 1)->count();
        // $getMenunggudiketahui = PermintaanCuti::where('is_checked', 0)
        // ->where('is_approved', 0)
        // ->where('is_rejected', 0)
        // ->count();

        $getDisetujui = PermintaanCuti::withTrashed()->with(['karyawan' => function ($query) {
            $query->withTrashed();
        }])->where('is_approved', 1)->count();
        $getPending = PermintaanCuti::withTrashed()->with(['karyawan' => function ($query) {
            $query->withTrashed();
        }])->where('is_checked', 1)
        ->where('is_approved', 0)
        ->where('is_rejected', 0)
        ->count();
        $getDitolak = PermintaanCuti::withTrashed()->with(['karyawan' => function ($query) {
            $query->withTrashed();
        }])->where('is_rejected', 1)->count();
        $getMenunggudiketahui = PermintaanCuti::withTrashed()->with(['karyawan' => function ($query) {
            $query->withTrashed();
        }])->where('is_checked', 0)
        ->where('is_approved', 0)
        ->where('is_rejected', 0)
        ->count();


        return view('admin.index', [
            'dataPairing' => $dataPairing,
            'riwayats' => $riwayat,
            'idPosisi' => $idPosisi,
            'nama' => $namaUser,
            'jabatan' => $jabatan,
            'jenisCuti' => $jenisCuti,
            'sisaCutis' => $sisaCuti,
            'disetujui' => $getDisetujui,
            'pending' => $getPending,
            'ditolak' => $getDitolak,
            'menunggudiketahui' => $getMenunggudiketahui,
            'karyawanCuti' => $getKaryawanCuti,
        ]);
    }
}
