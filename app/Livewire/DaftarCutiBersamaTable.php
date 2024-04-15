<?php

namespace App\Livewire;

use App\Models\CutiBersama;
use App\Models\KaryawanCutiBersama;
use Livewire\Attributes\On;
use Livewire\Component;

class DaftarCutiBersamaTable extends Component
{
    public $daftarCuti;

    public function render()
    {
        return view('livewire.daftar-cuti-bersama-table');
    }

    #[On('changeDate')]
    public function setDaftarCuti($tanggal)
    {
        $karyawan = KaryawanCutiBersama::where('tanggal', $tanggal)->get();

        $this->daftarCuti = $karyawan;
    }
}
