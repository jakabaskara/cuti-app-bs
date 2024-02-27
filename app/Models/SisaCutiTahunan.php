<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SisaCutiTahunan extends Model
{
    use HasFactory;

    protected $table = 'sisa_cuti_tahunan';

    protected $fillable = [
        'periode_awal',
        'periode_akhir',
        'sisa_cuti',
        'id_karyawan',
    ];

    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class, 'id_karyawan');
    }


}
