<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterLiburKalender extends Model
{
    protected $table = 'master_libur_kalender';
    protected $fillable = [
        'tanggal',
        'description',
        'jenis_libur',
    ];
    
    public $timestamps = false;
}
