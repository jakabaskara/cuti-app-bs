<?php

use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\KaryawanController;
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

Route::get('/', function () {
    return view('admin.index');
});

Route::group(['prefix' => 'admin'], function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('admin.index');
    Route::get('/karyawan', [KaryawanController::class, 'index'])->name('admin.karyawan.index');
});
