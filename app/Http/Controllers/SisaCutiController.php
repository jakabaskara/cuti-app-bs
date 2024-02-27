<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SisaCutiController extends Controller
{
    public function index(){
        return view('admin.sisacuti');
    }
}
