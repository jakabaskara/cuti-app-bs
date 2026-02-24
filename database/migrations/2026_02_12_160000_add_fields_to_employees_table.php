<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->string('sap', 50)->nullable()->after('id');
            $table->string('name', 255)->nullable()->after('sap');
            $table->string('phone', 50)->nullable()->after('name');
            $table->string('email', 255)->nullable()->after('phone');
            $table->string('birthplace', 255)->nullable()->after('email');
            $table->date('birthdate')->nullable()->after('birthplace');
            $table->string('gender', 50)->nullable()->after('birthdate');
            $table->string('religion', 100)->nullable()->after('gender');
            $table->string('personnel_area', 50)->nullable()->after('religion');
            $table->string('desc_personnel_area', 255)->nullable()->after('personnel_area');
            $table->string('personnel_subarea', 50)->nullable()->after('desc_personnel_area');
            $table->string('desc_personnel_subarea', 255)->nullable()->after('personnel_subarea');
            $table->string('region', 50)->nullable()->after('desc_personnel_subarea');
            $table->string('org_unit', 50)->nullable()->after('region');
            $table->string('desc_org_unit', 255)->nullable()->after('org_unit');
            $table->string('employee_group', 50)->nullable()->after('desc_org_unit');
            $table->string('desc_employee_group', 255)->nullable()->after('employee_group');
            $table->string('employee_subgroup', 50)->nullable()->after('desc_employee_group');
            $table->string('desc_employee_subgroup', 255)->nullable()->after('employee_subgroup');
            $table->string('level', 50)->nullable()->after('desc_employee_subgroup');
            $table->string('position', 50)->nullable()->after('level');
            $table->string('desc_position', 255)->nullable()->after('position');
            $table->string('job', 50)->nullable()->after('desc_position');
            $table->string('desc_job', 255)->nullable()->after('job');
            $table->string('suku', 255)->nullable()->after('desc_job');
            $table->string('kode_ring', 50)->nullable()->after('suku');
            $table->string('desc_kode_ring', 255)->nullable()->after('kode_ring');
            $table->string('division', 255)->nullable()->after('desc_kode_ring');
            $table->date('work_start_date')->nullable()->after('division');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropColumn([
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
                'created_at',
                'updated_at',
            ]);
        });
    }
};
