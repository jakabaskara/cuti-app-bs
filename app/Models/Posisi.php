<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Posisi extends Model
{
    use HasFactory;

    protected $table = 'posisi';

    protected $fillable = [
        'id_unit_kerja',
        'id_role',
        'jabatan',
    ];

    public function role()
    {
        return $this->belongsTo(Role::class, 'id_role');
    }


    public function unitKerja()
    {
        return $this->belongsTo(UnitKerja::class, 'id_unit_kerja');
    }

    public function karyawan()
    {
        return $this->hasMany(Karyawan::class, 'id_posisi');
    }

    public function atasan()
    {
        return $this->hasMany(Pairing::class, 'id_atasan');
    }

    public function bawahan()
    {
        return $this->hasMany(Pairing::class, 'id_bawahan');
    }
}
