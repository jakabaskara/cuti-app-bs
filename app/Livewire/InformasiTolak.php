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
        $keterangan = PermintaanCuti::withTrashed()->with(['karyawan' => function ($query) {
            $query->withTrashed();
        }])->find($id);
        // $this->keterangan = $keterangan->alasan_ditolak;
        if ($keterangan) {
            $this->keterangan = $keterangan->alasan_ditolak;
        } else {
            // Handling jika data tidak ditemukan
            $this->keterangan = "Alasan Cuti Ditolak Tidak Diberikan";
        }
    }

    public function render()
    {
        return view('livewire.informasi-tolak');
    }
}
