<?php

namespace App\Livewire;

use App\Models\PermintaanCuti;
use App\Models\SisaCuti;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Livewire\Component;



class RejectCutiForm extends Component
{
    public $permintaanCuti;


    public $alasan_ditolak;
    public $id;
    public $dataCuti;

    protected $rules = [
        'alasan_ditolak' => 'required',
    ];

    public function tolakCuti()
    {
        $this->validate();
        // Tutup modal setelah cuti ditolak
        $this->emit('closeRejectModal');
    }

    #[On('getCuti')]
    public function getCuti($id)
    {
        $dataCuti = PermintaanCuti::find($id);
        $this->id  = $id;
        $this->dataCuti = $dataCuti;
    }

    #[On('tolak_cuti')]
    public function tolak($id, $pesan)
    {
        $dataCuti = PermintaanCuti::find($id);
        DB::transaction(function () use ($dataCuti, $pesan) {
            $dataCuti->is_approved = 0;
            $dataCuti->is_checked = 1;
            $dataCuti->is_rejected = 1;
            $dataCuti->alasan_ditolak = $pesan;
            $dataCuti->save();
        });
        $this->dispatch('refresh');
        $this->dispatch('tolak');
    }


    public function render()
    {
        return view('livewire.reject-cuti-form');
    }
}
