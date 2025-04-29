<?php

use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\IzinSakitController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KelasSiswaController;
use App\Http\Controllers\LiburController;
use App\Http\Controllers\SiswaController;

Route::get('/', function () {
    return view('dashboard');
});

Route::resource('kelasSiswa', KelasSiswaController::class);
Route::resource('siswa', SiswaController::class);
Route::resource('libur', LiburController::class);
Route::resource('izinSakit', IzinSakitController::class);
Route::resource('absensi', AbsensiController::class)->except(['show']);


Route::get('/absensi/scan', [AbsensiController::class, 'showScanPage'])->name('absensi.scan.page');
Route::post('/absensi/scan', [AbsensiController::class, 'scan'])->name('absensi.scan');
