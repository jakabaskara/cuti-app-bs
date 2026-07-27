# Soft-delete Karyawan + Login Password Fix Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Perbaiki double-hash password (agar login/reset bekerja), dan soft-delete karyawan yang sekaligus soft-delete user terkait, dengan kemampuan restore.

**Architecture:** Minimal perubahan di controller/view yang sudah ada. `User` cast `hashed` jadi satu-satunya hasher. Soft-delete/restore di `AdminKaryawanController` dalam transaksi DB, matching related rows lewat timestamp `deleted_at` karyawan. UI admin menambah filter trashed + reset password.

**Tech Stack:** Laravel (PHP), Blade + jQuery fetch tables, Eloquent SoftDeletes, PHPUnit + sqlite `:memory:` (`phpunit.xml`).

## Global Constraints

- **Jangan** menjalankan migrate / seed / INSERT / UPDATE / DELETE terhadap DB production di `.env` tanpa perintah eksplisit user.
- **Jangan** mengedit `.env`.
- Test **hanya** lewat `phpunit.xml` (sqlite `:memory:`).
- **Jangan commit** sampai user minta (skip semua step Commit di plan ini).
- Spec: `docs/superpowers/specs/2026-07-27-soft-delete-karyawan-login-fix-design.md`.

---

## File map

| File | Responsibility |
|------|----------------|
| `database/migrations/2026_07_27_000001_add_deleted_at_to_soft_delete_tables.php` | Tambah `deleted_at` jika belum ada (file saja; jangan auto-migrate prod) |
| `app/Http/Controllers/admin/AdminUserController.php` | Create user tanpa bcrypt manual; reset password |
| `app/Http/Controllers/PasswordController.php` | Change password tanpa `Hash::make` manual |
| `app/Http/Controllers/admin/AdminKaryawanController.php` | Cascade soft-delete + restore |
| `routes/web.php` | Route restore + reset password |
| `resources/views/admin/karyawan.blade.php` | Filter status, restore, fix delete URL |
| `resources/views/admin/user.blade.php` | Modal reset password, fix delete URL |
| `tests/Feature/PasswordHashingTest.php` | Auth setelah create/reset |
| `tests/Feature/KaryawanSoftDeleteTest.php` | Soft-delete + restore + login gate |

---

### Task 1: Migration `deleted_at` (schema alignment)

**Files:**
- Create: `database/migrations/2026_07_27_000001_add_deleted_at_to_soft_delete_tables.php`
- Test: verified when later Feature tests use `RefreshDatabase`

**Interfaces:**
- Consumes: none
- Produces: nullable `deleted_at` on `users`, `karyawan`, `sisa_cuti`, `karyawan_cuti_bersama`, `permintaan_cuti`, `riwayat_cuti`, `log_pengurangan_cuti` (idempotent: only add if missing)

**Notes:** Dump SQL & migration lama **tidak** punya `deleted_at`, sementara model sudah SoftDeletes. Tanpa kolom ini soft-delete crash. **Jangan** `php artisan migrate` ke DB `.env` kecuali user minta.

