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
        return $this->belongsTo(Posisi::class, 'id_posisi_pembuat');
    }

    public static function getPendingCuti($idPosisi)
    {
        $pairings = Pairing::where('id_atasan', $idPosisi)->get();

        $permintaanCuti = $pairings->flatMap(function ($pairing) {
            return $pairing->bawahan->permintaanCuti->where('is_approved', 0)->where('is_rejected', 0);
        });
        return $permintaanCuti;
    }

    public static function getDisetujuiCuti($idAtasan)
    {
        $pairings = Pairing::where('id_atasan', $idAtasan)->get();

        $permintaanCuti = $pairings->flatMap(function ($pairing) {
            return $pairing->bawahan->permintaanCuti->where('is_approved', 1);
        });
        return $permintaanCuti;
    }

    public static function getDibatalkanCuti($idAtasan)
    {
        $pairings = Pairing::where('id_atasan', $idAtasan)->get();

        $permintaanCuti = $pairings->flatMap(function ($pairing) {
            return $pairing->bawahan->permintaanCuti->where('is_rejected', 1);
        });
        return $permintaanCuti;
    }

    public static function getPendingCutiAtAsisten($id)
    {
        $data = self::where('is_approved', '0')->where('is_rejected', '0')->where('is_checked', '1')->where('id_pairing', $id);
        return $data;
    }

    public static function getHistoryCuti($idPosisi)
    {
        $data = self::where('id_posisi_pembuat', $idPosisi)->orderBy('id', 'DESC');
        return $data;
    }

    public static function getDisetujui($idPosisi)
    {
        $data = self::where('id_posisi_pembuat', $idPosisi)->where('is_approved', 1)->count();
        return $data;
    }

    public static function getDitolak($idPosisi)
    {
        $data = self::where('id_posisi_pembuat', $idPosisi)->where('is_rejected', 1)->count();
        return $data;
    }

    public static function getPending($idPosisi)
    {
        $data = self::where('id_posisi_pembuat', $idPosisi)->where('is_approved', 0)->where('is_rejected', 0)->count();
        return $data;
    }

    public static function getTodayKaryawanCuti($idPosisi)
    {
        $data = self::where('id_posisi_pembuat', $idPosisi)->where('is_approved', 1)->where('tanggal_mulai', date('Y-m-d'))->get();

        return $data;
    }

}
