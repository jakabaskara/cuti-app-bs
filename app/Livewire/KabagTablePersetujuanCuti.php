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

        // $permintaanCuti = $pairings->flatMap(function ($pairing) {
        //     // dd($pairing->bawahan->permintaanCuti);
        //     return $pairing->bawahan->karyawan->permintaanCuti;
        //     // return $
        // });

        $permintaanCuti = PermintaanCuti::select('permintaan_cuti.*')
            ->join('karyawan', 'permintaan_cuti.id_karyawan', '=', 'karyawan.id')
            ->join('pairing', 'karyawan.id_posisi', '=', 'pairing.id_bawahan')
            ->where('pairing.id_atasan', $karyawan->id_posisi)
            ->where('permintaan_cuti.is_approved', '=', 0)
            ->get();




        $this->permintaanCuti = $permintaanCuti;
        // $idBawahan = Posisi::find($idAtasan)->atasan->first()->id_bawahan;
        // $this->cutiPendings  = PermintaanCuti::getPending(1)->get();
        return view('livewire.kabag-table-persetujuan-cuti');
    }

    public function setujui($id)
    {
        $dataCuti = PermintaanCuti::find($id);
        DB::transaction(function () use ($dataCuti) {
            // $sisaCutiPanjang = SisaCuti::where('id_karyawan', $dataCuti->id_karyawan->id)->where('id_jenis_cuti', 1)->first()->jumlah ?? '0';
            // $sisaCutiPanjang = SisaCuti::where('id_karyawan', $dataCuti->id_karyawan)->where('id_jenis_cuti', 1)->get()->first();
            // $sisaCutiTahunan = SisaCuti::where('id_karyawan', $dataCuti->id_karyawan)->where('id_jenis_cuti', 2)->get()->first();

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
