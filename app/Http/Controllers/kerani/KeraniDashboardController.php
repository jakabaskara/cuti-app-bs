<?php

namespace App\Http\Controllers\kerani;

use App\Http\Controllers\Controller;
use App\Models\PermintaanCuti;
use App\Models\Karyawan;
use App\Models\Keanggotaan;
use App\Models\Pairing;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class KeraniDashboardController extends Controller
{
    public function index()
    {
        // $idUser = 1;
        $idUser = Auth::user()->id;
        $user = User::find($idUser);
        $idPosisi = $user->karyawan->posisi->id;
        $dataPairing = Keanggotaan::getAnggota($idPosisi);
        // $dataPairing = Pairing::getDaftarKaryawanCuti($idUser)->get();
        $riwayat = PermintaanCuti::getHistoryCuti($idUser);
        $namaUser = $user->karyawan->nama;
        $jabatan = $user->karyawan->posisi->jabatan;

        return view('kerani.index', [
            'dataPairing' => $dataPairing,
            'riwayats' => $riwayat,
            'idPosisi' => $idPosisi,
            'nama' => $namaUser,
            'jabatan' => $jabatan,
        ]);
    }

    public function submitCuti(Request $request)
    {
        $validate = $request->validate([
            'karyawan' => 'required',
            'jenis_cuti' => 'required',
            'tanggal_cuti' => 'required',
            'jumlah_cuti' => 'required',
            'alasan' => 'required',
            'alamat' => 'required',
        ]);

        list($startDate, $endDate) = explode(" to ", $request->tanggal_cuti);

        // Konversi string tanggal menjadi format timestamp
        $startDate = strtotime($startDate);
        $endDate = strtotime($endDate);

        // Format ulang tanggal ke format yang diinginkan
        $startDate = date("Y-m-d", $startDate);
        $endDate = date("Y-m-d", $endDate);
        // $isKer = Karyawan::find($request->karyawan)->posisi->role->nama_role == 'manajer' ? true : false;
        // $isChecked = $isManager ? 0 : 1;

        PermintaanCuti::create([
            'id_karyawan' => $validate['karyawan'],
            'id_jenis_cuti' => $validate['jenis_cuti'],
            'tanggal_mulai' => $startDate,
            'tanggal_selesai' => $endDate,
            'jumlah_hari_cuti' => $validate['jumlah_cuti'],
            'alamat' => $validate['alamat'],
            'alasan' => $validate['alasan'],
            'id_pairing' => '1',
            'is_approved' => 0,
            'is_rejected' => 0,
            'is_checked' => 1,
        ]);

        return redirect()->back();
    }
}
