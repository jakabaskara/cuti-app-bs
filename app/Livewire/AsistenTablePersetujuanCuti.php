<?php

namespace App\Livewire;

use App\Models\PermintaanCuti;
use Livewire\Component;

class AsistenTablePersetujuanCuti extends Component
{
    public $cutiPendings;


    public function render()
    {
        $idAtasan = 1;
        $this->cutiPendings  = PermintaanCuti::getPendingCuti(1)->get();
        return view('livewire.asisten-table-persetujuan-cuti');
    }

    public function setujui($id)
    {
        $dataCuti = PermintaanCuti::find($id);
        $dataCuti->is_approved = 1;
        $dataCuti->is_checked = 1;
        $dataCuti->save();
    }
}
