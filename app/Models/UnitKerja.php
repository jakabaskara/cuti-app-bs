<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UnitKerja extends Model
{
    use HasFactory;

    protected $table = 'unit_kerja';

    protected $fillable = [
        'nama_unit_kerja',
        'bagian',
        'kode_unit_kerja',
    ];

    public function posisi()
    {
        return $this->hasMany(Posisi::class, 'id_unit_kerja');
    }
}
