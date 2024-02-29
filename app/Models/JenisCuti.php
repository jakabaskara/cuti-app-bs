<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisCuti extends Model
{
    use HasFactory;

    protected $table = 'jenis_cuti';

    protected $fillable = [
        'jenis_cuti',
    ];

    public function sisaCuti()
    {
        return $this->hasMany(SisaCuti::class, 'id_jenis_cuti');
    }

    public function permintaanCuti()
    {
        return $this->hasMany(PermintaanCuti::class, 'id_jenis_cuti');
    }
}
