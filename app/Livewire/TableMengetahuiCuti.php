<?php

namespace App\Livewire;

use App\Models\PermintaanCuti;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class TableMengetahuiCuti extends Component
{
    public $permintaanCuti;

    public function mount()
    {
        $karyawan = Auth::user()->karyawan;

        $permintaanCuti = PermintaanCuti::select('permintaan_cuti.*')
            ->join('karyawan', 'permintaan_cuti.id_karyawan', '=', 'karyawan.id')
            ->join('pairing', 'karyawan.id_posisi', '=', 'pairing.id_bawahan')
            ->where('pairing.id_atasan', $karyawan->id_posisi)
            ->where('permintaan_cuti.is_approved', '=', 0)
            ->get();

        $this->permintaanCuti = $permintaanCuti;
    }

    public function render()
    {
        return view('livewire.table-mengetahui-cuti');
    }

    public function setujui($id)
    {
        $dataCuti = PermintaanCuti::find($id);
        DB::transaction(function () use ($dataCuti) {

            SisaCuti::where('id_karyawan', $dataCuti->id_karyawan)->where('id_jenis_cuti', 1)->decrement('jumlah', $dataCuti->jumlah_cuti_panjang);
            SisaCuti::where('id_karyawan', $dataCuti->id_karyawan)->where('id_jenis_cuti', 2)->decrement('jumlah', $dataCuti->jumlah_cuti_tahunan);

            if ($dataCuti) {
                $dataCuti->is_approved = 1;
                $dataCuti->is_checked = 1;
                $dataCuti->save();
            }
        });
        $this->dispatch('refresh');
    }
}
