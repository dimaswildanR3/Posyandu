<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Imunisasi extends Model
{
    use HasFactory;
    protected $fillable = [
        'balita_id',
        'jenis_imunisasi',
        'tanggal_imunisasi',
        'keterangan',
    ];

    public function balita()
    {
        return $this->belongsTo(Balita::class);
    }
}
