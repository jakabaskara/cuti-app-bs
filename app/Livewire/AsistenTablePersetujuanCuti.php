<?php

namespace App\Livewire;

use App\Models\PermintaanCuti;
use Livewire\Component;

class AsistenTablePersetujuanCuti extends Component
{
    public $cutiPendings;


    public function render()
    {
        $this->cutiPendings  = PermintaanCuti::getPendingCuti()->get();
        return view('livewire.asisten-table-persetujuan-cuti');
    }
}
