<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use App\Console\Commands\IsiAbsensiLibur;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('absensi:libur', function () {
    $this->call(IsiAbsensiLibur::class);
})->describe('Isi absensi Libur jika hari ini hari libur');

