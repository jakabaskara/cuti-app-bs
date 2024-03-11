<?php

namespace App\Livewire;

use App\Models\Keanggotaan;
use App\Models\SisaCuti;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;

class KabagDaftarSisaCuti extends Component
{

    public $sisaCutis;

    #[On('refresh')]
    public function refresh()
    {
        $idUser = Auth::user()->id;
        $user = User::find($idUser);
        $karyawan = $user->karyawan;
        $idPosisi = $karyawan->posisi->id;

        $dataPairing = Keanggotaan::getAnggota($idPosisi);

        $this->sisaCutis = $dataPairing->each(function ($data) {
            $data->sisa_cuti_panjang = SisaCuti::where('id_karyawan', $data->id)->where('id_jenis_cuti', 1)->first()->jumlah ?? '0';
            $data->sisa_cuti_tahunan = SisaCuti::where('id_karyawan', $data->id)->where('id_jenis_cuti', 2)->first()->jumlah ?? '0';

            return $data;
        });
    }

    public function mount()
    {
        $idUser = Auth::user()->id;
        $user = User::find($idUser);
        $karyawan = $user->karyawan;
        $idPosisi = $karyawan->posisi->id;

        $dataPairing = Keanggotaan::getAnggota($idPosisi);

        $this->sisaCutis = $dataPairing->each(function ($data) {
            $data->sisa_cuti_panjang = SisaCuti::where('id_karyawan', $data->id)->where('id_jenis_cuti', 1)->first()->jumlah ?? '0';
            $data->sisa_cuti_tahunan = SisaCuti::where('id_karyawan', $data->id)->where('id_jenis_cuti', 2)->first()->jumlah ?? '0';

            return $data;
        });
    }

    public function render()
    {
        return view('livewire.kabag-daftar-sisa-cuti');
    }
}
