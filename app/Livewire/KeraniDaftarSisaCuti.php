<?php

namespace App\Livewire;

use App\Models\SisaCuti;
use Livewire\Component;

class KeraniDaftarSisaCuti extends Component
{
    public $cutiPendings;


    public function render()
    {
        // $idAtasan = 1;
        // $this->cutiPendings  = SisaCuti::getPendingCuti(1)->get();
        return view('livewire.kerani-daftar-sisa-cuti');
    }

    public function setujui($id)
    {
        $dataCuti = SisaCuti::find($id);
        $dataCuti->is_approved = 1;
        $dataCuti->is_checked = 1;
        $dataCuti->save();
    }
}
