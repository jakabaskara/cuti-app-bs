<?php

namespace App\Livewire;

use App\Models\PermintaanCuti;
use Carbon\Carbon;
use Livewire\Attributes\On;
use Livewire\Component;

class ModalKalender extends Component
{

    public $tanggal;
    public $dataCutis;

    public function render()
    {
        return view('livewire.modal-kalender');
    }

    #[On('wait-tanggal')]
    public function setTanggal($tanggal)
    {
        $today = Carbon::now()->toDateString();

        $data = PermintaanCuti::select('permintaan_cuti.*')
            ->join('karyawan', 'permintaan_cuti.id_karyawan', '=', 'karyawan.id')
            ->join('posisi', 'karyawan.id_posisi', '=', 'posisi.id')
            ->join('unit_kerja', 'posisi.id_unit_kerja', '=', 'unit_kerja.id')
            ->where('is_approved', 1)
            ->whereDate('tanggal_mulai', '<=', $tanggal)
            ->whereDate('tanggal_selesai', '>=', $tanggal)
            ->get();

        $this->dataCutis = $data;
    }
}
