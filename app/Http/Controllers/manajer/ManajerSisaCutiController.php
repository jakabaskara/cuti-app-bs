<?php

namespace App\Http\Controllers\manajer;

use App\Http\Controllers\Controller;
use App\Models\SisaCuti;
use Illuminate\Http\Request;

class ManajerSisaCutiController extends Controller
{
    public function index()
    {
       return view('manajer.sisacuti');
    }

    //tess
}
