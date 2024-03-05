<?php

namespace App\Livewire;

use App\Models\Keanggotaan;
use App\Models\SisaCuti;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class KeraniDaftarSisaCuti extends Component
{
    public $cutiPendings;
    public $dataPairing;
    public $namaKaryawan;

    public $sisaCutiPanjang;
    public $sisaCutiTahunan;


    public function mount()
    {
        $idUser = Auth::user()->id;
        $user = User::find($idUser);
        $idPosisi = $user->karyawan->posisi->id;
        $this->dataPairing = Keanggotaan::getAnggota($idPosisi);
    }

    public function setNama()
    {
        $sisaCutiPanjang = SisaCuti::where('id_karyawan', $this->namaKaryawan)->where('id_jenis_cuti', 1)->first()->jumlah ?? 0;
        $sisaCutiTahunan = SisaCuti::where('id_karyawan', $this->namaKaryawan)->where('id_jenis_cuti', 2)->first()->jumlah ?? 0;
        $this->sisaCutiPanjang = $sisaCutiPanjang;
        $this->sisaCutiTahunan = $sisaCutiTahunan;
    }

    public function render()
    {
        // $this->namaKaryawan = $this->namaKaryawan;
        // $this->namaKaryawan;
        // $idAtasan = 1;
        // $this->cutiPendings  = SisaCuti::getPendingCuti(1)->get();
        return view('livewire.kerani-daftar-sisa-cuti');
    }

    public function getSisaCuti()
    {
        $idUser = Auth::user()->id;
        $user = User::find($idUser);
        $idPosisi = $user->karyawan->posisi->id;
        $dataPairing = Keanggotaan::getAnggota($idPosisi);
        $sisaCuti = $dataPairing->each(function ($data) {
            $data->sisa_cuti_panjang = SisaCuti::where('id_karyawan', $data->id)->where('id_jenis_cuti', 1)->first()->jumlah ?? '0';
            $data->sisa_cuti_tahunan = SisaCuti::where('id_karyawan', $data->id)->where('id_jenis_cuti', 2)->first()->jumlah ?? '0';
        });
    }

    public function setujui($id)
    {
        $dataCuti = SisaCuti::find($id);
        $dataCuti->is_approved = 1;
        $dataCuti->is_checked = 1;
        $dataCuti->save();
    }
}
