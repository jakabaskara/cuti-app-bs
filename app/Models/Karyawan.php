<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Karyawan extends Model
{
    use HasFactory;

    protected $table = 'karyawan';

    protected $fillable = [
        'nik',
        'nama_karyawan',
        'jabatan',
        'tmt_bekerja',
        'tgl_diangkat_start',
        'id_posisi',
    ];

    public function posisi(){
        return $this->belongsTo(Posisi::class, 'id_posisi');
    }

    public function SisaCuti(){
        return $this->hasMany(SisaCuti::class, 'id_karyawan');
    }

    public function users(){
        return $this->hasMany(Users::class, 'id_karyawan');
    }

    public function PermintaanCuti(){
        return $this->hasMany(PermintaanCuti::class, 'id_karyawan');
    }
}
