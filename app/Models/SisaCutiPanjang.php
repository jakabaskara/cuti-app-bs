<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SisaCutiPanjang extends Model
{
    use HasFactory;

    protected $table = 'sisa_cuti_panjang';

    protected $fillable = [
        'periode_awal',
        'periode_akhir',
        'sisa_cuti',
        'id_karyawan',
    ];

    public function Karyawan()
    {
        return $this->belongsTo(Karyawan::class, 'id_karyawan');
    }
}
