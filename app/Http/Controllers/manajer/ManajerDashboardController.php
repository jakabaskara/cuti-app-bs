<?php

namespace App\Http\Controllers\manajer;

use App\Http\Controllers\Controller;
use App\Models\Pairing;
use Barryvdh\DomPDF\PDF;
use Illuminate\Http\Request;
use Livewire\Features\SupportFormObjects\Form;

class ManajerDashboardController extends Controller
{
    public function index()
    {
        return view('manajer.index');
    }

    
    public function pengajuanCuti()
    {
        $dataPairing = Pairing::getDaftarKaryawan(1)->get();
        return view('manajer.pengajuan-cuti', [
            'dataPairing' => $dataPairing
        ]);
    }


}
