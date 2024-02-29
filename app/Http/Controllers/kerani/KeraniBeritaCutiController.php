<?php

namespace App\Http\Controllers\kerani;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class KeraniBeritaCutiController extends Controller
{
    public function index()
    {
        return view('kerani.cuti');
    }
}
