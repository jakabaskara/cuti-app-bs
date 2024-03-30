<?php

namespace App\Livewire;

use App\Models\Pairing;
use App\Models\PermintaanCuti;
use App\Models\Posisi;
use App\Models\RiwayatCuti;
use App\Models\SisaCuti;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class GmTablePersetujuanCuti extends Component
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
        return view('livewire.g-m-persetujuan-cuti');
    }

    public function setujui($id)
    {
        $karyawan = Auth::user()->karyawan;

        $dataCuti = PermintaanCuti::find($id);
        DB::transaction(function () use ($dataCuti, $karyawan) {
            $cutiPanjang = SisaCuti::where('id_karyawan', $dataCuti->id_karyawan)->where('id_jenis_cuti', 1)->first();
            $cutiTahunan = SisaCuti::where('id_karyawan', $dataCuti->id_karyawan)->where('id_jenis_cuti', 2)->first();

            $totalPermintaanCuti = $dataCuti->jumlah_cuti_tahunan + $dataCuti->jumlah_cuti_panjang;
            $totalSisaCuti = ($cutiPanjang ? $cutiPanjang->jumlah : 0) + ($cutiTahunan ? $cutiTahunan->jumlah : 0);

            if ($totalSisaCuti >= $totalPermintaanCuti) {

                if ($cutiPanjang && $cutiPanjang->jumlah > 0) {
                    $cutiPanjang->jumlah -= $dataCuti->jumlah_cuti_panjang;
                    $cutiPanjang->save();
                }

                if ($cutiTahunan && $cutiTahunan->jumlah > 0) {
                    $cutiTahunan->jumlah -= $dataCuti->jumlah_cuti_tahunan;
                    $cutiTahunan->save();
                }

                if ($dataCuti) {
                    $dataCuti->is_approved = 1;
                    $dataCuti->is_checked = 1;
                    $dataCuti->save();
                    $this->dispatch('terima');
                }

                $riwayat = RiwayatCuti::where('id_permintaan_cuti', $dataCuti->id)->first();
                $riwayat->nama_approver = $karyawan->nama;
                $riwayat->jabatan_approver = $karyawan->jabatan;
                $riwayat->nik_approver = $karyawan->NIK;
                $riwayat->sisa_cuti_panjang = $cutiPanjang ? $cutiPanjang->jumlah : 0;
                $riwayat->sisa_cuti_tahunan = $cutiTahunan ? $cutiTahunan->jumlah : 0;
                $riwayat->save();
            } else {
                $this->dispatch('cutiKurang');
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
