<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Console\Scheduling\Schedule;
use App\Console\Commands\IsiAbsensiLibur;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })
    // ->booted(function (Application $app) {
    //     Log::info('Booted scheduler: ' . now());
    //     // Pastikan command sudah terdaftar dalam Schedule
    //     $schedule = $app->make(Schedule::class);
    //     $schedule->command('absensi:libur') // Nama command
    //     ->everyMinute(); // Atur waktu yang tepat (misal setiap jam 06:00)
    // })
    ->create();
