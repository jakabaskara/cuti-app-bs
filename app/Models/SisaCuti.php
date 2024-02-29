<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SisaCuti extends Model
{
    use HasFactory;

    protected $table = 'sisa_cuti';

    protected $fillable = [
        'id_karyawan',
        'id_jenis_cuti',
        'periode_mulai',
        'periode_akhir',
        'jumlah_sisa_cuti',
    ];

    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class, 'id_karyawan');
    }

    public function jenisCuti()
    {
        return $this->belongsTo(JenisCuti::class, 'id_jenis_cuti');
    }
}
