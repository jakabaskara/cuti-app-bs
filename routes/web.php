<?php

use App\Http\Controllers\admin\AdminKaryawanController;
use App\Http\Controllers\admin\AdminEmployeeSapController;
use App\Http\Controllers\admin\AdminLeaveBalanceReportController;
use App\Http\Controllers\asisten\AsistenDashboardController;
use App\Http\Controllers\asisten\AsistenBeritaCutiController;
use App\Http\Controllers\admin\AdminDashboardController;
use App\Http\Controllers\admin\AdminBeritaCutiController;
use App\Http\Controllers\admin\AdminRiwayatCutiController;
use App\Http\Controllers\admin\AdminUserController;
use App\Http\Controllers\AdminPairingController;
use App\Http\Controllers\asisten\CutiBersamaController as AsistenCutiBersamaController;
use App\Http\Controllers\CutiBersamaController;
use App\Http\Controllers\gm\GmDashboardController;
use App\Http\Controllers\manajer\ManajerDashboardController;
use App\Http\Controllers\kerani\KeraniDashboardController;
use App\Http\Controllers\kerani\KeraniBeritaCutiController;
use App\Http\Controllers\kabag\KabagDashboardController;
use App\Http\Controllers\KeraniCutiBersamaController;
use App\Http\Controllers\OrganizationalChartController;
use App\Http\Controllers\SisaCutiController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\sevp\SevpBeritaCutiController;
use App\Http\Controllers\sevp\SevpDashboardController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PasswordController;

Route::get('/', function () {
    if (Auth::check()) {
        $karyawan = Auth::user()->karyawan;
        $role = $karyawan->posisi->role->nama_role;

        switch ($role) {
            case 'admin':
                return redirect()->route('admin.index');
            case 'kerani':
                return redirect()->route('kerani.index');
            case 'asisten':
                return redirect()->route('asisten.index');
            case 'manajer':
                return redirect()->route('manajer.index');
            case 'kabag':
                return redirect()->route('kabag.index');
            case 'gm':
                return redirect()->route('gm.index');
            case 'brm':
                return redirect()->route('sevp.index');
            case 'user':
                return view('login.index');
            default:
                return redirect()->route('login');
        }
    }
    return view('login.index');
})->name('login');

Route::post('/auth', [LoginController::class , 'login'])->name('auth');
Route::get('/logout', [LoginController::class , 'logout'])->name('logout');

Route::group(['prefix' => 'auth', 'middleware' => 'account.auth'], function () {
    Route::get('/change-password', [PasswordController::class , 'index'])->name('password.change');
    Route::post('/change-password', [PasswordController::class , 'changePassword'])->name('password.update');

    Route::prefix('employee-sap')->group(function () {
            Route::get('/', [AdminEmployeeSapController::class , 'index'])->name('admin.employee-sap.index');
            Route::get('/data', [AdminEmployeeSapController::class , 'getEmployeeSapData'])->name('admin.employee-sap.data');
        }
        );

        Route::prefix('leave-balance-report')->group(function () {
            Route::get('/', [AdminLeaveBalanceReportController::class , 'index'])->name('leave-balance-report.index');
            Route::get('/data', [AdminLeaveBalanceReportController::class , 'getReportData'])->name('leave-balance-report.data');
            Route::get('/export-excel', [AdminLeaveBalanceReportController::class , 'exportExcel'])->name('leave-balance-report.export-excel');
            Route::get('/export-pdf', [AdminLeaveBalanceReportController::class , 'exportPdf'])->name('leave-balance-report.export-pdf');
        }
        );

        Route::prefix('organizational-chart')->group(function () {
            Route::get('/', [OrganizationalChartController::class , 'index'])->name('organizational-chart.index');
            Route::get('/data', [OrganizationalChartController::class , 'getChartData'])->name('organizational-chart.data');
            Route::get('/available-employees', [OrganizationalChartController::class , 'getAvailableEmployees'])->name('organizational-chart.available-employees');
            Route::post('/employee-position', [OrganizationalChartController::class , 'storeEmployeePosition'])->name('organizational-chart.employee-position.store');
            Route::put('/employee-position/{id}', [OrganizationalChartController::class , 'updateEmployeePosition'])->name('organizational-chart.employee-position.update');
            Route::delete('/employee-position/{id}', [OrganizationalChartController::class , 'destroyEmployeePosition'])->name('organizational-chart.employee-position.destroy');
        }
        );
    });

