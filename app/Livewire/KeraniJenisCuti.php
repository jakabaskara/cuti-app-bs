<?php

namespace App\Livewire;

use App\Models\JenisCuti;
use Livewire\Attributes\On;
use Livewire\Component;

class KeraniJenisCuti extends Component
{

    public $idKaryawan;
    public $jenisCuti;
    public $sisaCutiPanjang;
    public $sisaCutiTahunan;

    public function mount()
    {
        // $this->jenisCuti = JenisCuti::get();
    }

    public function render()
    {
        return view('livewire.kerani-jenis-cuti');
    }

    #[On('setNama')]
    public function getJenisCuti($sisaCutiPanjang, $sisaCutiTahunan)
    {
        $this->sisaCutiPanjang = $sisaCutiPanjang;
        $this->sisaCutiTahunan = $sisaCutiTahunan;
    }

    public function setCuti()
    {
        $this->dispatch('setCuti');
    }
}
