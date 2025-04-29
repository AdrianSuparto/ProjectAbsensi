<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KelasSiswa extends Model
{
    //
    use HasFactory, HasUuids;

    protected $fillable = ['nama'];

    public function siswa()
    {
        return $this->hasMany(Siswa::class, 'kelasSiswa_id');
    }
}
