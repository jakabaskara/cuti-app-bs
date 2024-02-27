<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PermintaanCuti extends Model
{
    use HasFactory;

    protected $table = 'permintaan_cuti';

    protected $fillable = [
        'id_karyawan',
        'jenis_cuti',
        'alasan',
        'alamat',
        'status',
        'id_unit_kerja',
    ];

    public function unitKerja()
    {
        return $this->belongsTo(UnitKerja::class,'id_unit_kerja');
    }
}
