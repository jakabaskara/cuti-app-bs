<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiwayatCuti extends Model
{
    use HasFactory;

    protected $table = 'riwayat_cuti';

    protected $fillable = [
        'id_permintaan_cuti',
        'nama_approver',
        'nama_checker',
        'nama_pembuat',
        'jabatan_approver',
        'jabatan_checker',
        'jabatan_pembuat',
        'approval_date',
        'checked_date',
        'sisa_cuti_tahunan',
        'sisa_cuti_panjang',
    ];

    public function PermintaanCuti()
    {
        return $this->belongsTo(PermintaanCuti::class, 'id_permintaan_cuti');
    }
}
