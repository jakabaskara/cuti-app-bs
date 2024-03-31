<?php

namespace App\Http\Controllers\kerani;

use App\Http\Controllers\Controller;
use App\Models\JenisCuti;
use App\Models\JenisPermintaanCuti;
use App\Models\PermintaanCuti;
use App\Models\Karyawan;
use App\Models\Keanggotaan;
use App\Models\Pairing;
use App\Models\Posisi;
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
        $karyawan = $user->karyawan;
        $idPosisi = $karyawan->posisi->id;
        // $dataPairing = Pairing::getDaftarKaryawanCuti($idUser)->get();
        $riwayat = PermintaanCuti::getHistoryCuti($idPosisi)->get();
        $namaUser = $user->karyawan->nama;
        $jabatan = $user->karyawan->posisi->jabatan;
        $dataPairing = Keanggotaan::getAnggota($idPosisi);
        $sisaCuti = $dataPairing->each(function ($data) {
            $data->sisa_cuti_panjang = SisaCuti::where('id_karyawan', $data->id)->where('id_jenis_cuti', 1)->first()->jumlah ?? '0';
            $data->jatuh_tempo_tahunan = SisaCuti::where('id_karyawan', $data->id)->where('id_jenis_cuti', 1)->get()->first();
            $data->sisa_cuti_tahunan = SisaCuti::where('id_karyawan', $data->id)->where('id_jenis_cuti', 2)->first()->jumlah ?? '0';
            $data->jatuh_tempo_panjang = SisaCuti::where('id_karyawan', $data->id)->where('id_jenis_cuti', 2)->get()->first();
        });
        $isKandir = $karyawan->posisi->unitKerja->nama_unit_kerja == 'Region Office' ? true : false;


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
            'isKandir' => $isKandir,
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
            'jumlahHariCuti' => 'required|min:1|numeric'
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
        $isKebun = Posisi::isKebun($idPosisi);
        if ($isManager == true) {
            $isManager = 1;
        } elseif ($isKebun == 1) {
            $isManager = 0;
        } else {
            $isManager = 1;
        }
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

            $karyawan_req = Karyawan::find($validate['karyawan']);
            $periodeCuti = SisaCuti::where('id_karyawan', $karyawan_req->id)->get();

            $periode = $periodeCuti->flatMap(function ($data) {
                if ($data->id_jenis_cuti == 1) {
                    $tanggal['periode_cuti_panjang'] = date('Y', strtotime($data->periode_mulai)) . "/" . date('Y', strtotime($data->periode_akhir));
                } elseif ($data->id_jenis_cuti == 2) {
                    $tanggal['periode_cuti_tahunan'] = date('Y', strtotime($data->periode_mulai)) . "/" . date('Y', strtotime($data->periode_akhir));
                } else {
                    $tanggal['periode_cuti_panjang'] = '';
                    $tanggal['periode_cuti_panjang'] = '';
                }
                return $tanggal;
            });

            // if (!array_key_exists('periode_cuti_panjang', $periode)) {
            //     $periode['periode_cuti_panjang'] = '';
            // }
            // if (!array_key_exists('periode_cuti_tahunan', $periode)) {
            //     $periode['periode_cuti_tahunan'] = '';
            // }

            RiwayatCuti::create([
                'id_permintaan_cuti' => $permintaanCuti->id,
                'nama_pembuat' => $karyawan->nama,
                'jabatan_pembuat' => $karyawan->posisi->jabatan,
                'periode_cuti_tahunan' => $periode['periode_cuti_tahunan'],
                'periode_cuti_panjang' => $periode['periode_cuti_panjang'],
            ]);
            $message = "Terdapat Permintaan Cuti Baru\n";
            $message .= "Nama: $karyawan_req->nama\n";
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
        $nik = $atasan->NIK;
        $riwayatPermintaanCuti = RiwayatCuti::where('id_permintaan_cuti', $permintaanCuti->id)->first();
        $sisaCutiPanjang = $riwayatPermintaanCuti->sisa_cuti_panjang;
        $sisaCutiTahunan = $riwayatPermintaanCuti->sisa_cuti_tahunan;

        $cutiPanjangDijalani = $permintaanCuti->jumlah_cuti_panjang;
        $cutiTahunanDijalani = $permintaanCuti->jumlah_cuti_tahunan;

        $cutiPanjangDijalani += $sisaCutiPanjang;
        $cutiTahunanDijalani += $sisaCutiTahunan;

        $pdf = Pdf::loadView('form', [
            'nik' => $nik,
            'bagian' => $bagian,
            'karyawan' => $karyawan,
            'namaAtasan' => $nama,
            'jabatan' => $jabatan,
            'permintaanCuti' => $permintaanCuti,
            'sisaCutiPanjang' => $sisaCutiPanjang,
            'sisaCutiTahunan' => $sisaCutiTahunan,
            'cutiPanjangDijalani' => $cutiPanjangDijalani,
            'cutiTahunanDijalani' => $cutiTahunanDijalani,
            'periode_cuti_panjang' => $riwayatPermintaanCuti->periode_cuti_panjang,
            'periode_cuti_tahunan' => $riwayatPermintaanCuti->periode_cuti_tahunan,

        ]);

        // return view('form');
        // return $pdf->stream();
        return $pdf->download('Form Cuti ' . $karyawan->nama . ' tanggal ' . $permintaanCuti->tanggal_mulai . ' .pdf');
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
