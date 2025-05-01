<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Absensi;
use Carbon\Carbon;
use App\Models\Libur;

class IsiAbsensiLibur extends Command
{
    protected $signature = 'absensi:libur';
    protected $description = 'Isi absensi Libur jika hari ini hari libur';

    public function handle()
    {
        \Log::info('Command absensi:libur dijalankan pada: ' . now());
        // Cek apakah hari ini hari libur
        $tanggalHariIni = Carbon::today()->format('Y-m-d');
        $isLibur = $this->cekHariLibur($tanggalHariIni);

        if ($isLibur) {
            // Dapatkan siswa-siswa yang seharusnya libur
            $siswa = \App\Models\Siswa::all(); // Ambil semua data siswa, atau sesuaikan query

            foreach ($siswa as $s) {
                $sudahAda = Absensi::where('siswa_id', $s->id)
                            ->where('tanggal', $tanggalHariIni)
                            ->exists();
            
                if (!$sudahAda) {
                    Absensi::create([
                        'siswa_id' => $s->id,
                        'tanggal' => $tanggalHariIni,
                        'jam_masuk' => null,
                        'status_masuk' => 'libur',
                        'jam_pulang' => null,
                        'status_pulang' => 'libur',
                    ]);
                }
            }
            $this->info('Absensi libur sudah diisi.');
        } else {
            $this->info('Hari ini bukan hari libur.');
        }
    }

    private function cekHariLibur($tanggal)
    {
        // Mengecek apakah tanggal ada di tabel liburs
        return Libur::where('tanggal', $tanggal)->exists();
    }

}
