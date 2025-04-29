<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Siswa extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'siswas';

    protected $fillable = [
        'no_kartu',
        'nis',
        'nama',
        'kelas_siswa_id',
        'nama_ortu',
        'nomor_ortu',
    ];

    // Relasi ke Kelas Siswa
    public function kelasSiswa()
    {
        return $this->belongsTo(KelasSiswa::class);
    }

    public function izinSakit()
    {
        return $this->hasMany(IzinSakit::class, 'siswa_id');
    }

    public function absensi()
    {
        return $this->hasMany(Absensi::class, 'siswa_id');
    }
}
