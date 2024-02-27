<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Karyawan extends Model
{
    use HasFactory;

    // Definisikan nama table nya
    protected $table = 'karyawan';

    // masukan nama nama attribut table yang bisa di isi
    protected $fillable = [
        'NIK',
        'nama',
        'level',
        'TMT_bekerja',
        'tgl_diangkat_staf',
        'id_unit_kerja',
        'id_users',
    ];

    // Relasi
    public function unitKerja()
    {
        return $this->belongsTo(UnitKerja::class, 'id_unit_kerja');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'id_users');
    }
    public function sisaCutiTahunan()
    {
        return $this->hasMany(SisaCutiTahunan::class, 'id_karyawan');
    }
    public function sisaCutiPanjang()
    {
        return $this->hasMany(SisaCutiPanjang::class, 'id_karyawan');
    }
    public function pimpinan()
    {
        return $this->hasMany(Pimpinan::class, 'id_karyawan');
    }
    public function permintaanCuti()
    {
        return $this->hasMany(PermintaanCuti::class, 'id_karyawan');
    }
}
