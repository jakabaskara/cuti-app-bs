<?php

namespace App\Livewire;

use App\Models\PermintaanCuti;
use App\Models\SisaCuti;
use Illuminate\Support\Facades\DB;
use Livewire\Component;



class RejectCutiForm extends Component
{
    public $permintaanCuti;

    
    public $alasan_ditolak;

    protected $rules = [
        'alasan_ditolak' => 'required',
    ];

    public function tolakCuti()
    {
        $this->validate();

        // Lakukan proses penolakan cuti disini, sesuaikan dengan kebutuhan Anda
        // Misalnya, Anda dapat mengirim email notifikasi atau melakukan penanganan lainnya.
        
        // Kemudian, reset alasan penolakan setelah proses penolakan selesai
        $this->reset('alasan_ditolak');

        // Tutup modal setelah cuti ditolak
        $this->emit('closeRejectModal');
    }
    
    public function tolak($id)
    {
        $dataCuti = PermintaanCuti::find($id);
        DB::transaction(function () use ($dataCuti) {
            $dataCuti->is_approved = 0;
            $dataCuti->is_checked = 1;
            $dataCuti->is_rejected = 1;
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
