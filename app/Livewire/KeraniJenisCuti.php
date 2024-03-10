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

    public $cutiDiambil;
    public $sisaCuti;

    public $cutiPanjangDiambil;
    public $cutiTahunanDiambil;
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

        $this->cutiTahunanDiambil = 0;
        $this->cutiPanjangDiambil = 0;
    }

    #[On('setJumlahHariCuti')]
    public function validasiCuti($daysDifference, $totalCuti)
    {
        // daydif = cuti di ambil
        // totalCuti = sisa cuti keseluruhan


        if ($daysDifference > $totalCuti) {
            $this->cutiPanjangDiambil = 0;
            $this->cutiTahunanDiambil = 0;
            $this->dispatch('errorCuti');
        } else {
            if ($daysDifference > $this->sisaCutiTahunan) {
                $this->cutiTahunanDiambil = $this->sisaCutiTahunan;
                $this->cutiPanjangDiambil = $daysDifference - $this->sisaCutiTahunan;
            } else {
                $this->cutiTahunanDiambil = $daysDifference;
                $this->cutiPanjangDiambil = 0;
            }
        }
    }

    public function setCuti()
    {
        $this->dispatch('setCuti');
    }
}
