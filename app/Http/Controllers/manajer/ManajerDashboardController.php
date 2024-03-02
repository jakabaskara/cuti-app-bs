<?php

namespace App\Http\Controllers\manajer;

use App\Http\Controllers\Controller;
use App\Models\Karyawan;

use App\Models\PermintaanCuti;
use Barryvdh\DomPDF\PDF;
use Illuminate\Contracts\Support\ValidatedData;
use Illuminate\Http\Request;
use Livewire\Attributes\Validate;
use Livewire\Features\SupportFormObjects\Form;

class ManajerDashboardController extends Controller
{
    public function index()
    {
        return view('manajer.index');
    }

}
