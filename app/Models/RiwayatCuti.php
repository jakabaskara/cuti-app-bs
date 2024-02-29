<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiwayatCuti extends Model
{
    use HasFactory;

    protected $table = 'riwayat_cuti';

    protected $fillable = [
        'nama_approver',
        'nama_checker',
        'jabatan_approver',
        'jabatan_checker',
        'approval_date',
        'checked_date',
    ];

    public function PermintaanCuti(){
        return $this->belongsTo(PermintaanCuti::class, 'id_permintaan_cuti');
    }
}
