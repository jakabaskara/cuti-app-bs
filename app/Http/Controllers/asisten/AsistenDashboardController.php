<?php

namespace App\Http\Controllers\asisten;

use App\Http\Controllers\Controller;
use App\Models\Karyawan;
use App\Models\Keanggotaan;
use App\Models\Pairing;
use App\Models\PermintaanCuti;
use App\Models\RiwayatCuti;
use App\Models\SisaCuti;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AsistenDashboardController extends Controller
{

    public function index()
    {
        $idUser = Auth::user()->id;
        $user = User::find($idUser);
        $karyawan = $user->karyawan;

        $idPosisi = $karyawan->posisi->id;

        $namaUser = $user->karyawan->nama;
        $jabatan = $user->karyawan->posisi->jabatan;
        $riwayat = PermintaanCuti::getHistoryCuti($karyawan->id_posisi)->get();
        $isKebun = $karyawan->posisi->unitKerja->is_kebun;

        // Mengambil data untuk status bar pertama (Daftar Pengajuan Cuti) cuti dibuat
        // $getDisetujui = PermintaanCuti::getDisetujui($idPosisi);
        // $getPending = PermintaanCuti::getPending($idPosisi);
        // $getDitolak = PermintaanCuti::getDitolak($idPosisi);
        // $getMenunggudiketahui = PermintaanCuti::getMenunggudiketahui($idPosisi);

        // Mengambil data untuk status bar kedua (Riwayat Persetujuan Cuti) cuti dibuat orang lain
        // $getSetuju = PermintaanCuti::getDisetujuiCuti($idPosisi)->count();
        // $getTunggu = PermintaanCuti::getPendingCuti($idPosisi)->count();
        // $getTolak = PermintaanCuti::getDibatalkanCuti($idPosisi)->count();
        // $getBelumdiketahui = PermintaanCuti::getMenungguPersetujuan($idPosisi)->count();

        // Menghitung total dari kedua set status bar
        // $totalDisetujui = $getDisetujui + $getSetuju;
        // $totalPending = $getPending + $getTunggu;
        // $totalDitolak = $getDitolak + $getTolak;
        // $totalMenunggudiketahui = $getMenunggudiketahui + $getBelumdiketahui;

        return view('asisten.index', [
            'nama' => $namaUser,
            'jabatan' => $jabatan,
            'riwayats' => $riwayat,
            'is_kebun' => $isKebun,
            // 'disetujui' => $totalDisetujui,
            // 'pending' => $totalPending,
            // 'ditolak' => $totalDitolak,
            // 'menunggudiketahui' => $totalMenunggudiketahui,
        ]);
    }


    public function pengajuanCuti()
    {
        // $dataPairing = Pairing::getDaftarKaryawanCuti(1)->get();
        // return view('asisten.pengajuan-cuti', [
        //     'dataPairing' => $dataPairing
        // ]);
        $idUser = Auth::user()->id;
        $user = User::find($idUser);

        $idPosisi = User::find($idUser)->karyawan->posisi->id;
        $anggota = Keanggotaan::getAnggota($idPosisi);
        // $anggota = Keanggotaan::where('id_posisi', 4)->get();

        $namaUser = $user->karyawan->nama;
        $jabatan = $user->karyawan->posisi->jabatan;

        $riwayat = PermintaanCuti::getHistoryCuti($idPosisi)->get();

        $idPosisi = $user->karyawan->posisi->id;
        $dataPairing = Keanggotaan::getAnggota($idPosisi);
        $sisaCuti = $dataPairing->each(function ($data) {
            $data->sisa_cuti_panjang = SisaCuti::where('id_karyawan', $data->id)->where('id_jenis_cuti', 1)->first()->jumlah ?? '0';
            $data->sisa_cuti_tahunan = SisaCuti::where('id_karyawan', $data->id)->where('id_jenis_cuti', 2)->first()->jumlah ?? '0';
        });

        return view('asisten.pengajuan-cuti', [
            'riwayats' => $riwayat,
            'dataPairing' => $dataPairing,
            'anggotas' => $anggota,
            'nama' => $namaUser,
            'jabatan' => $jabatan,
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

        // Lanjutkan dengan proses pembuatan permintaan cuti
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

        DB::transaction(function () use ($validate, $startDate, $endDate, $idPosisi, $isChecked, $karyawan) {

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


            RiwayatCuti::create([
                'id_permintaan_cuti' => $permintaanCuti->id,
                'nama_pembuat' => $karyawan->nama,
                'jabatan_pembuat' => $karyawan->posisi->jabatan,
                'periode_cuti_tahunan' => $periode['periode_cuti_tahunan'],
                'periode_cuti_panjang' => $periode['periode_cuti_panjang'],
            ]);

            $nama = Karyawan::find($validate['karyawan'])->nama;
            $message = "Terdapat Permintaan Cuti Baru\n";
            $message .= "Nama: $nama\n";
            $message .= "Tanggal Mulai: $startDate\n";
            $message .= "Tanggal Selesai: $endDate\n";
            $message .= "Alasan: " . $validate['alasan'];

            // Notification::send($user, new SendNotification($message));

            // // Mendefinisikan keyboard inline
            // $keyboard = [
            //     'inline_keyboard' => [
            //         [
            //             ['text' => 'Setujui', 'callback_data' => 'tombol1_data'],
            //             ['text' => 'Tolak', 'callback_data' => 'tombol2_data']
            //         ]
            //     ]
            // ];

            // $pesan = 'Apakah Cuti Disetujui?';

            // // Mengonversi keyboard menjadi JSON
            // $keyboard = json_encode($keyboard);

            // // Kirim pesan dengan keyboard inline
            // $response = file_get_contents("https://api.telegram.org/bot7168138742:AAH7Nlo0YsgvIl4S-DexMsWK34_SOAocfqI/sendMessage?chat_id=1176854977&text=$pesan&reply_markup=$keyboard");
        });

        return redirect()->back()->with('message', 'Permintaan Cuti Berhasil Dibuat!');
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

    public function downloadPermintaanCutiPDF($id)
    {
        // $permintaanCuti = PermintaanCuti::find($id);
        $permintaanCuti = PermintaanCuti::withTrashed()->with(['karyawan' => function ($query) {
            $query->withTrashed();
        }])->find($id);
        // if ($permintaanCuti->karyawan->trashed()) {
        //     return redirect()->back()->with('error_message', 'Data Karyawan tersebut sudah dihapus');
        // }
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

        // $riwayatCuti = RiwayatCuti::where('id_permintaan_cuti', $permintaanCuti->id)->first();
        $riwayatCuti = RiwayatCuti::withTrashed()->with(['permintaanCuti' => function ($query) {
            $query->withTrashed();
        }])->where('id_permintaan_cuti', $permintaanCuti->id)->first();
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


        // Mengambil sisa cuti dari RiwayatCuti
        $sisaCutiPanjang = $riwayatCuti->sisa_cuti_panjang ?? '0';
        $sisaCutiTahunan = $riwayatCuti->sisa_cuti_tahunan ?? '0';

        // Hitung jumlah cuti yang dijalani
        $cutiPanjangDijalani = $permintaanCuti->jumlah_cuti_panjang;
        $cutiTahunanDijalani = $permintaanCuti->jumlah_cuti_tahunan;


        // $cutiPanjangDijalani += $sisaCutiPanjang;
        // $cutiTahunanDijalani += $sisaCutiTahunan;
        // dd($karyawan->posisi->role);
        if ($karyawan->posisi->role->nama_role == 'manajer') {
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

        return $pdf->download('Form Cuti ' . $karyawan->nama . ' .pdf');
    }
}
