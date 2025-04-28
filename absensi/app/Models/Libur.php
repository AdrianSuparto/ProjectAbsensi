<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Libur extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'liburs';

    protected $fillable = [
        'tanggal',
        'keterangan',
    ];
}
