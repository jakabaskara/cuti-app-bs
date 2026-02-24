<?php

namespace App\Http\Controllers;

use App\Models\Keanggotaan;
use App\Models\Pairing;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminPairingController extends Controller
{

    public $nama;
    public $jabatan;

    public function index()
    {
        $idUser = Auth::user()->id;
        $user = User::find($idUser);
        $karyawan = $user->karyawan;
        $this->nama = $karyawan->nama;
        $this->jabatan = $karyawan->jabatan;

        return view('admin.pairing', [
            'jabatan' => $this->jabatan,
            'nama' => $this->nama,
        ]);
    }

    public function getPairingData(Request $request)
    {
        $perPage = $request->input('per_page', 25);
        $page = $request->input('page', 1);
        $search = $request->input('search', '');

        $pairings = Pairing::with([
            'atasan.unitKerja',
            'atasan.karyawan',
            'bawahan.karyawan'
        ])->get();

        $flattenedData = [];
        foreach ($pairings as $pairing) {
            $unitKerja = $pairing->atasan->unitKerja->nama_unit_kerja ?? '';
            $atasanNama = $pairing->atasan->karyawan->first()->nama ?? '';
            $atasanJabatan = $pairing->atasan->karyawan->first()->jabatan ?? '';
            $atasanPosisi = $pairing->atasan->jabatan ?? '';
            
            foreach ($pairing->bawahan->karyawan as $karyawan) {
                $flattenedData[] = [
                    'unit_kerja' => $unitKerja,
                    'atasan_nama' => $atasanNama,
                    'atasan_jabatan' => $atasanJabatan,
                    'atasan_posisi' => $atasanPosisi,
                    'bawahan_nama' => $karyawan->nama ?? '',
                    'bawahan_jabatan' => $karyawan->jabatan ?? '',
                    'bawahan_posisi' => $pairing->bawahan->jabatan ?? '',
                ];
            }
        }

        if ($search) {
            $flattenedData = array_values(array_filter($flattenedData, function($item) use ($search) {
                return stripos($item['unit_kerja'], $search) !== false ||
                       stripos($item['atasan_nama'], $search) !== false ||
                       stripos($item['atasan_jabatan'], $search) !== false ||
                       stripos($item['atasan_posisi'], $search) !== false ||
                       stripos($item['bawahan_nama'], $search) !== false ||
                       stripos($item['bawahan_jabatan'], $search) !== false ||
                       stripos($item['bawahan_posisi'], $search) !== false;
            }));
        }

        $total = count($flattenedData);
        $offset = ($page - 1) * $perPage;
        $paginatedData = array_slice($flattenedData, $offset, $perPage);

        return response()->json([
            'data' => array_values($paginatedData),
            'current_page' => $page,
            'per_page' => $perPage,
            'total' => $total,
            'last_page' => ceil($total / $perPage)
        ]);
    }

    public function keanggotaan()
    {
        $idUser = Auth::user()->id;
        $user = User::find($idUser);
        $karyawan = $user->karyawan;
        $this->nama = $karyawan->nama;
        $this->jabatan = $karyawan->jabatan;

        return view('admin.keanggotaan', [
            'jabatan' => $this->jabatan,
            'nama' => $this->nama,
        ]);
    }

    public function getKeanggotaanData(Request $request)
    {
        $perPage = $request->input('per_page', 25);
        $page = $request->input('page', 1);
        $search = $request->input('search', '');

        $keanggotaans = Keanggotaan::with([
            'posisi.unitKerja',
            'posisi.karyawan',
            'anggota.karyawan'
        ])->get();

        $flattenedData = [];
        foreach ($keanggotaans as $keanggotaan) {
            $unitKerja = $keanggotaan->posisi->unitKerja->nama_unit_kerja ?? '';
            $picNama = $keanggotaan->posisi->karyawan->first()->nama ?? '';
            $picJabatan = $keanggotaan->posisi->karyawan->first()->jabatan ?? '';
            $picPosisi = $keanggotaan->posisi->jabatan ?? '';
            
            foreach ($keanggotaan->anggota->karyawan as $karyawan) {
                $flattenedData[] = [
                    'unit_kerja' => $unitKerja,
                    'pic_nama' => $picNama,
                    'pic_jabatan' => $picJabatan,
                    'pic_posisi' => $picPosisi,
                    'anggota_nama' => $karyawan->nama ?? '',
                    'anggota_jabatan' => $karyawan->jabatan ?? '',
                    'anggota_posisi' => $keanggotaan->anggota->jabatan ?? '',
                ];
            }
        }

        if ($search) {
            $flattenedData = array_values(array_filter($flattenedData, function($item) use ($search) {
                return stripos($item['unit_kerja'], $search) !== false ||
                       stripos($item['pic_nama'], $search) !== false ||
                       stripos($item['pic_jabatan'], $search) !== false ||
                       stripos($item['pic_posisi'], $search) !== false ||
                       stripos($item['anggota_nama'], $search) !== false ||
                       stripos($item['anggota_jabatan'], $search) !== false ||
                       stripos($item['anggota_posisi'], $search) !== false;
            }));
        }

        $total = count($flattenedData);
        $offset = ($page - 1) * $perPage;
        $paginatedData = array_slice($flattenedData, $offset, $perPage);

        return response()->json([
            'data' => array_values($paginatedData),
            'current_page' => $page,
            'per_page' => $perPage,
            'total' => $total,
            'last_page' => ceil($total / $perPage)
        ]);
    }
}
