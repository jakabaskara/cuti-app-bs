<?php

namespace App\Livewire;

use App\Models\PermintaanCuti;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;

class KabagDaftarRiwayatCuti extends Component
{

    public $permintaanCutis;

    #[On('refresh')]
    public function refresh()
    {
        $idUser = Auth::user()->id;
        $user = User::find($idUser);
        $karyawan = $user->karyawan;
        $idUnitKerja = $karyawan->posisi->id_unit_kerja;
        $this->permintaanCutis = PermintaanCuti::whereHas('posisi', function ($query) use ($idUnitKerja) {
            $query->where('id_unit_kerja', $idUnitKerja);
        })->orderBy('id', 'DESC')->get();
    }

    public function mount()
    {
        $idUser = Auth::user()->id;
        $user = User::find($idUser);
        $karyawan = $user->karyawan;
        $idUnitKerja = $karyawan->posisi->id_unit_kerja;
        $this->permintaanCutis = PermintaanCuti::whereHas('posisi', function ($query) use ($idUnitKerja) {
            $query->where('id_unit_kerja', $idUnitKerja);
        })->orderBy('id', 'DESC')->get();
    }

    public function render()
    {
        return view('livewire.kabag-daftar-riwayat-cuti');
    }
}
