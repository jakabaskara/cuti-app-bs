<?php

namespace App\Livewire;

use App\Models\Karyawan;
use App\Models\KaryawanCutiBersama;
use Livewire\Attributes\On;
use Livewire\Component;

class SelectedKaryawanCuti extends Component
{
    public $karyawanSelected;

    public function render()
    {
        return view('livewire.selected-karyawan-cuti');
    }

    #[On('refresh')]
    public function refresh()
    {
        $this->karyawanSelected = null;
    }

    #[On('setSelectedKaryawan')]
    public function getKaryawanSelected($data)
    {
        $karyawan = KaryawanCutiBersama::whereIn('id', $data)->get();

        $this->karyawanSelected = $karyawan;
    }
}
