<?php

namespace App\Http\Controllers\manajer;

use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\PDF;
use Illuminate\Http\Request;
use Livewire\Features\SupportFormObjects\Form;

class ManajerDashboardController extends Controller
{
    public function index()
    {
        return view('manajer.index');
    }



    public function tambahCuti(Request $request)
    {

        // $karyawan = Karyawan::where('id', 55)->first();
        // $sisaCuti = SisaCutiPanjang::where('id_karyawan', $karyawan->id)->first();
        // $sisa = $sisaCuti->sisa_cuti;

        // $sisaCuti->sisa_cuti = $sisa - 1;
        // $sisaCuti->save();
        // return redirect()->route('admin.index');

        return view('manajer.index');
    }


}
