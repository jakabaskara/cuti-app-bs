<?php

namespace App\Http\Controllers\kerani;

use App\Http\Controllers\Controller;
use App\Models\JenisCuti;
use App\Models\JenisPermintaanCuti;
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
use Telegram\Bot\Keyboard\Button;
use Telegram\Bot\Keyboard\Keyboard;
use Telegram\Bot\Laravel\Facades\Telegram;

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

        ]);
    }

    public function submitCuti(Request $request)
    {
        $validate = $request->validate([
            'karyawan' => 'required',
            // 'jenis_cuti' => 'required',
            'tanggal_cuti' => 'required',
            'jumlah_cuti_panjang' => 'required',
            'jumlah_cuti_tahunan' => 'required',
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
                'tanggal_mulai' => $startDate,
                'tanggal_selesai' => $endDate,
                'jumlah_cuti_panjang' => $validate['jumlah_cuti_panjang'],
                'jumlah_cuti_tahunan' => $validate['jumlah_cuti_tahunan'],
                'alamat' => $validate['alamat'],
                'alasan' => $validate['alasan'],
                'id_posisi_pembuat' => $idPosisi,
                'is_approved' => 0,
                'is_rejected' => 0,
                'is_checked' => $isChecked,
            ]);

            // SisaCuti::where('id_karyawan', $validate['karyawan'])->where('id_jenis_cuti', 1)->decrement('jumlah', $validate['jumlah_cuti_panjang']);
            // SisaCuti::where('id_karyawan', $validate['karyawan'])->where('id_jenis_cuti', 2)->decrement('jumlah', $validate['jumlah_cuti_tahunan']);


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



            $keyboard = Keyboard::make()->inline();

            $buttonSetujui = Keyboard::inlineButton(['text' => 'Setujui', 'callback_data' => 'setujui']);
            $buttonTolak = Keyboard::inlineButton(['text' => 'Tolak', 'callback_data' => 'tolak']);

            $keyboard->row([$buttonSetujui, $buttonTolak]);

            $pesan = 'Apakah Cuti Disetujui?';

            // Mengonversi keyboard menjadi JSON
            $keyboard = json_encode($keyboard);

            // Telegram::sendChatAction($keyboard);

            $response = Telegram::sendMessage([
                'chat_id' => '1176854977', // ID chat yang dituju
                'text' => $pesan, // Isi pesan yang ingin Anda kirim
                'reply_markup' => $keyboard // Markup keyboard jika Anda ingin menyertakannya
            ]);

            // Kirim pesan dengan keyboard inline
            // $response = file_get_contents("https://api.telegram.org/bot7168138742:AAH7Nlo0YsgvIl4S-DexMsWK34_SOAocfqI/sendMessage?chat_id=1176854977&text=$pesan&reply_markup=$keyboard");
        });

        return redirect()->back()->with('message', 'Permintaan Cuti Berhasil Dibuat!');
    }

    public function downloadPermintaanCutiPDF($id)
    {
        $permintaanCuti = PermintaanCuti::find($id);
        $karyawan = $permintaanCuti->karyawan;
        $pairing = Pairing::where('id_bawahan', $karyawan->id_posisi)->get()->first();
        $jabatan = $pairing->atasan->jabatan;
        $bagian = $pairing->atasan->karyawan->first()->posisi->unitKerja->nama_unit_kerja;
        $atasan = $pairing->atasan->karyawan->first();
        $nama = $atasan->nama;
        $nik = $atasan->nik;
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
            'bagian' => $bagian,
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
