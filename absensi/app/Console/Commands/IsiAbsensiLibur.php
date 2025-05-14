<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Absensi;
use Carbon\Carbon;
use App\Models\Libur;
use App\Models\Siswa;
use Illuminate\Support\Str;

class IsiAbsensiLibur extends Command
{
    protected $signature = 'absensi:libur';
    protected $description = 'Isi absensi Libur jika hari ini hari libur';

    public function handle()
    {
        $today = Carbon::today();

        // Skip weekend
        if ($today->isWeekend()) {
            $this->info('Hari ini weekend, tidak perlu isi absensi libur.');
            return;
        }

        // Cek libur nasional
        $isLibur = Libur::whereDate('tanggal', $today)->exists();

        if ($isLibur) {
            $siswas = Siswa::all();

            foreach ($siswas as $siswa) {
                // Gunakan firstOrCreate untuk hindari duplikasi
                Absensi::firstOrCreate(
                    [
                        'siswa_id' => $siswa->id,
                        'tanggal' => $today
                    ],
                    [
                        'id' => (string) Str::uuid(),
                        'status_masuk' => 'Libur',
                        'status_pulang' => 'Libur',
                        'jam_masuk' => null,
                        'jam_pulang' => null
                    ]
                );
            }

            $this->info('Berhasil mengisi absensi libur untuk ' . $siswas->count() . ' siswa.');
        } else {
            $this->info('Hari ini bukan hari libur.');
        }
    }
}