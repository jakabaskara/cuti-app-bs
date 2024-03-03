<?php

namespace App\Livewire;

use App\Models\Karyawan;
use App\Models\Keanggotaan;
use App\Models\SisaCuti;
use Livewire\Component;

class KeraniSisaCutiModal extends Component
{

    public $sisaCuti;

    public function render()
    {
        return view('livewire.kerani-sisa-cuti-modal');
    }

    public function setSisaCuti($id)
    {

        $dataPairing = Karyawan::find($id);
        $sisaCuti = SisaCuti::where('id_karyawan', $dataPairing);
        $this->sisaCuti = $sisaCuti;
    }
}
