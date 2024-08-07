<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Karyawan extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'karyawan';

    protected $fillable = [
        'nik',
        'nama',
        'jabatan',
        'tmt_bekerja',
        'tgl_diangkat_staf',
        'id_posisi',
    ];

    public function posisi()
    {
        return $this->belongsTo(Posisi::class, 'id_posisi');
    }

    public function sisaCuti()
    {
        return $this->hasMany(SisaCuti::class, 'id_karyawan');
    }

    public function users()
    {
        return $this->hasMany(User::class, 'id_karyawan');
    }

    public function permintaanCuti()
    {
        return $this->hasMany(PermintaanCuti::class, 'id_karyawan');
    }

    public function keanggotaan()
    {
        return $this->belongsTo(Keanggotaan::class, 'id_posisi', 'id_anggota');
    }

    public function pairing()
    {
        return $this->belongsTo(Pairing::class, 'id_posisi', 'id_bawahan');
    }

    public function karyawanCutiBersama()
    {
        return $this->hasMany(KaryawanCutiBersama::class, 'id_karyawan');
    }


    public function riwayatCuti()
    {
        return $this->hasMany(RiwayatCuti::class);
    }

    // public function getKeanggotaan(){
    //     self::
    // }
}