- [ ] **Step 1: Create migration file**

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private array $tables = [
        'users',
        'karyawan',
        'sisa_cuti',
        'karyawan_cuti_bersama',
        'permintaan_cuti',
        'riwayat_cuti',
        'log_pengurangan_cuti',
    ];

    public function up(): void
    {
        foreach ($this->tables as $table) {
            if (!Schema::hasTable($table)) {
                continue;
            }
            if (!Schema::hasColumn($table, 'deleted_at')) {
                Schema::table($table, function (Blueprint $blueprint) {
                    $blueprint->softDeletes();
                });
            }
        }
    }

    public function down(): void
    {
        foreach ($this->tables as $table) {
            if (Schema::hasTable($table) && Schema::hasColumn($table, 'deleted_at')) {
                Schema::table($table, function (Blueprint $blueprint) {
                    $blueprint->dropSoftDeletes();
                });
            }
        }
    }
};
```

- [ ] **Step 2: Skip commit** (user: jangan commit dulu)

---

### Task 2: Fix double-hash + admin reset password (backend)

**Files:**
- Modify: `app/Http/Controllers/admin/AdminUserController.php` (`tambahUser` ~L90–95; add `resetPassword`)
- Modify: `app/Http/Controllers/PasswordController.php` (L54–55)
- Modify: `routes/web.php` (admin user group ~L105–113)
- Create: `tests/Feature/PasswordHashingTest.php`

**Interfaces:**
- Consumes: `User` cast `'password' => 'hashed'`
- Produces:
  - `AdminUserController::tambahUser` stores plain password string
  - `AdminUserController::resetPassword(Request $request): RedirectResponse` — validates `id` (exists:users), `password` (required|min:3|confirmed)
  - Route name `admin.user.reset-password` → `PUT /admin/user/reset-password`

- [ ] **Step 1: Write failing tests**

Create `tests/Feature/PasswordHashingTest.php`. `UserFactory` di repo **out of sync** (pakai `name`/`email`); jangan pakai factory. Buat schema minimal di `setUp` **atau** pakai `RefreshDatabase` jika full migrate jalan di sqlite.

Prefer approach yang stabil di sqlite: helper trait di test file yang `Schema::create` tabel minimal (`role`, `unit_kerja`, `posisi`, `karyawan`, `users` + `deleted_at`), tanpa menjalankan seluruh migration stack (banyak FK/enum bisa fragile). Contoh:

```php
<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Karyawan;
use App\Models\Posisi;
use App\Models\Role;
use App\Models\UnitKerja;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
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
        $unit = UnitKerja::create(['kode_unit_kerja' => 'T1', 'nama_unit_kerja' => 'Test', 'is_kebun' => 0]);
        $role = Role::create(['nama_role' => 'manajer']);
        $posisi = Posisi::create(['id_unit_kerja' => $unit->id, 'id_role' => $role->id, 'jabatan' => 'Manajer']);
        $karyawan = Karyawan::create([
            'nik' => '1001',
            'nama' => 'Test Manajer',
            'jabatan' => 'Manajer',
            'tmt_bekerja' => '2020-01-01',
            'id_posisi' => $posisi->id,
        ]);
        return User::create([
            'username' => $username,
            'password' => $plainPassword, // MUST be hashed once by cast
            'id_karyawan' => $karyawan->id,
        ]);
    }

    public function test_user_create_with_plain_password_authenticates(): void
    {
        $user = $this->seedManajerUser('palai_manajer', 'secret123');
        $this->assertTrue(Hash::check('secret123', $user->fresh()->password));
        $this->assertTrue(Auth::attempt(['username' => 'palai_manajer', 'password' => 'secret123']));
    }

    public function test_bcrypt_before_cast_breaks_login(): void
    {
        // Documents the bug we are fixing: double hash would fail Auth::attempt
        $unit = UnitKerja::create(['kode_unit_kerja' => 'T2', 'nama_unit_kerja' => 'Test2', 'is_kebun' => 0]);
        $role = Role::create(['nama_role' => 'manajer']);
        $posisi = Posisi::create(['id_unit_kerja' => $unit->id, 'id_role' => $role->id, 'jabatan' => 'Manajer']);
        $karyawan = Karyawan::create([
            'nik' => '1002', 'nama' => 'X', 'jabatan' => 'M', 'tmt_bekerja' => '2020-01-01', 'id_posisi' => $posisi->id,
        ]);
        $user = new User();
        $user->username = 'broken_user';
        $user->id_karyawan = $karyawan->id;
        $user->password = bcrypt('secret123'); // pre-hash then cast hashes again
        $user->save();
        $this->assertFalse(Auth::attempt(['username' => 'broken_user', 'password' => 'secret123']));
    }
}
```

Sesuaikan nama kolom `nik`/`tmt_bekerja` dengan yang dipakai model fillable (bukan `NIK`/`TMT_bekerja` dari dump lama) — model fillable memakai lowercase.

- [ ] **Step 2: Run tests — expect create/auth PASS already on model path; document double-hash FAIL**

Run: `php artisan test --filter=PasswordHashingTest`

Expected: `test_user_create_with_plain_password_authenticates` PASS; `test_bcrypt_before_cast_breaks_login` PASS (assertFalse).

- [ ] **Step 3: Fix controllers**

In `AdminUserController::tambahUser`, change:

```php
'password' => $validate['password'], // was bcrypt(...)
```

In `PasswordController::changePassword`, change:

```php
$user->password = $request->password; // was Hash::make(...)
$user->save();
```

Add to `AdminUserController`:

```php
public function resetPassword(Request $request)
{
    $validate = $request->validate([
        'id' => 'required|exists:users,id',
        'password' => 'required|min:3|confirmed',
    ]);

    $user = User::findOrFail($validate['id']);
    $user->password = $validate['password'];
    $user->save();

    return redirect()->back()->with('message', 'Password berhasil direset');
}
```

In `routes/web.php` inside `admin/user` group:

```php
Route::put('/reset-password', [AdminUserController::class, 'resetPassword'])->name('admin.user.reset-password');
```

- [ ] **Step 4: Extend test for reset password**

```php
public function test_admin_reset_password_allows_login_with_new_password(): void
{
    $user = $this->seedManajerUser('reset_me', 'oldpass');
    // Simulate admin reset (same code path as controller)
    $user->password = 'newpass99';
    $user->save();
    $this->assertFalse(Auth::attempt(['username' => 'reset_me', 'password' => 'oldpass']));
    $this->assertTrue(Auth::attempt(['username' => 'reset_me', 'password' => 'newpass99']));
}
```

- [ ] **Step 5: Run tests**

Run: `php artisan test --filter=PasswordHashingTest`  
Expected: all PASS

- [ ] **Step 6: Skip commit**

---

### Task 3: Soft-delete cascade + restore (backend)

**Files:**
- Modify: `app/Http/Controllers/admin/AdminKaryawanController.php` (`delete` L130–163; add `restore`)
- Modify: `routes/web.php` (karyawan group)
- Create: `tests/Feature/KaryawanSoftDeleteTest.php`

**Interfaces:**
- Consumes: SoftDeletes on `Karyawan`, `User`, `SisaCuti`, `KaryawanCutiBersama`, `PermintaanCuti`, `RiwayatCuti`
- Produces:
  - `delete($id)`: cascade soft-delete users + related + karyawan; no “user exists” blocker
  - `restore($id)`: restore karyawan + users + related rows whose `deleted_at` matches karyawan’s pre-restore `deleted_at` (±2 seconds window)
  - Route `POST /admin/karyawan/{id}/restore` name `admin.karyawan.restore`

- [ ] **Step 1: Write failing feature tests**

Reuse minimal schema from Task 2; extend with empty related tables if needed for delete path. Core assertions:

```php
public function test_soft_delete_karyawan_soft_deletes_users_and_blocks_login(): void
{
    // seed user+karyawan like Task 2
    // call controller delete OR replicate transaction logic under test via HTTP actingAs admin
    // assert Karyawan::find($id) === null
    // assert Karyawan::withTrashed()->find($id)->trashed()
    // assert User::where('username', $u)->first() === null
    // assert User::withTrashed()->where('username', $u)->first()->trashed()
    // assert Auth::attempt([...]) === false
}