Route::group(['prefix' => 'admin', 'middleware' => ['admin.auth']], function () {
    Route::get('/', [AdminDashboardController::class , 'index'])->name('admin.index');

    Route::prefix('karyawan')->group(function () {
            Route::get('/', [AdminKaryawanController::class , 'index'])->name('admin.karyawan.index');
            Route::get('/data', [AdminKaryawanController::class , 'getKaryawanData'])->name('admin.karyawan.data');
            Route::get('/{id}/edit', [AdminKaryawanController::class , 'edit'])->name('admin.karyawan.edit');
            Route::post('/', [AdminKaryawanController::class , 'tambahKaryawan'])->name('tambahKaryawan');
            Route::put('/update', [AdminKaryawanController::class , 'updateKaryawan'])->name('updateKaryawan');
            Route::delete('/{id}', [AdminKaryawanController::class , 'delete'])->name('admin.delete-karyawan');
        }
        );

        Route::prefix('user')->group(function () {
            Route::get('/', [AdminUserController::class , 'index'])->name('admin.user.index');
            Route::get('/data', [AdminUserController::class , 'getUserData'])->name('admin.user.data');
            Route::get('/karyawan-select', [AdminUserController::class , 'getKaryawanForSelect'])->name('admin.user.karyawan-select');
            Route::get('/{id}/edit', [AdminUserController::class , 'edit'])->name('admin.user.edit');
            Route::post('/', [AdminUserController::class , 'tambahUser'])->name('tambahUser');
            Route::put('/update', [AdminUserController::class , 'updateUser'])->name('updateUser');
            Route::delete('/{id}', [AdminUserController::class , 'delete'])->name('admin.delete-user');
        }
        );

        Route::prefix('sisacuti')->group(function () {
            Route::get('/', [SisaCutiController::class , 'index'])->name('admin.sisacuti.index');
            Route::get('/data', [SisaCutiController::class , 'getSisaCutiData'])->name('admin.sisacuti.data');
            Route::get('/karyawan-select', [SisaCutiController::class , 'getKaryawanForSelect'])->name('admin.sisacuti.karyawan-select');
            Route::get('/{id_karyawan}/edit', [SisaCutiController::class , 'edit'])->name('admin.sisacuti.edit');
            Route::post('/', [SisaCutiController::class , 'tambahCuti'])->name('tambahCuti');
            Route::put('/update', [SisaCutiController::class , 'updateCuti'])->name('updateCuti');
            Route::delete('/{id_karyawan}', [SisaCutiController::class , 'delete'])->name('admin.delete-sisacuti');
        }
        );

        Route::get('/data-sisa-cuti', [SisaCutiController::class , 'sisaCutiData'])->name('data-sisa-cuti');
        Route::get('/cuti', [AdminBeritaCutiController::class , 'index'])->name('admin.cuti.index');

        Route::prefix('pairing')->group(function () {
            Route::get('/', [AdminPairingController::class , 'index'])->name('admin.pairing.index');
            Route::get('/data', [AdminPairingController::class , 'getPairingData'])->name('admin.pairing.data');
            Route::get('/keanggotaan', [AdminPairingController::class , 'keanggotaan'])->name('admin.pairing.keanggotaan');
            Route::get('/keanggotaan/data', [AdminPairingController::class , 'getKeanggotaanData'])->name('admin.pairing.keanggotaan.data');
        }
        );

        Route::prefix('kalender')->group(function () {
            Route::get('/', [\App\Http\Controllers\admin\AdminKalenderController::class , 'index'])->name('admin.kalender.index');
            Route::get('/data', [\App\Http\Controllers\admin\AdminKalenderController::class , 'getKalenderData'])->name('admin.kalender.data');
            Route::post('/', [\App\Http\Controllers\admin\AdminKalenderController::class , 'store'])->name('admin.kalender.store');
            Route::get('/{id}', [\App\Http\Controllers\admin\AdminKalenderController::class , 'show'])->name('admin.kalender.show');
            Route::put('/{id}', [\App\Http\Controllers\admin\AdminKalenderController::class , 'update'])->name('admin.kalender.update');
            Route::delete('/{id}', [\App\Http\Controllers\admin\AdminKalenderController::class , 'destroy'])->name('admin.kalender.destroy');
        }
        );

        Route::prefix('riwayat-cuti')->group(function () {
            Route::get('/', [AdminRiwayatCutiController::class , 'index'])->name('riwayat-cuti.index');
            Route::get('/export', [AdminRiwayatCutiController::class , 'export'])->name('admin.riwayat.export');
        }
        );
    });

