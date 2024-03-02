<?php

namespace App\Livewire;

use App\Models\JenisCuti;
use App\Models\Pairing;
use App\Models\PermintaanCuti;
use Livewire\Component;

class AsistenModalAddCuti extends Component
{

    public $dataPairing;
    public $karyawan = '';
    public $jenis_cuti = '';
    public $tanggal_cuti = '';
    public $jumlah_cuti = '';
    public $jumlah_hari = '';
    public $alasan = '';
    public $alamat = '';
    public $showModal = false;
    public $jenisCuti;


    public function render()
    {
        $this->jenisCuti = JenisCuti::get();
        $this->dataPairing = Pairing::getDaftarKaryawanCuti(1)->get();
        return view('livewire.asisten-modal-add-cuti');
    }

    public function save()
    {

        // Validasi input

        // Proses penyimpanan data atau operasi lainnya
        // ...

        // Emit event untuk menutup modal
        $this->dispatch('modalClosed');

        // Redirect setelah validasi berhasil dan data disimpan
        return redirect()->route('asisten.pengajuan-cuti');

        // return $this->redirect('/pengajuan-cuti');

        // PermintaanCuti::create([
        //     'id_karyawan' => $validate['karyawan'],
        //     'id_jenis_cuti' => $validate['jenis_cuti'],
        //     'tangg'
        // ]);
    }
}
