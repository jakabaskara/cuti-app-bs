<?php

namespace App\Livewire;

use App\Models\PermintaanCuti;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;

class ManajerStatusBarIndex extends Component
{
    public $disetujui;
    public $pending;
    public $ditolak;

    #[On('refresh')]
    public function refresh()
    {
        $idUser = Auth::user()->id;
        $user = User::find($idUser);
        $idPosisi = $user->karyawan->id_posisi;

        $this->disetujui = PermintaanCuti::getDisetujuiCuti($idPosisi)->count();
        $this->pending = PermintaanCuti::getPendingCuti($idPosisi)->count();
        $this->ditolak = PermintaanCuti::getDibatalkanCuti($idPosisi)->count();
    }

    public function mount()
    {
        // parent::__construct();
        $idUser = Auth::user()->id;
        $user = User::find($idUser);
        $idPosisi = $user->karyawan->id_posisi;

        $this->disetujui = PermintaanCuti::getDisetujuiCuti($idPosisi)->count();
        $this->pending = PermintaanCuti::getPendingCuti($idPosisi)->count();
        $this->ditolak = PermintaanCuti::getDibatalkanCuti($idPosisi)->count();
    }

    public function render()
    {

        return view('livewire.manajer-status-bar-index');
    }
}
