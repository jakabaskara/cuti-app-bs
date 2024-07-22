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
        $isKebun = $karyawan->posisi->unitKerja->is_kebun;
        $sisaCuti = $dataPairing->each(function ($data) {
            $data->sisa_cuti_panjang = SisaCuti::where('id_karyawan', $data->id)->where('id_jenis_cuti', 1)->first()->jumlah ?? '0';
            $data->jatuh_tempo_panjang = SisaCuti::where('id_karyawan', $data->id)->where('id_jenis_cuti', 1)->get()->first();
            $data->sisa_cuti_tahunan = SisaCuti::where('id_karyawan', $data->id)->where('id_jenis_cuti', 2)->first()->jumlah ?? '0';
            $data->jatuh_tempo_tahunan = SisaCuti::where('id_karyawan', $data->id)->where('id_jenis_cuti', 2)->get()->first();
        });
        $isKandir = $karyawan->posisi->unitKerja->nama_unit_kerja == 'Region Office' ? true : false;


        $getDisetujui = PermintaanCuti::getDisetujui($idPosisi);
        $getPending = PermintaanCuti::getPending($idPosisi);
        $getDitolak = PermintaanCuti::getDitolak($idPosisi);
        $getMenunggudiketahui = PermintaanCuti::getMenunggudiketahui($idPosisi);

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
            'is_kebun' => $isKebun,
            'menunggudiketahui' => $getMenunggudiketahui,
        ]);
    }

    public function submitCuti(Request $request)
    {
        $validate = $request->validate([
            'karyawan' => 'required',
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



        // Cek apakah ada permintaan cuti yang belum diproses (is_approved atau is_rejected bukan 1)
        $pendingCuti = PermintaanCuti::where('id_karyawan', $validate['karyawan'])
            ->where('is_approved', 0)
            ->where('is_rejected', 0)
            ->exists();

        if ($pendingCuti) {
            return redirect()->back()->with('error_message', 'Terdapat Permintaan Cuti Yang Belum Diproses!');
        }


       // Memeriksa apakah karyawan sudah mengajukan cuti untuk tanggal yang sama
       $startDate = $endDate = $validate['tanggal_cuti'];

       if (strpos($validate['tanggal_cuti'], ' to ') !== false) {
           list($startDate, $endDate) = explode(" to ", $validate['tanggal_cuti']);
       }

       $existingCuti = PermintaanCuti::where('id_karyawan', $validate['karyawan'])
           ->where(function ($query) use ($startDate, $endDate) {
               $query->where(function ($query) use ($startDate, $endDate) {
                   $query->where('tanggal_mulai', '<=', $startDate)
                         ->where('tanggal_selesai', '>=', $startDate);
               })->orWhere(function ($query) use ($startDate, $endDate) {
                   $query->where('tanggal_mulai', '<=', $endDate)
                         ->where('tanggal_selesai', '>=', $endDate);
               })->orWhere(function ($query) use ($startDate, $endDate) {
                   $query->where('tanggal_mulai', '>=', $startDate)
                         ->where('tanggal_selesai', '<=', $endDate);
               });
           })
           ->where('is_rejected', 0) //kondisi tambahan untuk  cuti yang belum ditolak
           ->exists();

       if ($existingCuti) {
           return redirect()->back()->with('error_message', 'Cuti sudah ada!');
       }


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
            $isManager = 0;               //bukan manajer tetapi dikebun
        } else {
            $isManager = 1;               //bukan manajer bukan di kebun
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

            // Notification::send($user, new SendNotification($message));

            // $keyboard = Keyboard::make()->inline();

            // $buttonSetujui = Keyboard::inlineButton(['text' => 'Klik untuk konfirmasi',  'web_app' => ['url' => 'https://relico.reg5palmco.com']]);

            // $keyboard->row([$buttonSetujui]);

            // $pesan = 'Apakah Cuti Disetujui?';

            // $keyboard = json_encode($keyboard);

            // $response = Telegram::sendMessage([
            //     'chat_id' => '1176854977',
            //     'text' => $pesan,
            //     'reply_markup' => $keyboard,
            // ]);

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
        $nama = $pairing->atasan->karyawan->first()->nama;
        $atasan = $pairing->atasan->karyawan->first();
        $nik = $atasan->NIK;
        $sisaCutiPanjang = SisaCuti::where('id_karyawan', $karyawan->id)->where('id_jenis_cuti', 1)->first()->jumlah ?? '0';
        $sisaCutiTahunan = SisaCuti::where('id_karyawan', $karyawan->id)->where('id_jenis_cuti', 2)->first()->jumlah ?? '0';
        $cutiPanjangDijalani = 0;
        $cutiTahunanDijalani = 0;
        $cutiPanjangDijalani = $permintaanCuti->jumlah_cuti_panjang;
        $cutiTahunanDijalani = $permintaanCuti->jumlah_cuti_tahunan;

        $riwayatCuti = RiwayatCuti::where('id_permintaan_cuti', $permintaanCuti->id)->first();
        $checkedBy = $riwayatCuti->nama_checker;
        $jabatanChecker = $riwayatCuti->jabatan_checker;
        $nama_approver = $riwayatCuti->nama_approver;
        $jabatan_approver = $riwayatCuti->jabatan_approver;
        $nik_approver = $riwayatCuti->nik_approver;
        $nik_checker = $riwayatCuti->nik_checker;
        $periode_panjang = explode('/', $riwayatCuti->periode_cuti_panjang);
        $periode_tahunan = explode('/', $riwayatCuti->periode_cuti_tahunan);
        $periode_panjang[0] -= 6;
        $periode_panjang[1] -= 6;
        $periode_tahunan[0] -= 1;
        $periode_tahunan[1] -= 1;

        $tahun_panjang = implode('/', $periode_panjang);
        $tahun_tahunan = implode('/', $periode_tahunan);


        $cutiPanjangDijalani += $sisaCutiPanjang;
        $cutiTahunanDijalani += $sisaCutiTahunan;
        if ($karyawan->posisi->unitKerja->is_kebun == 0) {
            $pdf = Pdf::loadView('formGM', [
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
                'nama_checker' => $checkedBy,
                'jabatan_checker' => $jabatanChecker,
                'nama_approver' => $nama_approver,
                'jabatan_approver' => $jabatan_approver,
                'nik_approver' => $nik_approver,
                'nik_checker' => $nik_checker,
                'periode_cuti_panjang' => $tahun_panjang,
                'periode_cuti_tahunan' => $tahun_tahunan,
            ]);
        } else {
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
                'nama_checker' => $checkedBy,
                'jabatan_checker' => $jabatanChecker,
                'nama_approver' => $nama_approver,
                'jabatan_approver' => $jabatan_approver,
                'nik_approver' => $nik_approver,
                'nik_checker' => $nik_checker,
                'periode_cuti_panjang' => $tahun_panjang,
                'periode_cuti_tahunan' => $tahun_tahunan,
            ]);
        }

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

        return redirect()->back()->with('error_message', 'Data Berhasil Dihapus');
    }

    public function sendNoti()
    {
        $users = Auth::user();
        Notification::send($users, new SendNotification('ss'));
    }
}
