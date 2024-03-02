<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pairing extends Model
{
    use HasFactory;

    protected $table = 'Pairing';

    protected $fillable = [
        'id_atasan',
        'id_bawahan',
    ];

    public function atasan()
    {
        return $this->belongsTo(Posisi::class, 'id_atasan');
    }

    public function bawahan()
    {
        return $this->belongsTo(Posisi::class, 'id_bawahan');
    }

    public function permintaanCuti()
    {
        return $this->hasMany(PermintaanCuti::class, 'id_pairing');
    }

    public static function getDaftarKaryawanCuti($idAtasan)
    {
        return self::where('id_atasan', $idAtasan);
    }
}