public function test_restore_karyawan_restores_user_and_login(): void
{
    // soft delete then restore
    // assert Auth::attempt succeeds again
}
```

Prefer testing via HTTP with `actingAs($adminUser)` and middleware bypass if `admin.auth` hard to satisfy — or call controller methods directly with `app(AdminKaryawanController::class)->delete($id)` inside a test request. Simplest reliable path: **extract transaction into private methods and invoke controller from test with `$this->withoutMiddleware()`** on routes.

Example HTTP:

```php
$this->withoutMiddleware();
$this->delete('/admin/karyawan/'.$karyawan->id)->assertRedirect();
$this->post('/admin/karyawan/'.$karyawan->id.'/restore')->assertRedirect();
```

- [ ] **Step 2: Run — expect FAIL** (restore missing / delete still blocks)

Run: `php artisan test --filter=KaryawanSoftDeleteTest`

- [ ] **Step 3: Implement `delete` and `restore`**

Replace `delete` body roughly with:

```php
public function delete($id)
{
    $karyawan = Karyawan::find($id);
    if (!$karyawan) {
        return redirect()->back()->with('warning_message', 'Data karyawan tidak ditemukan');
    }

    DB::transaction(function () use ($id) {
        $deletedAt = now();

        User::where('id_karyawan', $id)->get()->each(function (User $user) use ($deletedAt) {
            $user->deleted_at = $deletedAt;
            $user->save();
        });

        SisaCuti::where('id_karyawan', $id)->whereNull('deleted_at')->get()->each(function ($row) use ($deletedAt) {
            $row->deleted_at = $deletedAt;
            $row->save();
        });
        KaryawanCutiBersama::where('id_karyawan', $id)->whereNull('deleted_at')->get()->each(function ($row) use ($deletedAt) {
            $row->deleted_at = $deletedAt;
            $row->save();
        });

        $permintaan = PermintaanCuti::where('id_karyawan', $id)->whereNull('deleted_at')->get();
        $permintaanIds = $permintaan->pluck('id');
        RiwayatCuti::whereIn('id_permintaan_cuti', $permintaanIds)->whereNull('deleted_at')->get()->each(function ($row) use ($deletedAt) {
            $row->deleted_at = $deletedAt;
            $row->save();
        });
        $permintaan->each(function ($row) use ($deletedAt) {
            $row->deleted_at = $deletedAt;
            $row->save();
        });

        DB::table('log_pengurangan_cuti')
            ->where('id_karyawan', $id)
            ->whereNull('deleted_at')
            ->update(['deleted_at' => $deletedAt]);

        $karyawan = Karyawan::find($id);
        $karyawan->deleted_at = $deletedAt;
        $karyawan->save();
    });

    return redirect()->back()->with('error_message', 'Data karyawan berhasil dihapus (soft delete)');
}
```

`restore`:

```php
public function restore($id)
{
    $karyawan = Karyawan::withTrashed()->find($id);
    if (!$karyawan || !$karyawan->trashed()) {
        return redirect()->back()->with('warning_message', 'Data karyawan terhapus tidak ditemukan');
    }

    DB::transaction(function () use ($karyawan) {
        $marker = $karyawan->deleted_at;
        $from = $marker->copy()->subSeconds(2);
        $to = $marker->copy()->addSeconds(2);

        User::withTrashed()
            ->where('id_karyawan', $karyawan->id)
            ->whereBetween('deleted_at', [$from, $to])
            ->restore();

        SisaCuti::withTrashed()->where('id_karyawan', $karyawan->id)->whereBetween('deleted_at', [$from, $to])->restore();
        KaryawanCutiBersama::withTrashed()->where('id_karyawan', $karyawan->id)->whereBetween('deleted_at', [$from, $to])->restore();

        $permintaanIds = PermintaanCuti::withTrashed()
            ->where('id_karyawan', $karyawan->id)
            ->whereBetween('deleted_at', [$from, $to])
            ->pluck('id');

        RiwayatCuti::withTrashed()->whereIn('id_permintaan_cuti', $permintaanIds)->whereBetween('deleted_at', [$from, $to])->restore();
        PermintaanCuti::withTrashed()->where('id_karyawan', $karyawan->id)->whereBetween('deleted_at', [$from, $to])->restore();

        DB::table('log_pengurangan_cuti')
            ->where('id_karyawan', $karyawan->id)
            ->whereBetween('deleted_at', [$from, $to])
            ->update(['deleted_at' => null]);

        $karyawan->restore();
    });

    return redirect()->back()->with('message', 'Data karyawan berhasil dipulihkan');
}
```

Route:

```php
Route::post('/{id}/restore', [AdminKaryawanController::class, 'restore'])->name('admin.karyawan.restore');
```

Place **before** `Route::delete('/{id}'...)` is fine; restore is POST with distinct path.

Update `getKaryawanData` to accept `status`:

```php
$status = $request->input('status', 'active'); // active|trashed
$query = Karyawan::with('posisi.unitKerja');
if ($status === 'trashed') {
    $query->onlyTrashed();
}
```

- [ ] **Step 4: Run tests — expect PASS**

Run: `php artisan test --filter=KaryawanSoftDeleteTest`

- [ ] **Step 5: Skip commit**

---

### Task 4: UI admin karyawan (filter + restore + fix delete URL)

**Files:**
- Modify: `resources/views/admin/karyawan.blade.php`

**Interfaces:**
- Consumes: `admin.karyawan.data?status=`, `admin.karyawan.restore`, `admin.delete-karyawan`
- Produces: toggle Aktif/Terhapus; restore button; correct delete form action

- [ ] **Step 1: Fix delete form action**

Current bug: JS sets `/admin/delete-karyawan/${id}` tetapi route sebenarnya `/admin/karyawan/{id}`.

```javascript
$('#deleteEmployeeForm').attr('action', `/admin/karyawan/${id}`);
```

- [ ] **Step 2: Add status filter UI + pass to fetch**

Add buttons/tabs “Aktif” / “Terhapus”. Keep `let statusFilter = 'active';`.

```javascript
fetch(`{{ route('admin.karyawan.data') }}?page=${currentPage}&per_page=${perPage}&search=${encodeURIComponent(searchQuery)}&status=${statusFilter}`)
```

- [ ] **Step 3: Render Restore vs Delete**

If `statusFilter === 'trashed'`, show restore button only:

```javascript
<button class="btn btn-sm btn-success" onclick="confirmRestore(${item.id}, '${item.nama}')">
  <span class="material-icons">restore</span>
