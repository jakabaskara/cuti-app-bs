<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CutiBersama extends Model
{

    protected $table = 'cuti_bersama';

    protected $fillable = [
        'id_karyawan',
        'tanggal_mulai',
        'tanggal_selesai',
        'jumlah_cuti_bersama',
    ];

    use HasFactory;
}
