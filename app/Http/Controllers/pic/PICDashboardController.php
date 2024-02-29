<?php

namespace App\Http\Controllers\pic;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PICDashboardController extends Controller
{
    public function index()
    {
        return view('pic.index');
    }
}
