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

    public function Posisi(){
        return $this->belongsTo(Posisi::class, 'id_posisi');
    }
}
