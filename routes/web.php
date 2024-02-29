<?php

use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\asisten\AsistenDashboardController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\manajer\ManajerDashboardController;
use App\Http\Controllers\pic\PICDashboardController;
use App\Http\Controllers\kerani\KeraniDashboardController;
use App\Http\Controllers\SisaCutiController;
use Illuminate\Support\Facades\Route;

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

Route::get('/', [AdminDashboardController::class, 'index']);

Route::group(['prefix' => 'admin'], function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('admin.index');
    Route::get('/karyawan', [KaryawanController::class, 'index'])->name('admin.karyawan.index');
    Route::get('/sisacuti', [SisaCutiController::class, 'index'])->name('admin.sisacuti.index');

    Route::get('/downloadPDF', [AdminDashboardController::class, 'downloadPermintaanCutiPDF'])->name('admin.download.pdf');

    Route::post('/ajukanCuti', [AdminDashboardController::class, 'tambahCuti'])->name('admin.tambahCuti');
});

Route::group(['prefix' => 'pic'], function () {
    Route::get('/', [PICDashboardController::class, 'index'])->name('pic.index');
});

Route::group(['prefix' => 'asisten'], function () {
    Route::get('/', [AsistenDashboardController::class, 'index'])->name('asisten.index');
});

Route::group(['prefix' => 'manajer'], function () {
    Route::get('/', [ManajerDashboardController::class, 'index'])->name('manajer.index');
    Route::get('/karyawan', [KaryawanController::class, 'index'])->name('manajer.karyawan.index');
    Route::get('/sisacuti', [SisaCutiController::class, 'index'])->name('manajer.sisacuti.index');

    Route::get('/downloadPDF', [ManajerDashboardController::class, 'downloadPermintaanCutiPDF'])->name('manajer.download.pdf');

    Route::post('/ajukanCuti', [ManajerDashboardController::class, 'tambahCuti'])->name('manajer.tambahCuti');
});

Route::group(['prefix' => 'kerani'], function () {
    Route::get('/', [KeraniDashboardController::class, 'index'])->name('kerani.index');
});
