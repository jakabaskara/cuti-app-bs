<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Karyawan extends Model
{
    use HasFactory;

    // Definisikan nama table nya
    protected $table = 'karyawan';

    // masukan nama nama attribut table yang bisa di isi
    protected $fillable = [
        'NIK',
        'nama',
        'level',
        'TMT_bekerja',
        'tgl_diangkat_staff',
        'id_unit_kerja',
        'id_users',
    ];

    // Relasi
    public function unitKerja()
    {
        return $this->belongsTo(UnitKerja::class, 'id_unit_kerja');
    }
}
