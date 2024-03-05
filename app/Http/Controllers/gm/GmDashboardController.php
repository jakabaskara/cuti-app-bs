<?php

namespace App\Http\Controllers\gm;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class GmDashboardController extends Controller
{
    public function index()
    {
        return view(index);
    }
}
