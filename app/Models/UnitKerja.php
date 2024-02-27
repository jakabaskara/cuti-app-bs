<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UnitKerja extends Model
{
    use HasFactory;

    protected $table = 'unit_kerja';

    protected $fillable = [
        'unit_kerja',
    ];

    public function karyawan()
    {
        return $this->hasMany(Karyawan::class, 'id_unit_kerja');
    }

    public function permintaanCuti()
    {
        return $this->hasMany(PermintaanCuti::class, 'id_unit_kerja');
    }
}
