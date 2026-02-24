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

    public $jumlahHariTahunan = 0;
    public $jumlahHariPanjang = 0;

    public function mount()
    {
        // nothing
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

        $this->jumlahHariTahunan = 0;
        $this->jumlahHariPanjang = 0;
    }

    #[On('setJumlahHariTahunan')]
    public function setJumlahHariTahunan($jumlahHari)
    {
        $this->jumlahHariTahunan = $jumlahHari;
    }

    #[On('setJumlahHariPanjang')]
    public function setJumlahHariPanjang($jumlahHari)
    {
        $this->jumlahHariPanjang = $jumlahHari;
    }

    public function setCuti()
    {
        $this->dispatch('setCuti');
    }
}
