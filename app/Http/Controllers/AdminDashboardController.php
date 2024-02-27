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
        $karyawans = Karyawan::where('id_unit_kerja', 1)->with('sisaCutiPanjang')->get();
        return view('admin.index', [
            'karyawans' => $karyawans,
        ]);
    }

    public function downloadPermintaanCutiPDF()
    {
        $pdf = PDF::loadView('form');
        return $pdf->download('pdf.pdf');
    }
}
