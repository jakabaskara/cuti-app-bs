<?php

namespace App\Livewire;

use App\Models\PermintaanCuti;
use App\Models\UnitKerja;
use App\Models\User;

use Carbon\Carbon;
use Livewire\Attributes\On;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;


class ModalKalenderKerani extends Component
{

    // public $tanggal;
    // public $dataCutis;

    // public function render()
    // {
    //     return view('livewire.modal-kalender-kerani');
    // }

    // #[On('wait-tanggal')]

    // public function setTanggal($tanggal)
    // {
    //     $idUser = Auth::user()->id;
    //     $user = User::with(['karyawan.posisi'])->find($idUser); // Eager load untuk menghindari N+1 problem

    //     if (!$user || !$user->karyawan || !$user->karyawan->posisi) {
    //         abort(404, 'Data karyawan atau posisi tidak ditemukan.');
    //     }

    //     // $unitKerjaId = $user->karyawan->posisi->id_unit_kerja; // Ambil ID unit kerja dari posisi karyawan
    //     $nama_unit_kerja = $user->karyawan->posisi->unitKerja->nama_unit_kerja; // Ambil nama unit kerja dari posisi karyawan


    //     $data = PermintaanCuti::select('permintaan_cuti.*')
    //         ->join('karyawan', 'permintaan_cuti.id_karyawan', '=', 'karyawan.id')
    //         ->join('posisi', 'karyawan.id_posisi', '=', 'posisi.id')
    //         ->join('unit_kerja', 'posisi.id_unit_kerja', '=', 'unit_kerja.id')
    //         ->where('is_approved', 1)
    //         // ->where('unit_kerja.id', $unitKerjaId)
    //         ->where('unit_kerja.nama_unit_kerja', $nama_unit_kerja) // Filter berdasarkan nama unit kerja pengguna
    //         ->whereDate('tanggal_mulai', '<=', $tanggal)
    //         ->whereDate('tanggal_selesai', '>=', $tanggal)
    //         ->get();

    //     $this->dataCutis = $data;
    // }

    public $tanggal;
    public $dataCutis;

    public function render()
    {
        return view('livewire.modal-kalender-kerani');
    }

    #[On('wait-tanggal')]
    public function setTanggal($tanggal)
    {
        $idUser = Auth::user()->id;
        $user = User::with(['karyawan.posisi'])->find($idUser); // Eager load untuk menghindari N+1 problem

        if (!$user || !$user->karyawan || !$user->karyawan->posisi) {
            abort(404, 'Data karyawan atau posisi tidak ditemukan.');
        }

        $idPosisi = $user->karyawan->posisi->id;

        $data = PermintaanCuti::select('permintaan_cuti.*')
            ->join('karyawan', 'permintaan_cuti.id_karyawan', '=', 'karyawan.id')
            ->join('posisi', 'karyawan.id_posisi', '=', 'posisi.id')
            ->join('unit_kerja', 'posisi.id_unit_kerja', '=', 'unit_kerja.id')
            ->where('is_approved', 1)
            ->where('id_posisi_pembuat', $idPosisi)
            ->whereDate('tanggal_mulai', '<=', $tanggal)
            ->whereDate('tanggal_selesai', '>=', $tanggal)
            ->get();

        $this->dataCutis = $data;
    }
}
