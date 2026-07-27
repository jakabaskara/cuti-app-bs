<?php

namespace Tests\Feature;

use App\Models\Karyawan;
use App\Models\Posisi;
use App\Models\Role;
use App\Models\UnitKerja;
use App\Models\User;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class PasswordHashingTest extends TestCase
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
    }

    private function seedManajerUser(string $username, string $plainPassword): User
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

        return User::create([
            'username' => $username,
            'password' => $plainPassword,
            'id_karyawan' => $karyawan->id,
        ]);
    }

    public function test_user_create_with_plain_password_authenticates(): void
    {
        $user = $this->seedManajerUser('palai_manajer', 'secret123');

        $this->assertTrue(Hash::check('secret123', $user->fresh()->password));
        $this->assertTrue(Auth::attempt([
            'username' => 'palai_manajer',
            'password' => 'secret123',
        ]));
    }

    public function test_already_hashed_password_assignment_still_authenticates(): void
    {
        // Laravel 'hashed' cast skips re-hash when value is already hashed.
        $unit = UnitKerja::create([
            'kode_unit_kerja' => 'T2',
            'nama_unit_kerja' => 'Test2',
            'is_kebun' => 0,
        ]);
        $role = Role::create(['nama_role' => 'manajer']);
        $posisi = Posisi::create([
            'id_unit_kerja' => $unit->id,
            'id_role' => $role->id,
            'jabatan' => 'Manajer',
        ]);
        $karyawan = Karyawan::create([
            'nik' => '1002',
            'nama' => 'X',
            'jabatan' => 'M',
            'tmt_bekerja' => '2020-01-01',
            'id_posisi' => $posisi->id,
        ]);

        $user = new User();
        $user->username = 'prehashed_user';
        $user->id_karyawan = $karyawan->id;
        $user->password = bcrypt('secret123');
        $user->save();

        $this->assertTrue(Auth::attempt([
            'username' => 'prehashed_user',
            'password' => 'secret123',
        ]));
    }

    public function test_admin_reset_password_allows_login_with_new_password(): void
    {
        $user = $this->seedManajerUser('reset_me', 'oldpass');
        $user->password = 'newpass99';
        $user->save();

        $this->assertFalse(Auth::attempt([
            'username' => 'reset_me',
            'password' => 'oldpass',
        ]));
        $this->assertTrue(Auth::attempt([
            'username' => 'reset_me',
            'password' => 'newpass99',
        ]));
    }
}
