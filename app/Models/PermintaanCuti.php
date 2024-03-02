<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PermintaanCuti extends Model
{
    use HasFactory;

    protected $table = 'permintaan_cuti';

    protected $fillable = [
        'id_karyawan',
        'id_jenis_cuti',
        'tanggal_mulai',
        'tanggal_selesai',
        'jumlah_hari_cuti',
        'id_pairing',
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

    public static function getPendingCuti()
    {
        $data = self::where('is_approved', '0')->where('is_rejected', '0')->where('is_checked', '1');
        return $data;
    }

    public static function getPendingCutiAtAsisten($id)
    {
        $data = self::where('is_approved', '0')->where('is_rejected', '0')->where('is_checked', '1')->where('id_pairing', $id);
        return $data;
    }
}
