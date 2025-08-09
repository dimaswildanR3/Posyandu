<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Balita extends Model
{
    use HasFactory;
    protected $table= "balitas";
    protected $primaryKey = "id";
    protected $fillable = [
        'id',
            'nama_balita',
            'tpt_lahir',
            'tgl_lahir',
            'orang_tua_id',
            'ket',
            'jenis_kelamin',
            'alamat',
            'user_id',  // jika sudah ada di database
        ];

    public function orangtua(){
        return $this->belongsTo(OrangTua::class,'orang_tua_id','id');
    }
    public function penimbangan(){
        return $this->hasMany(Penimbangan::class);
    }
    public function imunisasis()
{
    return $this->hasMany(Imunisasi::class);
}
public function user()
{
    return $this->belongsTo(User::class, 'user_id');
}


}