</button>
```

Add restore confirmation modal + form:

```html
<form id="restoreEmployeeForm" method="post">
  @csrf
  <button type="submit" class="btn btn-success">Pulihkan</button>
</form>
```

```javascript
$('#restoreEmployeeForm').attr('action', `/admin/karyawan/${id}/restore`);
```

Keep NIK warning text on delete modal as-is.

- [ ] **Step 4: Manual UI smoke** (local browser if app up) — skip if DB unreachable; rely on tests for backend

- [ ] **Step 5: Skip commit**

---

### Task 5: UI admin user reset password + fix delete URL

**Files:**
- Modify: `resources/views/admin/user.blade.php`

**Interfaces:**
- Consumes: `admin.user.reset-password`
- Produces: modal reset password per row

- [ ] **Step 1: Fix delete URL**

```javascript
$('#deleteEmployeeForm').attr('action', `/admin/user/${id}`);
```

- [ ] **Step 2: Add reset password button + modal**

In `renderTable` actions:

```javascript
<button class="btn btn-sm btn-info" onclick="openResetPassword(${item.id}, '${item.username}')">
  <span class="material-icons">lock_reset</span>
</button>
```

Modal form:

```html
<form method="post" action="{{ route('admin.user.reset-password') }}">
  @csrf
  @method('PUT')
  <input type="hidden" name="id" id="resetUserId" />
  <input type="password" name="password" required minlength="3" />
  <input type="password" name="password_confirmation" required minlength="3" />
  <button type="submit" class="btn btn-primary">Reset Password</button>
