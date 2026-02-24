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
        // Eager load relationships to optimize performance
        $user = User::with(['karyawan.posisi.unitKerja'])->find($idUser);
        $karyawan = $user->karyawan;
        $idPosisi = $karyawan->posisi->id;

        $riwayat = PermintaanCuti::getHistoryCuti($idPosisi)->get();
        $namaUser = $user->karyawan->nama;
        $jabatan = $user->karyawan->posisi->jabatan;

        // Ensure we get a collection
        $dataPairing = Keanggotaan::getAnggota($idPosisi);
        // Check if dataPairing is a builder instance (not a collection), if so, get results
        if ($dataPairing instanceof \Illuminate\Database\Eloquent\Builder || $dataPairing instanceof \Illuminate\Database\Query\Builder) {
            $dataPairing = $dataPairing->get();
        }

        $isKebun = $karyawan->posisi->unitKerja->is_kebun;

        // Optimasi: Ambil semua ID karyawan dari dataPairing
        $karyawanIds = $dataPairing->pluck('id')->toArray();

        // Ambil semua data sisa cuti untuk karyawan-karyawan tersebut sekaligus (Eager Loading manual)
        $allSisaCuti = SisaCuti::whereIn('id_karyawan', $karyawanIds)
            ->whereIn('id_jenis_cuti', [1, 2])
            ->get()
            ->groupBy('id_karyawan');

        // Map data sisa cuti ke dataPairing tanpa query database dalam loop (N+1 fixed)
        $sisaCuti = $dataPairing->each(function ($data) use ($allSisaCuti) {
            $cutiKaryawan = $allSisaCuti->get($data->id);

            // Cuti Panjang (id_jenis_cuti = 1)
            $cutiPanjang = $cutiKaryawan ? $cutiKaryawan->where('id_jenis_cuti', 1)->first() : null;
            $data->sisa_cuti_panjang = $cutiPanjang->jumlah ?? '0';
            $data->jatuh_tempo_panjang = $cutiPanjang;

            // Cuti Tahunan (id_jenis_cuti = 2)
            $cutiTahunan = $cutiKaryawan ? $cutiKaryawan->where('id_jenis_cuti', 2)->first() : null;
            $data->sisa_cuti_tahunan = $cutiTahunan->jumlah ?? '0';
            $data->jatuh_tempo_tahunan = $cutiTahunan;
        });

        $isKandir = $karyawan->posisi->unitKerja->nama_unit_kerja == 'Region Office' ? true : false;
        $username = $user->username;


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
            'username' => $username,
            'is_kebun' => $isKebun,
            'menunggudiketahui' => $getMenunggudiketahui,
        ]);
    }

    public function submitCuti(Request $request)
    {
        $request->validate([
            'karyawan' => 'required',
            'alasan' => 'required',
            'alamat' => 'required',
            'jumlahHariCuti' => 'required|min:1|numeric',
        ]);

        $idKaryawan = $request->karyawan;
        $tanggalCutiTahunan = $request->tanggal_cuti_tahunan; // e.g. "2025-01-10 to 2025-01-14"
        $tanggalCutiPanjang = $request->tanggal_cuti_panjang;
        $jumlahCutiTahunan = (int) $request->jumlah_cuti_tahunan;
        $jumlahCutiPanjang = (int) $request->jumlah_cuti_panjang;

        // Pastikan setidaknya satu tanggal diisi
        if (empty($tanggalCutiTahunan) && empty($tanggalCutiPanjang)) {
            return redirect()->back()->with('error_message', 'Harap pilih setidaknya satu tanggal cuti!');
        }

        // Validasi sisa cuti
        $sisaTahunan = SisaCuti::where('id_karyawan', $idKaryawan)->where('id_jenis_cuti', 2)->first()->jumlah ?? 0;
        $sisaPanjang = SisaCuti::where('id_karyawan', $idKaryawan)->where('id_jenis_cuti', 1)->first()->jumlah ?? 0;

        if (!empty($tanggalCutiTahunan) && $jumlahCutiTahunan > $sisaTahunan) {
            return redirect()->back()->with('error_message', 'Jumlah cuti tahunan melebihi sisa cuti tahunan!');
        }
        if (!empty($tanggalCutiPanjang) && $jumlahCutiPanjang > $sisaPanjang) {
            return redirect()->back()->with('error_message', 'Jumlah cuti panjang melebihi sisa cuti panjang!');
        }

        // Parse tanggal tahunan
        $startTahunan = $endTahunan = null;
        if (!empty($tanggalCutiTahunan)) {
            if (strpos($tanggalCutiTahunan, ' to ') !== false) {
                [$startTahunan, $endTahunan] = explode(' to ', $tanggalCutiTahunan);
            } else {
                $startTahunan = $endTahunan = $tanggalCutiTahunan;
            }
            $startTahunan = date('Y-m-d', strtotime($startTahunan));
            $endTahunan = date('Y-m-d', strtotime($endTahunan));
        }

        // Parse tanggal panjang
        $startPanjang = $endPanjang = null;
        if (!empty($tanggalCutiPanjang)) {
            if (strpos($tanggalCutiPanjang, ' to ') !== false) {
                [$startPanjang, $endPanjang] = explode(' to ', $tanggalCutiPanjang);
            } else {
                $startPanjang = $endPanjang = $tanggalCutiPanjang;
            }
            $startPanjang = date('Y-m-d', strtotime($startPanjang));
            $endPanjang = date('Y-m-d', strtotime($endPanjang));
        }

        // Hitung rentang gabungan untuk pengecekan konflik dan penyimpanan
        $allStarts = array_filter([$startTahunan, $startPanjang]);
        $allEnds = array_filter([$endTahunan, $endPanjang]);
        $startDate = min($allStarts);
        $endDate = max($allEnds);

        $idUser = Auth::user()->id;
        $user = User::find($idUser);
        $idPosisi = $user->karyawan->id_posisi;
        $karyawan = $user->karyawan;

        // Cek apakah ada permintaan cuti yang belum diproses
        $pendingCuti = PermintaanCuti::where('id_karyawan', $idKaryawan)
            ->where('is_approved', 0)
            ->where('is_rejected', 0)
            ->exists();

        if ($pendingCuti) {
            return redirect()->back()->with('error_message', 'Terdapat Permintaan Cuti Yang Belum Diproses!');
        }

        // Cek konflik tanggal dengan data yang sudah ada
        $existingCuti = PermintaanCuti::where('id_karyawan', $idKaryawan)
            ->where(function ($query) use ($startDate, $endDate) {
                $query->where(function ($q) use ($startDate, $endDate) {
                    $q->where('tanggal_mulai', '<=', $startDate)
                        ->where('tanggal_selesai', '>=', $startDate);
                })->orWhere(function ($q) use ($startDate, $endDate) {
                    $q->where('tanggal_mulai', '<=', $endDate)
                        ->where('tanggal_selesai', '>=', $endDate);
                })->orWhere(function ($q) use ($startDate, $endDate) {
                    $q->where('tanggal_mulai', '>=', $startDate)
                        ->where('tanggal_selesai', '<=', $endDate);
                });
            })
            ->where('is_rejected', 0)
            ->exists();

        if ($existingCuti) {
            return redirect()->back()->with('error_message', 'Cuti sudah ada pada rentang tanggal tersebut!');
        }

        $isManager = Karyawan::find($idKaryawan)->posisi->role->nama_role == 'manajer' ? true : false;
        $isKebun = Posisi::isKebun($idPosisi);
        if ($isManager == true) {
            $isManager = 1;
        } elseif ($isKebun == 1) {
            $isManager = 0;
        } else {
            $isManager = 1;
        }
        $isChecked = $isManager ? 0 : 1;

        DB::transaction(function () use ($request, $idKaryawan, $startDate, $endDate, $jumlahCutiTahunan, $jumlahCutiPanjang, $idPosisi, $isChecked, $karyawan, $user) {

            $permintaanCuti = PermintaanCuti::create([
                'id_karyawan' => $idKaryawan,
                'tanggal_mulai' => $startDate,
                'tanggal_selesai' => $endDate,
                'jumlah_cuti_panjang' => $jumlahCutiPanjang,
                'jumlah_cuti_tahunan' => $jumlahCutiTahunan,
                'alamat' => $request->alamat,
                'alasan' => $request->alasan,
                'id_posisi_pembuat' => $idPosisi,
                'is_approved' => 0,
                'is_rejected' => 0,
                'is_checked' => $isChecked,
            ]);

            $karyawan_req = Karyawan::find($idKaryawan);
            $periodeCuti = SisaCuti::where('id_karyawan', $karyawan_req->id)->get();

            $periode = $periodeCuti->flatMap(function ($data) {
                if ($data->id_jenis_cuti == 1) {
                    $tanggal['periode_cuti_panjang'] = date('Y', strtotime($data->periode_mulai))."/".date('Y', strtotime($data->periode_akhir));
                } elseif ($data->id_jenis_cuti == 2) {
                    $tanggal['periode_cuti_tahunan'] = date('Y', strtotime($data->periode_mulai))."/".date('Y', strtotime($data->periode_akhir));
                } else {
                    $tanggal['periode_cuti_panjang'] = '';
                    $tanggal['periode_cuti_tahunan'] = '';
                }
                return $tanggal;
            });

            RiwayatCuti::create([
                'id_permintaan_cuti' => $permintaanCuti->id,
                'nama_pembuat' => $karyawan->nama,
                'jabatan_pembuat' => $karyawan->posisi->jabatan,
                'periode_cuti_tahunan' => $periode['periode_cuti_tahunan'] ?? '',
                'periode_cuti_panjang' => $periode['periode_cuti_panjang'] ?? '',
            ]);
        });

        return redirect()->back()->with('message', 'Permintaan Cuti Berhasil Dibuat!');
    }

    public function downloadPermintaanCutiPDF($id)
    {
        // $permintaanCuti = PermintaanCuti::find($id);
        $permintaanCuti = PermintaanCuti::withTrashed()->with([
            'karyawan' => function ($query) {
                $query->withTrashed();
            }
        ])->find($id);
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
        $riwayatCuti = RiwayatCuti::withTrashed()->with([
            'permintaanCuti' => function ($query) {
                $query->withTrashed();
            }
        ])->where('id_permintaan_cuti', $permintaanCuti->id)->first();
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
        return $pdf->download('Form Cuti '.$karyawan->nama.' tanggal '.$permintaanCuti->tanggal_mulai.' .pdf');
    }

    public function delete($id)
    {
        DB::transaction(function () use ($id) {
            // Force delete related RiwayatCuti records
            $riwayatPermintaanCuti = RiwayatCuti::where('id_permintaan_cuti', $id);
            $riwayatPermintaanCuti->forceDelete();

            // Force delete PermintaanCuti record
            $permintaanCuti = PermintaanCuti::find($id);
            $permintaanCuti->forceDelete();
        });

        return redirect()->back()->with('error_message', 'Data Berhasil Dihapus');
    }

    public function sendNoti()
    {
        $users = Auth::user();
        Notification::send($users, new SendNotification('ss'));
    }
}
