<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrangTua extends Model
{
    use HasFactory;
    protected $table= "orang_tuas";
    protected $primaryKey = "id";
    protected $fillable = [
        'nama',
        'tempat_lahir_ibu',
        'tanggal_lahir_ibu',
        'pendidikan',
        'pekerjaan',
        'nama_suami',
        'tempat_lahir_suami',
        'tanggal_lahir_suami',
        'pendidikan_suami',
        'pekerjaan_suami',
        'alamat',
        'kota',
        'kecamatan',
        'no_tlpn',
        'ket',
    ];
    
    public function balitas(){
        return $this->hasMany(Balita::class);
    }
}
