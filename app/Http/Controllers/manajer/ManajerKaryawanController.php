<?php

namespace App\Http\Controllers\manajer;

use App\Http\Controllers\Controller;
use App\Models\Karyawan;
use Illuminate\Http\Request;

class ManajerKaryawanController extends Controller
{
    public function index()
    {
        return view('manajer.karyawan');
    }

  
}
