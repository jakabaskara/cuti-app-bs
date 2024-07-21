<?php

namespace App\Livewire;

use App\Models\PermintaanCuti;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;

class AsistenStatusBarIndex extends Component
{
    public $disetujui;
    public $pending;
    public $ditolak;
    public $menungguDiketahui;

    public $setuju;
    public $tunggu;
    public $tolak;
    public $belumDiketahui;

    public $totalDisetujui;
    public $totalPending;
    public $totalDitolak;
    public $totalMenunggudiketahui;


    #[On('refresh')]
    public function refresh()
    {
        $idUser = Auth::user()->id;
        $user = User::find($idUser);
        $idPosisi = $user->karyawan->id_posisi;

        // Mengambil data untuk status bar pertama (Daftar Pengajuan Cuti) cuti dibuat
        $this->disetujui = PermintaanCuti::getDisetujui($idPosisi);
        $this->pending = PermintaanCuti::getPending($idPosisi);
        $this->ditolak = PermintaanCuti::getDitolak($idPosisi);
        $this->menungguDiketahui = PermintaanCuti::getMenunggudiketahui($idPosisi);

        // Mengambil data untuk status bar kedua (Riwayat Persetujuan Cuti) cuti dibuat orang lain
        $this->setuju = PermintaanCuti::getDisetujuiCuti($idPosisi)->count();
        $this->tunggu = PermintaanCuti::getPendingCuti($idPosisi)->count();
        $this->tolak = PermintaanCuti::getDibatalkanCuti($idPosisi)->count();
        $this->belumDiketahui = PermintaanCuti::getMenungguPersetujuan($idPosisi)->count();

        // Menghitung total dari kedua set status bar
        // $totalDisetujui = $disetujui + $setuju;
        // $totalPending = $pending + $tunggu;
        // $totalDitolak = $ditolak + $tolak;
        // $totalMenunggudiketahui = $menungguDiketahui + $belumDiketahui;

        $this->totalDisetujui = $this->disetujui + $this->setuju;
        $this->totalPending = $this->pending + $this->tunggu;
        $this->totalDitolak = $this->ditolak + $this->tolak;
        $this->totalMenunggudiketahui = $this->menungguDiketahui + $this->belumDiketahui;
    }

    public function mount()
    {
        $idUser = Auth::user()->id;
        $user = User::find($idUser);
        $idPosisi = $user->karyawan->id_posisi;

        // Mengambil data untuk status bar pertama (Daftar Pengajuan Cuti) cuti dibuat
        $this->disetujui = PermintaanCuti::getDisetujui($idPosisi);
        $this->pending = PermintaanCuti::getPending($idPosisi);
        $this->ditolak = PermintaanCuti::getDitolak($idPosisi);
        $this->menungguDiketahui = PermintaanCuti::getMenunggudiketahui($idPosisi);

        // Mengambil data untuk status bar kedua (Riwayat Persetujuan Cuti) cuti dibuat orang lain
        $this->setuju = PermintaanCuti::getDisetujuiCuti($idPosisi)->count();
        $this->tunggu = PermintaanCuti::getPendingCuti($idPosisi)->count();
        $this->tolak = PermintaanCuti::getDibatalkanCuti($idPosisi)->count();
        $this->belumDiketahui = PermintaanCuti::getMenungguPersetujuan($idPosisi)->count();

        // Menghitung total dari kedua set status bar
        // $totalDisetujui = $disetujui + $setuju;
        // $totalPending = $pending + $tunggu;
        // $totalDitolak = $ditolak + $tolak;
        // $totalMenunggudiketahui = $menungguDiketahui + $belumDiketahui;

        $this->totalDisetujui = $this->disetujui + $this->setuju;
        $this->totalPending = $this->pending + $this->tunggu;
        $this->totalDitolak = $this->ditolak + $this->tolak;
        $this->totalMenunggudiketahui = $this->menungguDiketahui + $this->belumDiketahui;
    }


    public function render()
    {
        return view('livewire.asisten-status-bar-index');
    }
}
