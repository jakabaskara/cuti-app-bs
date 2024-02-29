<?php

namespace App\Http\Controllers\asisten;

use App\Http\Controllers\Controller;
use App\Models\PermintaanCuti;
use Illuminate\Http\Request;

class AsistenDashboardController extends Controller
{

    public function index()
    {
        return view('asisten.index');
    }
}