</form>
```

- [ ] **Step 3: Skip commit**

---

### Task 6: Verification checklist (no prod writes)

**Files:** none (checklist)

- [ ] **Step 1: Run full related tests**

```bash
php artisan test --filter='PasswordHashingTest|KaryawanSoftDeleteTest'
```

Expected: all PASS. Confirm process **tidak** memakai `DB_*` production (phpunit forces sqlite memory).

- [ ] **Step 2: Provide manual SELECT for `palai_manajer`** (user/DBA runs when DB reachable)

Use SQL from spec. Jika `pw_prefix` OK tapi login gagal → reset via admin UI setelah deploy. Jika `user_deleted` set → restore karyawan terkait.

- [ ] **Step 3: Remind user** — migration file belum dijalankan ke production; minta izin eksplisit sebelum `php artisan migrate` di server.

- [ ] **Step 4: Skip commit** until user asks

---

## Self-review (plan vs spec)

| Spec requirement | Task |
|------------------|------|
| Fix double-hash create/change | Task 2 |
| Admin reset password | Task 2 + 5 |
| Soft-delete karyawan + users + related | Task 3 |
| Restore with related window | Task 3 |
| UI filter + restore | Task 4 |
| No prod DB damage; sqlite tests | Global + Task 6 |
| `deleted_at` verification/migration | Task 1 |
| Manual SELECT diagnostics | Task 6 |
| Fix wrong delete URLs (discovered) | Task 4 + 5 |

No TBD placeholders. Commit steps deferred per user request.
