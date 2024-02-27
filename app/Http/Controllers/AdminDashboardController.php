<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\PDF;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function index()
    {
        return view('admin.index');
    }

    public function downloadPermintaanCutiPDF()
    {
        // $pdf = PDF::loadView('')
    }
}
