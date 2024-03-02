<?php

namespace App\Http\Controllers\admin;

use App\Models\Karyawan;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminKaryawanController extends Controller
{   
       public function index()
    {
        $karyawan = Karyawan::all();
        return view('admin.karyawan',['karyawan' => $karyawan]);
    }
}
