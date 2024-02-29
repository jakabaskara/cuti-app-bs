<?php

namespace App\Http\Controllers;

use App\Models\Karyawan;
use App\Models\SisaCutiPanjang;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // $karyawans = Karyawan::where('id_unit_kerja', 1)->with('sisaCutiPanjang')->get();
        // return view('admin.index', [
        //     'karyawans' => $karyawans,
        // ]);

        return view('admin.index');
    }

    public function downloadPermintaanCutiPDF()
    {
        $pdf = PDF::loadView('form');
        return $pdf->download('pdf.pdf');
    }

    public function tambahCuti(Request $request)
    {

        // $karyawan = Karyawan::where('id', 55)->first();
        // $sisaCuti = SisaCutiPanjang::where('id_karyawan', $karyawan->id)->first();
        // $sisa = $sisaCuti->sisa_cuti;

        // $sisaCuti->sisa_cuti = $sisa - 1;
        // $sisaCuti->save();
        // return redirect()->route('admin.index');

        return view('admin.index');
    }
}
