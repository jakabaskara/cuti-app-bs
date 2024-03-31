<?php

namespace App\Livewire;

use App\Models\PermintaanCuti;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;

class KaryawanCutiTable extends Component
{
    public $karyawanCuti;

    public function render()
    {
        $idUser = Auth::user()->id;
        $user = User::find($idUser);
        $idPosisi = $user->karyawan->posisi->id_unit_kerja;
        $this->karyawanCuti = PermintaanCuti::getBagianKaryawanCuti($idPosisi);


        return view('livewire.karyawan-cuti-table');
    }

    #[On('refresh')]
    public function refresh()
    {
        $idUser = Auth::user()->id;
        $user = User::find($idUser);
        $idPosisi = $user->karyawan->posisi->id_unit_kerja;
        $this->karyawanCuti = PermintaanCuti::getBagianKaryawanCuti($idPosisi);
    }
}
