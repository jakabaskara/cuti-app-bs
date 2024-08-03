<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class KaryawanCutiBersama extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'karyawan_cuti_bersama';

    protected $fillable = [
        'id_karyawan',
        'tanggal',
    ];

    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class, 'id_karyawan');
    }
}
