<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Users extends Model
{
    use HasFactory;

    protected $table = 'users';

    protected $fillable = [
        'username',
        'password',
        'id_karyawan',
    ];

    public function karyawan(){
        return $this->belongsTo(Karyawan::class, 'id_karyawan');
    }
}
