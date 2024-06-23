<?php

namespace App\Http\Controllers\kabag;

use App\Http\Controllers\Controller;
use App\Models\JenisCuti;
use Illuminate\Http\Request;
use App\Models\Karyawan;
use App\Models\Keanggotaan;
use App\Models\Pairing;
use App\Models\PermintaanCuti;
use App\Models\RiwayatCuti;
use App\Models\SisaCuti;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class KabagDashboardController extends Controller
{

    public function index()
    {
        // $idUser = 1;
        $idUser = Auth::user()->id;
        $user = User::find($idUser);
        $karyawan = $user->karyawan;
        $idUnitKerja = $karyawan->posisi->id_unit_kerja;
        $idPosisi = $karyawan->posisi->id;
        $permintaanCuti = PermintaanCuti::whereHas('posisi', function ($query) use ($idUnitKerja) {
            $query->where('id_unit_kerja', $idUnitKerja);
        })->orderBy('id', 'DESC')->get();
        $karyawan->posisi->first()->unitKerja->id_unit_kerja;
        // $dataPairing = Pairing::getDaftarKaryawanCuti($idUser)->get();
        $riwayat = PermintaanCuti::getHistoryCuti($idPosisi)->get();
        $namaUser = $karyawan->nama;
        $jabatan = $karyawan->posisi->jabatan;
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

        return view('kabag.index', [
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
            'permintaanCutis' => $permintaanCuti,

        ]);
    }
}
