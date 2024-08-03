<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RiwayatCuti extends Model
{
    use HasFactory;
    use SoftDeletes;

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
        'nik_checker',
        'nik_approver',
        'periode_cuti_panjang',
        'periode_cuti_tahunan',
    ];

    public function permintaanCuti()
    {
        return $this->belongsTo(PermintaanCuti::class, 'id_permintaan_cuti');
    }
}
