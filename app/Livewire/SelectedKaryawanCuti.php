<?php

namespace App\Livewire;

use App\Models\Karyawan;
use Livewire\Attributes\On;
use Livewire\Component;

class SelectedKaryawanCuti extends Component
{
    public $karyawanSelected;

    public function render()
    {
        return view('livewire.selected-karyawan-cuti');
    }

    #[On('setSelectedKaryawan')]
    public function getKaryawanSelected($data)
    {
        $dataKaryawan = [];
        $karyawan = Karyawan::whereIn('id', $data)->get();

        $this->karyawanSelected = $karyawan;
    }
}
