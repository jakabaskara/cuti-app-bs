<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Keanggotaan extends Model
{
    use HasFactory;

    protected $table = 'keanggotaan';

    protected $fillable = [
        'id_posisi',
        'id_anggota',
    ];

    public function posisi()
    {
        return $this->belongsTo(Posisi::class, 'id_posisi');
    }

    public function anggota()
    {
        return $this->belongsTo(Posisi::class, 'id_anggota');
    }

    public static function getAnggota($idPosisi)
    {
        $karyawan = Karyawan::with('keanggotaan')
            ->whereHas('keanggotaan', function ($query) use ($idPosisi) {
                $query->where('id_posisi', $idPosisi);
            })
            ->get();

        return $karyawan;
    }
}
