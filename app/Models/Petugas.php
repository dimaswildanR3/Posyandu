<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Petugas extends Model
{
    use HasFactory;
    protected $fillable = [
        'nama_petugas', 'tempat_lahir', 'tanggal_lahir', 'no_hp',
        'jabatan', 'pendidikan', 'lama_kerja', 'user_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
}
