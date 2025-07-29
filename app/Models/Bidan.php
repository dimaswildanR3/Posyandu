<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Bidan extends Model

{
    use HasFactory;
    protected $table = 'bidans';


    protected $fillable = [
        'nama_lengkap',
        'tanggal_lahir',
        'tempat_lahir',
        'no_hp',
        'user_id',
        'pendidikan_terakhir',
    ];

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
