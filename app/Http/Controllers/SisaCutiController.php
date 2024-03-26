<?php

namespace App\Http\Controllers;

use App\Models\SisaCuti;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use League\CommonMark\Extension\Autolink\UrlAutolinkParser;
use Yajra\DataTables\DataTables;

class SisaCutiController extends Controller
{
    public function index()
    {
        $idUser = Auth::user()->id;
        $user = User::find($idUser);
        $karyawan = $user->karyawan;

        $namaUser = $karyawan->nama;
        $jabatan = $karyawan->posisi->jabatan;

        $sisaCuti = SisaCuti::get();

        return view('admin.sisacuti', [
            'nama' => $namaUser,
            'jabatan' => $jabatan,
            'sisaCutis' => $sisaCuti,
        ]);
    }

    public function sisaCutiData(Request $request)
    {
        return DataTables::of(SisaCuti::with('karyawan.posisi.unitKerja'))
            ->addIndexColumn()
            ->addColumn('NIK', function ($row) {
                return $row->karyawan->NIK;
            })
            ->addColumn('ID', function ($row) {
                return $row->id;
            })
            ->addColumn('Nama', function ($row) {
                return $row->karyawan->nama;
            })
            ->addColumn('UnitKerja', function ($row) {
                return $row->karyawan->posisi->unitKerja->nama_unit_kerja;
            })
            ->addColumn('Posisi', function ($row) {
                return $row->karyawan->posisi->jabatan;
            })
            ->addColumn('PeriodeCuti', function ($row) {
                return date('d M Y', strtotime($row->periode_mulai)) . ' s.d ' . date('d M Y', strtotime($row->periode_akhir));
            })
            ->addColumn('JenisCuti', function ($row) {
                return $row->jenisCuti->jenis_cuti;
            })
            ->addColumn('SisaCuti', function ($row) {
                return $row->jumlah;
            })
            ->setRowClass(function ($row) {
                return 'text-center'; // Menambahkan kelas 'text-center' pada setiap baris
            })
            ->setRowData([
                'data-placement' => 'center', // Menambahkan data-placement 'center' pada setiap baris
            ])
            ->rawColumns(['NIK', 'ID', 'Nama', 'UnitKerja', 'Posisi', 'PeriodeCuti', 'JenisCuti', 'SisaCuti']) // Aktifkan pencarian dan pengurutan untuk setiap kolom
            ->make(true);
    }
}
