<?php

namespace App\Livewire;

use App\Models\Karyawan;
use Livewire\Attributes\On;
use Livewire\Component;

class Profile extends Component
{
    // public $keterangan;
    
    // #[On('setKeterangan')]
    // public function setKeterangan($id)
    // {
    //     $keterangan = Karyawan::find($id);
    //     $this->keterangan = $keterangan->nik;
    //     $this->keterangan = $keterangan->nama;
    //     $this->keterangan = $keterangan->jabatan;
    //     $this->keterangan = $keterangan->tgl_diangkat_staf;
    // }

    public $nik;
    public $nama;
    public $jabatan;
    public $tgl_diangkat_staf;

    public function mount($id)
    {
        $karyawan = Karyawan::find($id);
        if ($karyawan) {
            $this->nik = $karyawan->nik;
            $this->nama = $karyawan->nama;
            $this->jabatan = $karyawan->jabatan;
            $this->tgl_diangkat_staf = $karyawan->tgl_diangkat_staf;
        }
    }
    
    
    public function render()
    {
        return view('livewire.profile');
    }
}
