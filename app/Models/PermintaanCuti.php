<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PermintaanCuti extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'permintaan_cuti';

    protected $fillable = [
        'id_karyawan',
        'tanggal_mulai',
        'tanggal_selesai',
        'jumlah_cuti_panjang',
        'jumlah_cuti_tahunan',
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

    public function jenisPermintaanCuti()
    {
        return $this->hasMany(JenisCuti::class, 'id_jenis_permintaan_cuti');
    }

    public function riwayatCuti()
    {
        return $this->hasMany(RiwayatCuti::class, 'id_permintaan_cuti');
    }

    public function posisi()
    {
        return $this->belongsTo(Posisi::class, 'id_posisi_pembuat');
    }

    public static function getRiwayatCuti($idAtasan)
    {
        $permintaanCuti = PermintaanCuti::withTrashed()->with(['karyawan' => function ($query) {
            $query->withTrashed();
        }])->select('permintaan_cuti.*')
            ->join('karyawan', 'permintaan_cuti.id_karyawan', '=', 'karyawan.id')
            ->join('pairing', 'karyawan.id_posisi', '=', 'pairing.id_bawahan')
            ->where('pairing.id_atasan', $idAtasan)
            ->orderBy('permintaan_cuti.id', 'DESC')
            ->get();

        return $permintaanCuti;
    }

    public static function getPendingCuti($idPosisi)
    {
        // $pairings = Pairing::where('id_atasan', $idPosisi)->get();

        $permintaanCuti = PermintaanCuti::withTrashed()->with(['karyawan' => function ($query) {
            $query->withTrashed();
        }])->select('permintaan_cuti.*')
            ->join('karyawan', 'permintaan_cuti.id_karyawan', '=', 'karyawan.id')
            ->join('pairing', 'karyawan.id_posisi', '=', 'pairing.id_bawahan')
            ->where('pairing.id_atasan', $idPosisi)
            ->where('permintaan_cuti.is_approved', '=', 0)
            ->where('permintaan_cuti.is_rejected', '=', 0)
            ->where('permintaan_cuti.is_checked', '=', 1)
            ->get();

        // $permintaanCuti = $pairings->flatMap(function ($pairing) {
        //     dd($pairing->bawahan);
        //     return $pairing->bawahan->permintaanCuti->where('is_approved', 0)->where('is_rejected', 0);
        // });
        return $permintaanCuti;
    }

    public static function getDisetujuiCuti($idAtasan)
    {
        // $pairings = Pairing::where('id_atasan', $idAtasan)->get();

        $permintaanCuti = PermintaanCuti::withTrashed()->with(['karyawan' => function ($query) {
            $query->withTrashed();
        }])->select('permintaan_cuti.*')
            ->join('karyawan', 'permintaan_cuti.id_karyawan', '=', 'karyawan.id')
            ->join('pairing', 'karyawan.id_posisi', '=', 'pairing.id_bawahan')
            ->where('pairing.id_atasan', $idAtasan)
            ->where('permintaan_cuti.is_approved', '=', 1)
            ->get();

        // $permintaanCuti = $pairings->flatMap(function ($pairing) {
        //     return $pairing->bawahan->permintaanCuti->where('is_approved', 1);
        // });
        return $permintaanCuti;
    }

    public static function getDibatalkanCuti($idAtasan)
    {
        $permintaanCuti = PermintaanCuti::withTrashed()->with(['karyawan' => function ($query) {
            $query->withTrashed();
        }])->select('permintaan_cuti.*')
            ->join('karyawan', 'permintaan_cuti.id_karyawan', '=', 'karyawan.id')
            ->join('pairing', 'karyawan.id_posisi', '=', 'pairing.id_bawahan')
            ->where('pairing.id_atasan', $idAtasan)
            ->where('permintaan_cuti.is_rejected', '=', 1)
            ->get();
        return $permintaanCuti;
    }

    public static function getMenungguPersetujuan($idAtasan)
    {
        $permintaanCuti = PermintaanCuti::withTrashed()->with(['karyawan' => function ($query) {
            $query->withTrashed();
        }])->select('permintaan_cuti.*')
            ->join('karyawan', 'permintaan_cuti.id_karyawan', '=', 'karyawan.id')
            ->join('pairing', 'karyawan.id_posisi', '=', 'pairing.id_bawahan')
            ->where('pairing.id_atasan', $idAtasan)
            ->where('permintaan_cuti.is_checked', '=', 0)
            ->get();
        return $permintaanCuti;
    }

    public static function getPendingCutiAtAsisten($id)
    {
        $data = self::where('is_approved', '0')->where('is_rejected', '0')->where('is_checked', '1')->where('id_pairing', $id);
        return $data;
    }

    public static function getHistoryCuti($idPosisi)
    {
        // $data = self::where('id_posisi_pembuat', $idPosisi)->orderBy('id', 'DESC');
        // return $data;
        return self::withTrashed()
               ->with(['karyawan' => function ($query) {
                   $query->withTrashed();
               }])
               ->where('id_posisi_pembuat', $idPosisi)
               ->orderBy('id', 'DESC');
    }

    public static function getDisetujui($idPosisi)
    {
        // $data = self::where('id_posisi_pembuat', $idPosisi)->where('is_approved', 1)->count();
        // return $data;
        return self::withTrashed()
               ->with(['karyawan' => function ($query) {
                   $query->withTrashed();
               }])
               ->where('id_posisi_pembuat', $idPosisi)
               ->where('is_approved', 1)
               ->count();
    }

    public static function getDitolak($idPosisi)
    {
        // $data = self::where('id_posisi_pembuat', $idPosisi)->where('is_rejected', 1)->count();
        // return $data;
        return self::withTrashed()
               ->with(['karyawan' => function ($query) {
                   $query->withTrashed();
               }])
               ->where('id_posisi_pembuat', $idPosisi)
               ->where('is_rejected', 1)
               ->count();
    }


    public static function getPending($idPosisi)
    {
        // $data = self::where('id_posisi_pembuat', $idPosisi)->where('is_approved', 0)->where('is_rejected', 0)->where('is_checked', 1)->count();
        // return $data;
        return self::withTrashed()
               ->with(['karyawan' => function ($query) {
                   $query->withTrashed();
               }])
               ->where('id_posisi_pembuat', $idPosisi)
               ->where('is_approved', 0)
               ->where('is_rejected', 0)
               ->where('is_checked', 1)
               ->count();
    }

    public static function getMenunggudiketahui($idPosisi)
    {
        // $data = self::where('id_posisi_pembuat', $idPosisi)->where('is_checked', 0)->count();
        // return $data;
        return self::withTrashed()
        ->with(['karyawan' => function ($query) {
            $query->withTrashed();
        }])
        ->where('id_posisi_pembuat', $idPosisi)
        ->where('is_checked', 0)
        ->count();
    }

    public static function getTodayKaryawanCuti($idPosisi)
    {
        $today = Carbon::now()->toDateString();

        $data = self::where('id_posisi_pembuat', $idPosisi)
            ->where('is_approved', 1)
            ->whereDate('tanggal_mulai', '<=', $today)
            ->whereDate('tanggal_selesai', '>=', $today)
            ->get();

        return $data;
    }

    public static function getBagianKaryawanCuti($idBagian)
    {
        $today = Carbon::now()->toDateString();

        $data = self::select('permintaan_cuti.*')
            ->join('karyawan', 'permintaan_cuti.id_karyawan', '=', 'karyawan.id')
            ->join('posisi', 'karyawan.id_posisi', '=', 'posisi.id')
            ->join('unit_kerja', 'posisi.id_unit_kerja', '=', 'unit_kerja.id')
            ->where('unit_kerja.id', $idBagian)
            ->where('is_approved', 1)
            ->whereDate('tanggal_mulai', '<=', $today)
            ->whereDate('tanggal_selesai', '>=', $today)
            ->get();

        return $data;
    }
}
