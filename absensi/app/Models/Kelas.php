<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Kelas extends Model
{
    use HasFactory, HasUuids;
    protected $table = 'kelas';

    protected $fillable = [
        'nama',
    ];

    // Contoh relasi jika ada siswa dalam kelas
    public function siswas()
    {
        return $this->hasMany(Siswa::class);
    }
}
