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
        'kelas_id',
        'nama_ortu',
        'nomor_ortu',
    ];

    // Relasi ke Kelas
    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }
}
