<?php

use App\Http\Controllers\admin\AdminKaryawanController;
use App\Http\Controllers\asisten\AsistenDashboardController;
use App\Http\Controllers\asisten\AsistenBeritaCutiController;
use App\Http\Controllers\admin\AdminDashboardController;
use App\Http\Controllers\admin\AdminBeritaCutiController;
use App\Http\Controllers\admin\AdminRiwayatCutiController;
use App\Http\Controllers\AdminPairingController;
// use App\Http\Controllers\asisten\ChangePasswordController;
use App\Http\Controllers\asisten\CutiBersamaController as AsistenCutiBersamaController;
use App\Http\Controllers\CutiBersamaController;
use App\Http\Controllers\gm\GmDashboardController;
use App\Http\Controllers\manajer\ManajerDashboardController;
// use App\Http\Controllers\pic\PICDashboardController;
use App\Http\Controllers\kerani\KeraniDashboardController;
use App\Http\Controllers\kerani\KeraniBeritaCutiController;
// use App\Http\Controllers\manajer\ManajerBeritaCutiController;
// use App\Http\Controllers\manajer\ManajerKaryawanController;
// use App\Http\Controllers\manajer\ManajerSisaCutiController;
use App\Http\Controllers\kabag\KabagDashboardController;
use App\Http\Controllers\KeraniCutiBersamaController;
use App\Http\Controllers\SisaCutiController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\sevp\SevpBeritaCutiController;
use App\Http\Controllers\sevp\SevpDashboardController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Telegram\Bot\Laravel\Facades\Telegram;
use App\Http\Controllers\PasswordController;
use Maatwebsite\Excel\Facades\Excel;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', [AdminDashboardController::class, 'index']);

Route::group(['prefix' => 'admin', 'middleware' => ['admin.auth']], function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('admin.index');
    Route::get('/karyawan', [AdminKaryawanController::class, 'index'])->name('admin.karyawan.index');
    Route::post('/karyawan', [AdminKaryawanController::class, 'tambahKaryawan'])->name('admin.tambahKaryawan');
    Route::get('/sisacuti', [SisaCutiController::class, 'index'])->name('admin.sisacuti.index');
    Route::get('/cuti', [AdminBeritaCutiController::class, 'index'])->name('admin.cuti.index');

    Route::get('/pairing', [AdminPairingController::class, 'index'])->name('admin.pairing.index');
    Route::get('/keanggotaan', [AdminPairingController::class, 'keanggotaan'])->name('admin.pairing.keanggotaan');

    Route::get('/data-sisa-cuti', [SisaCutiController::class, 'sisaCutiData'])->name('data-sisa-cuti');

    // Riwayat Cuti
    Route::get('/riwayat-cuti', [AdminRiwayatCutiController::class, 'index'])->name('riwayat-cuti.index');
    Route::get('admin/riwayat/export', [AdminRiwayatCutiController::class, 'export'])->name('admin.riwayat.export');
});


Route::group(['prefix' => 'asisten', 'middleware' => ['asisten.auth']], function () {
    Route::get('/', [AsistenDashboardController::class, 'index'])->name('asisten.index');
    Route::get('/cuti', [AsistenBeritaCutiController::class, 'index'])->name('asisten.cuti.index');

    Route::get('/pengajuan-cuti', [AsistenDashboardController::class, 'pengajuanCuti'])->name('asisten.pengajuan-cuti');

    Route::post('/add-cuti', [AsistenDashboardController::class, 'submitCuti'])->name('asisten.submit-cuti');
    Route::delete('/delete-cuti/{id}', [AsistenDashboardController::class, 'delete'])->name('asisten.delete-cuti');

    Route::get('/downloadPDF/{id}', [AsistenDashboardController::class, 'downloadPermintaanCutiPDF'])->name('asisten.download.pdf');


    // Cuti Bersama
    Route::get('/cuti-bersama', [AsistenCutiBersamaController::class, 'index'])->name('asisten.cuti-bersama.index');
    Route::post('/store-karyawan-tidak-cuti', [AsistenCutiBersamaController::class, 'storeHadir'])->name('asisten.store-karyawan-tidak-cuti');
});

