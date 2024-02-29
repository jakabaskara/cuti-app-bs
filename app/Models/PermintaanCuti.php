<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PermintaanCuti extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_karyawan',
        'id_jenis_cuti',
        'tgl_mulai',
        'tgl_selesai',
        'jumlah_hari_cuti',
        'alamat',
        'alasan',
        'is_approved',
        'is_rejected',
        'is_checked',
    ];

    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class, 'id_karyawan');
    }

    public function jenisCuti()
    {
        return $this->belongsTo(JenisCuti::class, 'id_jenis_cuti');
    }

    public function riwayatCuti()
    {
        return $this->hasMany(RiwayatCuti::class, 'id_permintaan_cuti');
    }
}