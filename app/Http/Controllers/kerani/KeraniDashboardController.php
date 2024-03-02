<?php

namespace App\Http\Controllers\kerani;

use App\Http\Controllers\Controller;
use App\Models\Pairing;
use Illuminate\Http\Request;

class KeraniDashboardController extends Controller
{
    public function index()
    {
        $dataPairing = Pairing::getDaftarKaryawanCuti(1)->get();
        return view('kerani.index', [
            'dataPairing' => $dataPairing,
        ]);
    }
}
