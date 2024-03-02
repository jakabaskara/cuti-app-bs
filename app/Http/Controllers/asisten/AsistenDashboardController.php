<?php

namespace App\Http\Controllers\asisten;

use App\Http\Controllers\Controller;
use App\Models\Pairing;
use App\Models\PermintaanCuti;
use Illuminate\Http\Request;

class AsistenDashboardController extends Controller
{

    public function index()
    {
        return view('asisten.index');
    }

    public function pengajuanCuti()
    {
        $dataPairing = Pairing::getDaftarKaryawanCuti(1)->get();
        return view('asisten.pengajuan-cuti', [
            'dataPairing' => $dataPairing
        ]);
    }
}