Route::group(['prefix' => 'manajer', 'middleware' => ['manajer.auth']], function () {
    Route::get('/', [ManajerDashboardController::class, 'index'])->name('manajer.index');
});

Route::group(['prefix' => 'kerani', 'middleware' => ['kerani.auth']], function () {
    Route::get('/', [KeraniDashboardController::class, 'index'])->name('kerani.index');
    Route::get('/cuti', [KeraniBeritaCutiController::class, 'index'])->name('kerani.cuti.index');

    Route::post('/add-cuti', [KeraniDashboardController::class, 'submitCuti'])->name('kerani.submit-cuti');

    Route::get('/downloadPDF/{id}', [KeraniDashboardController::class, 'downloadPermintaanCutiPDF'])->name('kerani.download.pdf');
    Route::delete('/delete-cuti/{id}', [KeraniDashboardController::class, 'delete'])->name('kerani.delete-cuti');

    Route::get('/send-nontify', [KeraniDashboardController::class, 'sendNoti'])->name('send.noti');

    // Cuti Bersama
    Route::get('/cuti-bersama', [KeraniCutiBersamaController::class, 'index'])->name('kerani.cuti-bersama');
    Route::post('/cuti-bersama', [KeraniCutiBersamaController::class, 'storeHadir'])->name('kerani.cuti-bersama.store');
});


Route::group(['prefix' => 'kabag', 'middleware' => ['kabag.auth']], function () {
    Route::get('/', [KabagDashboardController::class, 'index'])->name('kabag.index');
});


Route::group(['prefix' => 'gm', 'middleware' => ['gm.auth']], function () {
    Route::get('/', [GmDashboardController::class, 'index'])->name('gm.index');
});


Route::group(['prefix' => 'sevp', 'middleware' => ['sevp.auth']], function () {
    Route::get('/', [SevpDashboardController::class, 'index'])->name('sevp.index');
    Route::get('/cuti', [SevpBeritaCutiController::class, 'index'])->name('sevp.cuti.index');
});


// Route::get('/', [LoginController::class, 'index'])->name('login');
Route::get('/', function () {
    if (Auth::check()) {
        $karyawan = Auth::user()->karyawan;
        $role = $karyawan->posisi->role->nama_role;

        switch ($role) {
            case 'admin':
                return redirect()->route('admin.index');
                break;
            case 'kerani':
                return redirect()->route('kerani.index');
                break;
            case 'asisten':
                return redirect()->route('asisten.index');
                break;
            case 'manajer':
                return redirect()->route('manajer.index');
                break;
            case 'kabag':
                return redirect()->route('kabag.index');
                break;
            case 'gm':
                return redirect()->route('gm.index');
                break;
            case 'brm':
                return redirect()->route('sevp.index');
                break;
            case 'user':
                return view('login.index');
            default:
                // Jika tidak ada peran yang cocok, alihkan ke halaman default
                return redirect()->route('login');
        }
    } else {
        return view('login.index');
    }
})->name('login');
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');
Route::post('/auth', [LoginController::class, 'login'])->name('auth');

Route::get('/send-notification', [NotificationController::class, 'sendNotification'])->name('notification.send');

Route::get('/setWebhook', [NotificationController::class, 'setWebhook'])->name('telegram.setwebhook');
Route::post('/webhook', [NotificationController::class, 'commandHandlerWebhook'])->name('telegram.commandHandlerWebhook');

Route::group(['prefix' => 'auth', 'middleware' => 'account.auth'], function () {
    Route::get('/change-password', [PasswordController::class, 'index'])->name('password.change');
    Route::post('/change-password', [PasswordController::class, 'changePassword'])->name('password.update');
});
Route::get('/cuti-bersama', [CutiBersamaController::class, 'getCutibersama'])->name('cuti-bersama.get');
