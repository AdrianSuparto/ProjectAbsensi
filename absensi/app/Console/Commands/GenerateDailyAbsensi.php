<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Siswa;
use App\Models\Absensi;
use App\Models\Libur;
use Carbon\Carbon;
use Illuminate\Support\Str;

class GenerateDailyAbsensi extends Command
{
    protected $signature = 'generate:dailyabsensi';
    protected $description = 'Generate default absensi harian untuk semua siswa';

    public function handle()
    {
        $today = Carbon::today();
        
        // 1. Cek hari weekend
        if ($today->isWeekend()) {
            $this->info('[SABTU/MINGGU] Tidak generate absensi untuk hari weekend');
            return;
        }

        // 2. Cek libur nasional
        $isLibur = Libur::whereDate('tanggal', $today)->exists();
        if ($isLibur) {
            $this->info('[LIBUR NASIONAL] Tidak generate absensi untuk hari libur');
            return;
        }

        // 3. Ambil semua siswa
        $siswas = Siswa::all();
        if ($siswas->isEmpty()) {
            $this->error('Tidak ada data siswa');
            return;
        }

        // 4. Generate absensi default
        $counter = 0;
        foreach ($siswas as $siswa) {
            $created = Absensi::firstOrCreate(
                [
                    'siswa_id' => $siswa->id,
                    'tanggal' => $today
                ],
                [
                    'id' => (string) Str::uuid(),
                    'status_masuk' => 'Tidak Masuk',
                    'status_pulang' => 'Tidak Masuk',
                    'jam_masuk' => null,
                    'jam_pulang' => null
                ]
            );
            
            if ($created->wasRecentlyCreated) {
                $counter++;
            }
        }

        // 5. Output hasil
        $this->info("Berhasil generate {$counter} absensi dari {$siswas->count()} siswa");
        $this->info("Tanggal: {$today->format('d F Y')}");
        $this->info("Status default: Tidak Masuk");
    }
}