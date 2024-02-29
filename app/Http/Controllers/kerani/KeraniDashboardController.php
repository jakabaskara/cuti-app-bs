<?php

namespace App\Http\Controllers\kerani;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class KeraniDashboardController extends Controller
{
    public function index()
    {
        return view('kerani.index');
    }
}
