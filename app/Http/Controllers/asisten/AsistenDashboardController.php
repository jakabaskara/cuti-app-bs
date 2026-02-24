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

        $isKandir = $user->karyawan->posisi->unitKerja->nama_unit_kerja == 'Region Office' ? true : false;
        $username = $user->username;

        return view('asisten.pengajuan-cuti', [
            'riwayats' => $riwayat,
            'dataPairing' => $dataPairing,
            'anggotas' => $anggota,
            'nama' => $namaUser,
            'jabatan' => $jabatan,
            'isKandir' => $isKandir,
            'username' => $username,
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
        $tanggalCutiTahunan = $request->tanggal_cuti_tahunan;
        $tanggalCutiPanjang = $request->tanggal_cuti_panjang;
        $jumlahCutiTahunan = (int) $request->jumlah_cuti_tahunan;
        $jumlahCutiPanjang = (int) $request->jumlah_cuti_panjang;

        if (empty($tanggalCutiTahunan) && empty($tanggalCutiPanjang)) {
            return redirect()->back()->with('error_message', 'Harap pilih setidaknya satu tanggal cuti!');
        }

        $sisaTahunan = SisaCuti::where('id_karyawan', $idKaryawan)->where('id_jenis_cuti', 2)->first()->jumlah ?? 0;
        $sisaPanjang = SisaCuti::where('id_karyawan', $idKaryawan)->where('id_jenis_cuti', 1)->first()->jumlah ?? 0;

        if (!empty($tanggalCutiTahunan) && $jumlahCutiTahunan > $sisaTahunan) {
            return redirect()->back()->with('error_message', 'Jumlah cuti tahunan melebihi sisa cuti tahunan!');
        }
        if (!empty($tanggalCutiPanjang) && $jumlahCutiPanjang > $sisaPanjang) {
            return redirect()->back()->with('error_message', 'Jumlah cuti panjang melebihi sisa cuti panjang!');
        }

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

        $allStarts = array_filter([$startTahunan, $startPanjang]);
        $allEnds = array_filter([$endTahunan, $endPanjang]);
        $startDate = min($allStarts);
        $endDate = max($allEnds);

        $idUser = Auth::user()->id;
        $user = User::find($idUser);
        $idPosisi = $user->karyawan->id_posisi;
        $karyawan = $user->karyawan;

        $pendingCuti = PermintaanCuti::where('id_karyawan', $idKaryawan)
            ->where('is_approved', 0)
            ->where('is_rejected', 0)
            ->exists();

        if ($pendingCuti) {
            return redirect()->back()->with('error_message', 'Terdapat Permintaan Cuti Yang Belum Diproses!');
        }

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
        $isChecked = $isManager ? 0 : 1;

        DB::transaction(function () use ($request, $idKaryawan, $startDate, $endDate, $jumlahCutiTahunan, $jumlahCutiPanjang, $idPosisi, $isChecked, $karyawan) {

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

        return $pdf->download('Form Cuti '.$karyawan->nama.' .pdf');
    }
}
