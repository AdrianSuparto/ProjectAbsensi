<?php

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


