<?php

namespace App\Livewire;

use App\Models\Pairing;
use App\Models\PermintaanCuti;
use App\Models\Posisi;
use App\Models\SisaCuti;
use Illuminate\Support\Facades\DB;
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

    public function setujui($id)
    {
        $dataCuti = PermintaanCuti::find($id);
        DB::transaction(function () use ($dataCuti) {

            $sisaCuti = SisaCuti::where('id_karyawan', $dataCuti->id_karyawan)->where('id_jenis_cuti', $dataCuti->id_jenis_cuti)->get()->first();
            $sisaCuti->jumlah -= $dataCuti->jumlah_hari_cuti;
            $sisaCuti->save();

            $dataCuti->is_approved = 1;
            $dataCuti->is_checked = 1;
            $dataCuti->save();
        });
    }
}
