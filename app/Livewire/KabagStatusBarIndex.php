<?php

namespace App\Livewire;

use App\Models\PermintaanCuti;
use Livewire\Component;

class KabagStatusBarIndex extends Component
{
    public $disetujui;
    public $pending;
    public $ditolak;

    public function render()
    {
        $idUser = Auth::user()->id;
        $user = User::find($idUser);
        $idPosisi = $user->karyawan->id_posisi;

        $this->disetujui = PermintaanCuti::getDisetujuiCuti($idPosisi)->count();
        $this->pending = PermintaanCuti::getPendingCuti($idPosisi)->count();
        $this->ditolak = PermintaanCuti::getDibatalkanCuti($idPosisi);
        return view('livewire.kabag-status-bar-index');
    }
}
