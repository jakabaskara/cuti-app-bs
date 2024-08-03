<?php

namespace App\Livewire;

use App\Models\PermintaanCuti;
use Livewire\Component;

class TableRiwayatCuti extends Component
{
    public $dataCutis;
    public function mount()
    {
        // $dataCuti = PermintaanCuti::where('is_approved', 1)->orderBy('id', 'DESC')->get();
        // $this->dataCutis = $dataCuti;




        $dataCuti = PermintaanCuti::withTrashed()->with(['karyawan' => function ($query) {
            $query->withTrashed();
        }])->where('is_approved', 1)->orderBy('id', 'DESC')->get();
        $this->dataCutis = $dataCuti;


    }

    public function render()
    {
        return view('livewire.table-riwayat-cuti');
    }
}
