<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SisaCuti extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'sisa_cuti';

    protected $fillable = [
        'id_karyawan',
        'id_jenis_cuti',
        'periode_mulai',
        'periode_akhir',
        'jumlah',
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
