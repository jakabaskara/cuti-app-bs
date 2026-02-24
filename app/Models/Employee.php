<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $table = 'employees';

    protected $fillable = [
        'sap',
        'name',
        'phone',
        'email',
        'birthplace',
        'birthdate',
        'gender',
        'religion',
        'personnel_area',
        'desc_personnel_area',
        'personnel_subarea',
        'desc_personnel_subarea',
        'region',
        'org_unit',
        'desc_org_unit',
        'employee_group',
        'desc_employee_group',
        'employee_subgroup',
        'desc_employee_subgroup',
        'level',
        'position',
        'desc_position',
        'job',
        'desc_job',
        'suku',
        'kode_ring',
        'desc_kode_ring',
        'division',
        'work_start_date',
    ];

    protected $casts = [
        'birthdate' => 'date',
        'work_start_date' => 'date',
    ];

    /**
     * Get kode unit - for regional units (2nd char = 'R'), use first 4 digits of kode_ring
     * Otherwise return null (will need manual mapping)
     */
    public function getKodeUnitAttribute()
    {
        if (strlen($this->personnel_subarea) >= 2 && $this->personnel_subarea[1] === 'R') {
            // Regional unit - use first 4 digits of kode_ring
            return substr($this->kode_ring, 0, 4);
        }
        // Non-regional - would need different logic or mapping
        return null;
    }

    public function employeePositions()
    {
        return $this->hasMany(EmployeePosition::class , 'nik', 'sap');
    }
}