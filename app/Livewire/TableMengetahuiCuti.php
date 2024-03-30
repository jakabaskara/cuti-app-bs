<?php

namespace App\Livewire;

use App\Models\PermintaanCuti;
use App\Models\RiwayatCuti;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class TableMengetahuiCuti extends Component
{
    public $permintaanCuti;

    public function render()
    {
        $karyawan = Auth::user()->karyawan;

        $permintaanCuti = PermintaanCuti::select('permintaan_cuti.*')
            ->join('karyawan', 'permintaan_cuti.id_karyawan', '=', 'karyawan.id')
            ->join('pairing', 'karyawan.id_posisi', '=', 'pairing.id_bawahan')
            ->where('pairing.id_atasan', $karyawan->id_posisi)
            ->where('permintaan_cuti.is_approved', '=', 0)
            ->where('permintaan_cuti.is_checked', '=', 0)
            ->get();

        $this->permintaanCuti = $permintaanCuti;
        return view('livewire.table-mengetahui-cuti');
    }

    public function setujui($id)
    {
        $karyawan = Auth::user()->karyawan;

        $dataCuti = PermintaanCuti::find($id);
        DB::transaction(function () use ($dataCuti, $karyawan) {

            if ($dataCuti) {
                $dataCuti->is_checked = 1;
                $dataCuti->save();
            }

            $riwayat = RiwayatCuti::where('id_permintaan_cuti', $dataCuti->id)->first();
            $riwayat->nama_checker = $karyawan->nama;
            $riwayat->jabatan_checker = $karyawan->jabatan;
            $riwayat->save();
        });
        $this->dispatch('refresh');
        $this->dispatch('ketahui');
    }
}
