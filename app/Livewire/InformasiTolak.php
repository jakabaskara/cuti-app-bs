<?php

namespace App\Livewire;

use App\Models\PermintaanCuti;
use Livewire\Attributes\On;
use Livewire\Component;

class InformasiTolak extends Component
{
    public $keterangan;
    
    #[On('setKeterangan')]
    public function setKeterangan($id)
    {
        $keterangan = PermintaanCuti::find($id);
        $this->keterangan = $keterangan->alasan_ditolak;
    }

    public function render()
    {
        return view('livewire.informasi-tolak');
    }
}
