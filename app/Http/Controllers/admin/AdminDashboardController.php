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

        $getDisetujui = PermintaanCuti::getDisetujui($idPosisi);
        $getPending = PermintaanCuti::getPending($idPosisi);
        $getDitolak = PermintaanCuti::getDitolak($idPosisi);
        $getKaryawanCuti = PermintaanCuti::getTodayKaryawanCuti($idPosisi);



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
            'karyawanCuti' => $getKaryawanCuti,

        ]);
    }

    public function downloadPermintaanCutiPDF()
    {
        $pdf = Pdf::loadView('form');

        // return view('form');

        return $pdf->download('pdf.pdf');
    }

    public function tambahCuti(Request $request)
    {

        // $karyawan = Karyawan::where('id', 55)->first();
        // $sisaCuti = SisaCutiPanjang::where('id_karyawan', $karyawan->id)->first();
        // $sisa = $sisaCuti->sisa_cuti;

        // $sisaCuti->sisa_cuti = $sisa - 1;
        // $sisaCuti->save();
        // return redirect()->route('admin.index');

        return view('admin.index');
    }
}
