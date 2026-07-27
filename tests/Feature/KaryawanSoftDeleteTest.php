<?php

namespace Tests\Feature;

use App\Http\Controllers\admin\AdminKaryawanController;
use App\Models\Karyawan;
use App\Models\Posisi;
use App\Models\Role;
use App\Models\UnitKerja;
use App\Models\User;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class KaryawanSoftDeleteTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->createMinimalAuthSchema();
    }

    private function createMinimalAuthSchema(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('karyawan');
        Schema::dropIfExists('posisi');
        Schema::dropIfExists('role');
        Schema::dropIfExists('unit_kerja');
        Schema::dropIfExists('sisa_cuti');
        Schema::dropIfExists('karyawan_cuti_bersama');
        Schema::dropIfExists('permintaan_cuti');
        Schema::dropIfExists('riwayat_cuti');
        Schema::dropIfExists('log_pengurangan_cuti');

        Schema::create('unit_kerja', function (Blueprint $t) {
            $t->id();
            $t->string('kode_unit_kerja')->nullable();
            $t->string('nama_unit_kerja')->nullable();
            $t->string('bagian')->nullable();
            $t->boolean('is_kebun')->default(0);
            $t->timestamps();
        });
        Schema::create('role', function (Blueprint $t) {
            $t->id();
            $t->string('nama_role');
            $t->timestamps();
        });
        Schema::create('posisi', function (Blueprint $t) {
            $t->id();
            $t->unsignedBigInteger('id_unit_kerja');
            $t->unsignedBigInteger('id_role');
            $t->string('jabatan')->nullable();
            $t->timestamps();
        });
        Schema::create('karyawan', function (Blueprint $t) {
            $t->id();
            $t->string('nik');
            $t->string('nama');
            $t->string('jabatan')->nullable();
            $t->date('tmt_bekerja')->nullable();
            $t->date('tgl_diangkat_staf')->nullable();
            $t->unsignedBigInteger('id_posisi');
            $t->timestamps();
            $t->softDeletes();
        });
        Schema::create('users', function (Blueprint $t) {
            $t->id();
            $t->string('username');
            $t->string('password');
            $t->unsignedBigInteger('id_karyawan');
            $t->string('kode_unit')->nullable();
            $t->rememberToken();
            $t->timestamps();
            $t->softDeletes();
        });
        Schema::create('sisa_cuti', function (Blueprint $t) {
            $t->id();
            $t->unsignedBigInteger('id_karyawan');
            $t->unsignedBigInteger('id_jenis_cuti')->nullable();
            $t->date('periode_mulai')->nullable();
            $t->date('periode_akhir')->nullable();
            $t->integer('jumlah')->default(0);
            $t->timestamps();
            $t->softDeletes();
        });
        Schema::create('karyawan_cuti_bersama', function (Blueprint $t) {
            $t->id();
            $t->unsignedBigInteger('id_karyawan');
            $t->date('tanggal')->nullable();
            $t->timestamps();
            $t->softDeletes();
        });
        Schema::create('permintaan_cuti', function (Blueprint $t) {
            $t->id();
            $t->unsignedBigInteger('id_karyawan');
            $t->timestamps();
            $t->softDeletes();
        });
        Schema::create('riwayat_cuti', function (Blueprint $t) {
            $t->id();
            $t->unsignedBigInteger('id_permintaan_cuti');
            $t->timestamps();
            $t->softDeletes();
        });
        Schema::create('log_pengurangan_cuti', function (Blueprint $t) {
            $t->id();
            $t->unsignedBigInteger('id_karyawan');
            $t->timestamps();
            $t->softDeletes();
        });
    }

    private function seedUserWithKaryawan(string $username, string $password): array
    {
        $unit = UnitKerja::create([
            'kode_unit_kerja' => 'T1',
            'nama_unit_kerja' => 'Test',
            'is_kebun' => 0,
        ]);
        $role = Role::create(['nama_role' => 'manajer']);
        $posisi = Posisi::create([
            'id_unit_kerja' => $unit->id,
            'id_role' => $role->id,
            'jabatan' => 'Manajer',
        ]);
        $karyawan = Karyawan::create([
            'nik' => (string) random_int(100000, 999999),
            'nama' => 'Test Manajer',
            'jabatan' => 'Manajer',
            'tmt_bekerja' => '2020-01-01',
            'id_posisi' => $posisi->id,
        ]);
        $user = User::create([
            'username' => $username,
            'password' => $password,
            'id_karyawan' => $karyawan->id,
        ]);

        return [$karyawan, $user];
    }

    public function test_soft_delete_karyawan_soft_deletes_users_and_blocks_login(): void
    {
        [$karyawan, $user] = $this->seedUserWithKaryawan('soft_del_user', 'secret123');
        $this->assertTrue(Auth::attempt(['username' => 'soft_del_user', 'password' => 'secret123']));
        Auth::logout();

        $controller = app(AdminKaryawanController::class);
        $controller->delete($karyawan->id);

        $this->assertNull(Karyawan::find($karyawan->id));
        $this->assertTrue(Karyawan::withTrashed()->find($karyawan->id)->trashed());
        $this->assertNull(User::where('username', 'soft_del_user')->first());
        $this->assertTrue(User::withTrashed()->where('username', 'soft_del_user')->first()->trashed());
        $this->assertFalse(Auth::attempt(['username' => 'soft_del_user', 'password' => 'secret123']));
    }

    public function test_restore_karyawan_restores_user_and_login(): void
    {
        [$karyawan] = $this->seedUserWithKaryawan('restore_user', 'secret123');
        $controller = app(AdminKaryawanController::class);
        $controller->delete($karyawan->id);
        $controller->restore($karyawan->id);

        $this->assertNotNull(Karyawan::find($karyawan->id));
        $this->assertNotNull(User::where('username', 'restore_user')->first());
        $this->assertTrue(Auth::attempt(['username' => 'restore_user', 'password' => 'secret123']));
    }

    public function test_get_karyawan_data_supports_trashed_filter(): void
    {
        [$karyawan] = $this->seedUserWithKaryawan('filter_user', 'secret123');
        $controller = app(AdminKaryawanController::class);
        $controller->delete($karyawan->id);

        $active = $controller->getKaryawanData(Request::create('/admin/karyawan/data', 'GET', [
            'status' => 'active',
            'per_page' => 25,
            'page' => 1,
        ]));
        $trashed = $controller->getKaryawanData(Request::create('/admin/karyawan/data', 'GET', [
            'status' => 'trashed',
            'per_page' => 25,
            'page' => 1,
        ]));

        $this->assertSame(0, $active->getData(true)['total']);
        $this->assertSame(1, $trashed->getData(true)['total']);
    }
}
