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

    protected $rules = [
        'alasan_ditolak' => 'required',
    ];

    public function tolakCuti()
    {
        $this->validate();
        // Tutup modal setelah cuti ditolak
        $this->emit('closeRejectModal');
    }

    #[On('tolak_cuti')]
    public function tolak($id, $teks)
    {
        $teks = '' ?? '';
        $dataCuti = PermintaanCuti::find($id);
        DB::transaction(function () use ($dataCuti, $teks) {
            $dataCuti->is_approved = 0;
            $dataCuti->is_checked = 1;
            $dataCuti->is_rejected = 1;
            $dataCuti->alasan_ditolak = $teks;
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
