<?php

namespace App\Livewire;

use App\Models\Pairing;
use App\Models\PermintaanCuti;
use App\Models\Posisi;
use Livewire\Component;

class KabagTablePersetujuanCuti extends Component
{

    public $permintaanCuti;

    public function render()
    {
        $idAtasan = 2;
        $pairings = Pairing::where('id_atasan', $idAtasan)->get();

        $permintaanCuti = $pairings->flatMap(function ($pairing) {
            return $pairing->bawahan->permintaanCuti->where('is_approved', 0);
        });

        $this->permintaanCuti = $permintaanCuti;
        // $idBawahan = Posisi::find($idAtasan)->atasan->first()->id_bawahan;
        // $this->cutiPendings  = PermintaanCuti::getPending(1)->get();
        return view('livewire.kabag-table-persetujuan-cuti');
    }
}
