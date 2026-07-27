# Soft-delete karyawan + perbaikan login password

Date: 2026-07-27  
Status: approved for planning (pending user review of this file)  
Approach: minimal fix (existing controllers + SoftDeletes)

## Problem

1. Beberapa akun (contoh: `palai_manajer`) gagal login dengan pesan **“Username atau Password Anda Salah”** (`Auth::attempt` gagal).
2. Admin butuh kemampuan menonaktifkan karyawan (pensiun/MBT/keluar) tanpa hard delete, termasuk akun login terkait, dan bisa di-restore.

## Findings (read-only)

- `User` dan `Karyawan` sudah memakai `SoftDeletes`.
- `AdminKaryawanController::delete` sudah soft-delete related cuti, tapi **menolak** hapus jika masih ada user aktif.
- UI hapus karyawan sudah ada di `admin/karyawan`.
- Risiko kuat double-hash: `User` cast `'password' => 'hashed'` + `AdminUserController` memakai `bcrypt()` + `PasswordController` memakai `Hash::make()`.
- Host DB production di `.env` tidak reachable dari environment agent; dump lokal (Jan 2026) tidak berisi `palai_*`. Diagnosa akun spesifik lewat SELECT manual saat DB accessible.

## Goals

- Password di-hash **sekali** saat create/change/reset.
- Admin bisa reset password user tanpa current password.
- Soft-delete karyawan → soft-delete semua user terkait + related soft-deletable records (pola existing).
- Restore karyawan → restore user + related yang ikut dihapus pada aksi itu.
- UI filter aktif/terhapus + tombol restore.
- Tidak merusak DB/env production dari agent; test memakai sqlite `:memory:` (`phpunit.xml`).

## Non-goals

- Hard delete.
- Migrasi skema baru kecuali verifikasi membuktikan kolom `deleted_at` belum ada di DB live (model sudah SoftDeletes).
- Refactor auth/role besar.
- Mass password fix tanpa aksi admin per akun.
- Pesan login khusus untuk user soft-deleted (tetap pesan generic di v1).

## Design

### Password / login

| Lokasi | Perubahan |
|--------|-----------|
| `AdminUserController::tambahUser` | Simpan password plain string; biarkan cast `hashed` |
| `PasswordController` | Assign plain password; jangan `Hash::make` manual |
| `AdminUserController` + `admin/user` view | Endpoint + UI **reset password** (admin set password baru) |
| `LoginController` | Tidak diubah alur role; soft-deleted user tetap gagal attempt |

### Soft-delete & restore karyawan

`AdminKaryawanController::delete($id)` dalam transaksi:

1. Soft-delete semua `User` dengan `id_karyawan = $id` (termasuk yang aktif).
2. Soft-delete related: `SisaCuti`, `KaryawanCutiBersama`, `PermintaanCuti` (+ `RiwayatCuti` terkait), `log_pengurangan_cuti`.
3. Soft-delete `Karyawan`.
4. Hapus blocker “masih ada user → tolak”.

`AdminKaryawanController::restore($id)` dalam transaksi:

1. `Karyawan::withTrashed()->findOrFail` → `restore()`.
2. Restore semua `User` trashed untuk `id_karyawan`.
3. Restore related records milik karyawan yang soft-deleted **pada window yang sama** dengan `deleted_at` karyawan (hindari menghidupkan data yang sudah dihapus sebelumnya tidak terkait pensiun).

Route baru: `POST/PATCH` restore (mis. `admin/karyawan/{id}/restore`).

### UI

- `admin/karyawan`: query `status=active|trashed` (default `active`); tombol Hapus vs Restore.
- `admin/user`: tombol Reset Password; list default user aktif (user ikut hilang dari list saat soft-delete).

### NIK

Soft-delete tidak menghapus baris; unique NIK tetap terpakai oleh row soft-deleted. UI tetap peringatkan. Restore mengembalikan NIK yang sama.

## Error handling

- Hapus/restore id tidak valid atau state salah → flash warning, no crash.
- Multi-user per karyawan → semua ikut soft-delete/restore.
- Gagal di tengah transaksi → rollback penuh.

## Diagnostics (manual, SELECT only)

Saat DB reachable, cek akun bermasalah:

```sql
SELECT u.id, u.username, u.deleted_at AS user_deleted,
       LENGTH(u.password) AS pw_len, LEFT(u.password, 7) AS pw_prefix,
       k.id AS karyawan_id, k.nama, k.deleted_at AS karyawan_deleted,
       r.nama_role
FROM users u
LEFT JOIN karyawan k ON k.id = u.id_karyawan
LEFT JOIN posisi p ON p.id = k.id_posisi
LEFT JOIN role r ON r.id = p.id_role
WHERE u.username = 'palai_manajer';
```

Interpretasi cepat: `user_deleted` tidak null → soft-deleted; `pw_prefix` bukan `$2y$12$` / hash aneh → kemungkinan corrupt/double-hash; role null → masalah posisi (biasanya pesan B, bukan A).

Perbaikan akun: admin reset password (setelah fix double-hash), atau restore jika soft-deleted.

## Testing

- Feature tests di `tests/Feature` dengan sqlite memory (sudah di `phpunit.xml`).
- Kasus wajib:
  1. Create user → `Auth::attempt` sukses dengan password plain.
  2. Soft-delete karyawan → user tidak lolos `Auth::attempt`.
  3. Restore karyawan → user bisa login lagi.
  4. Reset password admin → login dengan password baru.
- Jangan mengarahkan test ke DB production `.env`.

## Files likely touched

- `app/Http/Controllers/admin/AdminKaryawanController.php`
- `app/Http/Controllers/admin/AdminUserController.php`
- `app/Http/Controllers/PasswordController.php`
- `resources/views/admin/karyawan.blade.php`
- `resources/views/admin/user.blade.php`
- `routes/web.php`
- `tests/Feature/...` (baru)

## Open verification before implement

- Konfirmasi kolom `deleted_at` ada di tabel live `users` / `karyawan` / related (SELECT schema only). Jika belum, baru tulis migration file — **jangan jalankan migrate** tanpa perintah eksplisit user.
