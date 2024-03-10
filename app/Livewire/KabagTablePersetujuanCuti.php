<?php

namespace App\Livewire;

use App\Models\Pairing;
use App\Models\PermintaanCuti;
use App\Models\Posisi;
use App\Models\SisaCuti;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class KabagTablePersetujuanCuti extends Component
{

    public $permintaanCuti;

    public function render()
    {
        $karyawan = Auth::user()->karyawan;

        $pairings = Pairing::where('id_atasan', $karyawan->id_posisi)->get();

        $permintaanCuti = $pairings->flatMap(function ($pairing) {
            return $pairing->bawahan->permintaanCuti->where('is_approved', 0)->where('is_rejected', 0);
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

            $sisaCutiPanjang = SisaCuti::where('id_karyawan', $dataCuti->id_karyawan)->where('id_jenis_cuti', 1)->get()->first();
            $sisaCutiTahunan = SisaCuti::where('id_karyawan', $dataCuti->id_karyawan)->where('id_jenis_cuti', 2)->get()->first();

            if ($sisaCutiPanjang) {
                $sisaCutiPanjang->jumlah -= $dataCuti->jumlah_cuti_panjang;
                $sisaCutiPanjang->save();
            } else if ($sisaCutiTahunan) {
                $sisaCutiTahunan->jumlah -= $dataCuti->jumlah_cuti_tahunan;
                $sisaCutiTahunan->save();
            }
            if ($dataCuti) {
                $dataCuti->is_approved = 1;
                $dataCuti->is_checked = 1;
                $dataCuti->save();
            }
        });
        $this->dispatch('refresh', []);
    }

    public function tolak($id)
    {
        $dataCuti = PermintaanCuti::find($id);
        DB::transaction(function () use ($dataCuti) {
            $dataCuti->is_approved = 0;
            $dataCuti->is_checked = 1;
            $dataCuti->is_rejected = 1;
            $dataCuti->save();
        });

        $this->dispatch('refresh');
    }
}
