<?php

namespace App\Http\Controllers\manajer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ManajerDashboardController extends Controller
{
    public function index()
    {
        return view('manajer.index');
    }
}
