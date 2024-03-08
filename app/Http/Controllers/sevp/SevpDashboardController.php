<?php

namespace App\Http\Controllers\sevp;

use App\Http\Controllers\Controller;
use App\Models\JenisCuti;
use App\Models\Karyawan;
use App\Models\Keanggotaan;
use App\Models\PermintaanCuti;
use App\Models\SisaCuti;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SevpDashboardController extends Controller
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

        return view('sevp.index', [
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

        $idUser = Auth::user()->id;
        $user = User::find($idUser);
        $idPosisi = $user->karyawan->id_posisi;

        if (strlen($request->tanggal_cuti) != 10) {
            list($startDate, $endDate) = explode(" to ", $request->tanggal_cuti);
            // Konversi string tanggal menjadi format timestamp
            $startDate = strtotime($startDate);
            $endDate = strtotime($endDate);

            // Format ulang tanggal ke format yang diinginkan
            $startDate = date("Y-m-d", $startDate);
            $endDate = date("Y-m-d", $endDate);
        } else {
            $startDate = $request->tanggal_cuti;
            $endDate = $request->tanggal_cuti;
        };

        $isManager = Karyawan::find($request->karyawan)->posisi->role->nama_role == 'sevp' ? true : false;
        $isChecked = $isManager ? 0 : 1;

        PermintaanCuti::create([
            'id_karyawan' => $validate['karyawan'],
            'id_jenis_cuti' => $validate['jenis_cuti'],
            'tanggal_mulai' => $startDate,
            'tanggal_selesai' => $endDate,
            'jumlah_hari_cuti' => $validate['jumlah_cuti'],
            'alamat' => $validate['alamat'],
            'alasan' => $validate['alasan'],
            'id_posisi_pembuat' => $idPosisi,
            'is_approved' => 0,
            'is_rejected' => 0,
            'is_checked' => $isChecked,
        ]);


        return redirect()->back();
    }

}
