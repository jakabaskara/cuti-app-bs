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
        'id_posisi_pembuat',
        'alamat',
        'alasan',
        'alasan_ditolak',
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

    public function posisi()
    {
        return $this->belongsTo(Posisi::class, 'id_pairing');
    }

    public static function getPendingCuti($idAtasan)
    {
        $data = self::where('is_approved', '0')->where('is_rejected', '0')->where('is_checked', '1')->whereHas('pairing', function ($query) use ($idAtasan) {
            $query->where('id_atasan', $idAtasan)->where('id_atasan', '!=', $idAtasan);
        });
        return $data;
    }

    public static function getPendingCutiAtAsisten($id)
    {
        $data = self::where('is_approved', '0')->where('is_rejected', '0')->where('is_checked', '1')->where('id_pairing', $id);
        return $data;
    }

    public static function getHistoryCuti($idAtasan)
    {
        $data = self::whereHas('pairing', function ($query) use ($idAtasan) {
            $query->where('id_atasan', $idAtasan);
        })->get()->sortByDesc('id');

        return $data;
    }
}
