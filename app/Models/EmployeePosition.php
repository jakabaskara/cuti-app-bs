<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeePosition extends Model
{
    use HasFactory;

    protected $table = 'employee_position';

    protected $fillable = [
        'nik',
        'id_position',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class , 'nik', 'sap');
    }

    public function posisi()
    {
        return $this->belongsTo(Posisi::class , 'id_position');
    }
}