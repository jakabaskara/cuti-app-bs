<?php

namespace App\Livewire;

use App\Models\CutiBersama;
use App\Models\KaryawanCutiBersama;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;

class DaftarCutiBersamaTable extends Component
{
    public $daftarCuti;
    public $checkKaryawan = [];

    public function render()
    {
        return view('livewire.daftar-cuti-bersama-table');
    }

    #[On('changeDate')]
    public function setDaftarCuti($tanggal)
    {
        // $karyawan = KaryawanCutiBersama::where('tanggal', $tanggal)->get();

        $idUser = Auth::user()->id;
        $user = User::find($idUser);
        $karyawan = $user->karyawan;

        $karyawanCuti = KaryawanCutiBersama::join('karyawan', 'karyawan_cuti_bersama.id_karyawan', '=', 'karyawan.id')
            ->join('posisi', 'karyawan.id_posisi', '=', 'posisi.id')
            ->join('keanggotaan', 'posisi.id', '=', 'keanggotaan.id_anggota')
            ->where('keanggotaan.id_posisi', $karyawan->id_posisi)
            ->where('karyawan_cuti_bersama.tanggal', $tanggal)
            ->select('karyawan_cuti_bersama.*')
            ->get();

        $this->daftarCuti = $karyawanCuti;
    }

    public function setHadir()
    {
        $this->dispatch('setHadir');
    }
}