Route::group(['prefix' => 'asisten', 'middleware' => ['asisten.auth']], function () {
    Route::get('/', [AsistenDashboardController::class , 'index'])->name('asisten.index');
    Route::get('/cuti', [AsistenBeritaCutiController::class , 'index'])->name('asisten.cuti.index');
    Route::get('/pengajuan-cuti', [AsistenDashboardController::class , 'pengajuanCuti'])->name('asisten.pengajuan-cuti');

    Route::post('/add-cuti', [AsistenDashboardController::class , 'submitCuti'])->name('asisten.submit-cuti');
    Route::delete('/delete-cuti/{id}', [AsistenDashboardController::class , 'delete'])->name('asisten.delete-cuti');
    Route::get('/downloadPDF/{id}', [AsistenDashboardController::class , 'downloadPermintaanCutiPDF'])->name('asisten.download.pdf');

    Route::prefix('cuti-bersama')->group(function () {
            Route::get('/', [AsistenCutiBersamaController::class , 'index'])->name('asisten.cuti-bersama.index');
            Route::post('/store-hadir', [AsistenCutiBersamaController::class , 'storeHadir'])->name('asisten.store-karyawan-tidak-cuti');
        }
        );
    });

Route::group(['prefix' => 'kerani', 'middleware' => ['kerani.auth']], function () {
    Route::get('/', [KeraniDashboardController::class , 'index'])->name('kerani.index');
    Route::get('/cuti', [KeraniBeritaCutiController::class , 'index'])->name('kerani.cuti.index');

    Route::post('/add-cuti', [KeraniDashboardController::class , 'submitCuti'])->name('kerani.submit-cuti');
    Route::delete('/delete-cuti/{id}', [KeraniDashboardController::class , 'delete'])->name('kerani.delete-cuti');
    Route::get('/downloadPDF/{id}', [KeraniDashboardController::class , 'downloadPermintaanCutiPDF'])->name('kerani.download.pdf');
    Route::get('/send-nontify', [KeraniDashboardController::class , 'sendNoti'])->name('send.noti');

    Route::prefix('cuti-bersama')->group(function () {
            Route::get('/', [KeraniCutiBersamaController::class , 'index'])->name('kerani.cuti-bersama');
            Route::post('/', [KeraniCutiBersamaController::class , 'storeHadir'])->name('kerani.cuti-bersama.store');
        }
        );
    });

Route::group(['prefix' => 'manajer', 'middleware' => ['manajer.auth']], function () {
    Route::get('/', [ManajerDashboardController::class , 'index'])->name('manajer.index');
});

Route::group(['prefix' => 'kabag', 'middleware' => ['kabag.auth']], function () {
    Route::get('/', [KabagDashboardController::class , 'index'])->name('kabag.index');
});

Route::group(['prefix' => 'gm', 'middleware' => ['gm.auth']], function () {
    Route::get('/', [GmDashboardController::class , 'index'])->name('gm.index');
});

Route::group(['prefix' => 'sevp', 'middleware' => ['sevp.auth']], function () {
    Route::get('/', [SevpDashboardController::class , 'index'])->name('sevp.index');
    Route::get('/cuti', [SevpBeritaCutiController::class , 'index'])->name('sevp.cuti.index');
});

Route::get('/cuti-bersama', [CutiBersamaController::class , 'getCutibersama'])->name('cuti-bersama.get');
Route::get('/api/libur-kalender', [CutiBersamaController::class , 'getLiburKalender'])->name('api.libur-kalender');

Route::prefix('notification')->group(function () {
    Route::get('/send', [NotificationController::class , 'sendNotification'])->name('notification.send');
    Route::get('/setWebhook', [NotificationController::class , 'setWebhook'])->name('telegram.setwebhook');
    Route::post('/webhook', [NotificationController::class , 'commandHandlerWebhook'])->name('telegram.commandHandlerWebhook');
});