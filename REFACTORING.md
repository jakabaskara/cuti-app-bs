# Refactoring Sidebar & Routes - Cuti App

## Perubahan yang Dilakukan

### 1. Sidebar Component Terpusat
**File:** `resources/views/components/sidebar.blade.php`

Semua sidebar sekarang menggunakan satu component yang sama, dengan konfigurasi menu per role di dalam component.

**Cara Penggunaan:**
```blade
<x-sidebar :role="'admin'" :nama="$nama" :jabatan="$jabatan" />
```

**Role yang Tersedia:**
- admin
- asisten
- kerani
- manajer
- kabag
- gm
- sevp
- pic

### 2. Routes Terorganisir
**File:** `routes/web.php`

Routes sekarang dikelompokkan dengan lebih rapi menggunakan `Route::prefix()` dan `Route::group()`:

**Struktur Routes:**
```
├── Authentication (/, /auth, /logout)
├── Password Management (/auth/change-password)
├── Admin Routes (/admin/*)
│   ├── Karyawan
│   ├── User
│   ├── Sisa Cuti
│   ├── Pairing
│   └── Riwayat Cuti
├── Asisten Routes (/asisten/*)
├── Kerani Routes (/kerani/*)
├── Manajer Routes (/manajer/*)
├── Kabag Routes (/kabag/*)
├── GM Routes (/gm/*)
├── SEVP Routes (/sevp/*)
└── Notification Routes (/notification/*)
```

### 3. File yang Dihapus
Sidebar lama yang tidak diperlukan telah dihapus:
- `resources/views/admin/layout/sidebar.blade.php`
- `resources/views/asisten/layout/sidebar.blade.php`
- `resources/views/kerani/layout/sidebar.blade.php`
- `resources/views/manajer/layout/sidebar.blade.php`
- `resources/views/kabag/layout/sidebar.blade.php`
- `resources/views/gm/layout/sidebar.blade.php`
- `resources/views/sevp/layout/sidebar.blade.php`
- `resources/views/pic/layout/sidebar.blade.php`

## Keuntungan

1. **Maintainability**: Hanya satu file sidebar yang perlu diupdate
2. **Consistency**: Semua role menggunakan struktur sidebar yang sama
3. **Scalability**: Mudah menambah menu baru untuk role tertentu
4. **Clean Code**: Routes lebih terorganisir dan mudah dibaca

## Cara Menambah Menu Baru

Edit file `resources/views/components/sidebar.blade.php`:

```php
'role_name' => [
    ['route' => 'route.name', 'uri' => 'route/path', 'icon' => 'icon_name', 'label' => 'Menu Label'],
],
```

## Testing

Pastikan untuk test semua role:
1. Login sebagai setiap role
2. Verifikasi menu sidebar muncul dengan benar
3. Test navigasi antar menu
4. Verifikasi active state pada menu yang sedang dibuka

