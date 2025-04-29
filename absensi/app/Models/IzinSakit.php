<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class IzinSakit extends Model
{
    use HasFactory, HasUuids;
    protected $fillable = [
        'siswa_id',
        'tanggal',
        'jenis',
        'keterangan',
    ];

    // Relasi ke model Siswa
    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }
}
