<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KelasSiswaController;


Route::get('/', function () {
    return view('welcome');
});

Route::resource('kelasSiswa', KelasSiswaController::class);
