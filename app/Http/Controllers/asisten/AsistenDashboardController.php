<?php

namespace App\Http\Controllers\asisten;

use App\Http\Controllers\Controller;
use App\Models\Karyawan;
use App\Models\Keanggotaan;
use App\Models\Pairing;
use App\Models\PermintaanCuti;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AsistenDashboardController extends Controller
{

    public function index()
    {
        $idUser = Auth::user()->id;
        $user = User::find($idUser);
        $karyawan = $user->karyawan;


        return view('asisten.index');
    }

    public function pengajuanCuti()
    {
        // $dataPairing = Pairing::getDaftarKaryawanCuti(1)->get();
        // return view('asisten.pengajuan-cuti', [
        //     'dataPairing' => $dataPairing
        // ]);
        $idUser = Auth::user()->id;
        $idPosisi = User::find($idUser)->karyawan->posisi->id;
        $anggota = Keanggotaan::getAnggota($idPosisi);;
        $anggota = Keanggotaan::where('id_posisi', 4)->get();

        $riwayat = PermintaanCuti::getHistoryCuti($idUser);

        return view('asisten.pengajuan-cuti', [
            'riwayats' => $riwayat,
            'anggotas' => $anggota,
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
        $isManager = Karyawan::find($request->karyawan)->posisi->role->nama_role == 'manajer' ? true : false;
        $isChecked = $isManager ? 0 : 1;

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
            'is_checked' => $isChecked,
        ]);

        return redirect()->back();
    }

    public function deleteCuti($id)
    {
        $permintaanCuti = PermintaanCuti::find($id);
        $nama = $permintaanCuti->karyawan->nama;
        $permintaanCuti->delete();


        return redirect()->back()->with('message', 'Data Cuti ' . $nama . "Berhasil Dihapus");
    }
}
