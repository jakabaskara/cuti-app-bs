<?php

namespace App\Http\Controllers\kerani;

use App\Http\Controllers\Controller;
use App\Models\JenisCuti;
use App\Models\PermintaanCuti;
use App\Models\Karyawan;
use App\Models\Keanggotaan;
use App\Models\Pairing;
use App\Models\RiwayatCuti;
use App\Models\SisaCuti;
use App\Models\User;
use App\Notifications\SendNotification;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;


class KeraniDashboardController extends Controller
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
        $dataPairing = Keanggotaan::getAnggota($idPosisi);
        $sisaCuti = $dataPairing->each(function ($data) {
            $data->sisa_cuti_panjang = SisaCuti::where('id_karyawan', $data->id)->where('id_jenis_cuti', 1)->first()->jumlah ?? '0';
            $data->sisa_cuti_tahunan = SisaCuti::where('id_karyawan', $data->id)->where('id_jenis_cuti', 2)->first()->jumlah ?? '0';
        });

        $getDisetujui = PermintaanCuti::getDisetujui($idPosisi);
        $getPending = PermintaanCuti::getPending($idPosisi);
        $getDitolak = PermintaanCuti::getDitolak($idPosisi);
        $getKaryawanCuti = PermintaanCuti::getTodayKaryawanCuti($idPosisi);

        return view('kerani.index', [
            'dataPairing' => $dataPairing,
            'riwayats' => $riwayat,
            'idPosisi' => $idPosisi,
            'nama' => $namaUser,
            'jabatan' => $jabatan,
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
        $karyawan = $user->karyawan;

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

        DB::transaction(function () use ($validate, $startDate, $endDate, $idPosisi, $isChecked, $karyawan, $user) {

            $permintaanCuti = PermintaanCuti::create([
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

            RiwayatCuti::create([
                'id_permintaan_cuti' => $permintaanCuti->id,
                'nama_pembuat' => $karyawan->nama,
                'jabatan_pembuat' => $karyawan->posisi->jabatan,
            ]);
            $nama = Karyawan::find($validate['karyawan'])->nama;
            $message = "Terdapat Permintaan Cuti Baru\n";
            $message .= "Nama: $nama\n";
            $message .= "Tanggal Mulai: $startDate\n";
            $message .= "Tanggal Selesai: $endDate\n";
            $message .= "Alasan: " . $validate['alasan'];
            Notification::send($user, new SendNotification($message));
        });

        return redirect()->back();
    }

    public function downloadPermintaanCutiPDF($id)
    {
        $permintaanCuti = PermintaanCuti::find($id);
        $karyawan = $permintaanCuti->karyawan;
        $pairing = Pairing::where('id_bawahan', $karyawan->id_posisi)->get()->first();
        $jabatan = $pairing->atasan->jabatan;
        $nama = $pairing->atasan->karyawan->first()->nama;
        $sisaCutiPanjang = SisaCuti::where('id_karyawan', $karyawan->id)->where('id_jenis_cuti', 1)->first()->jumlah ?? '0';
        $sisaCutiTahunan = SisaCuti::where('id_karyawan', $karyawan->id)->where('id_jenis_cuti', 2)->first()->jumlah ?? '0';
        $cutiPanjangDijalani = 0;
        $cutiTahunanDijalani = 0;
        if ($permintaanCuti->id_jenis_cuti == 1) {
            $cutiPanjangDijalani = $permintaanCuti->jumlah_hari_cuti;
        } else {
            $cutiTahunanDijalani = $permintaanCuti->jumlah_hari_cuti;
        }

        $cutiPanjangDijalani += $sisaCutiPanjang;
        $cutiTahunanDijalani += $sisaCutiTahunan;

        $pdf = Pdf::loadView('form', [
            'karyawan' => $karyawan,
            'namaAtasan' => $nama,
            'jabatan' => $jabatan,
            'permintaanCuti' => $permintaanCuti,
            'sisaCutiPanjang' => $sisaCutiPanjang,
            'sisaCutiTahunan' => $sisaCutiTahunan,
            'cutiPanjangDijalani' => $cutiPanjangDijalani,
            'cutiTahunanDijalani' => $cutiTahunanDijalani,

        ]);

        // return view('form');

        return $pdf->download('Form Cuti ' . $karyawan->nama . ' .pdf');
    }

    public function delete($id)
    {
        DB::transaction(function () use ($id) {
            $riwayatPermintaanCuti = RiwayatCuti::where('id_permintaan_cuti', $id);
            $riwayatPermintaanCuti->delete();

            $permintaanCuti = PermintaanCuti::find($id);
            $permintaanCuti->delete();
        });

        return redirect()->back()->with('message', 'Data Berhasil Dihapus');
    }

    public function sendNoti()
    {
        $users = Auth::user();
        Notification::send($users, new SendNotification('ss'));
    }
}
