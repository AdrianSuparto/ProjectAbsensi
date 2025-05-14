<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use App\Console\Commands\IsiAbsensiLibur;
use App\Console\Commands\GenerateDailyAbsensi;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

app()->booted(function () {
    $schedule = app(Illuminate\Console\Scheduling\Schedule::class);
    
    // Jadwalkan command menggunakan class langsung
    $schedule->command(IsiAbsensiLibur::class)
            //  ->dailyAt('00:05')
             ->withoutOverlapping()
             ->weekdays()
             ->description('Isi absensi libur');

    $schedule->command(GenerateDailyAbsensi::class)
            //  ->dailyAt('00:10')
             ->withoutOverlapping()
             ->weekdays()
             ->description('Generate absensi harian');
});