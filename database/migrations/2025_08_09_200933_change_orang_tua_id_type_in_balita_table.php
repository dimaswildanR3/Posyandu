<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeOrangTuaIdTypeInBalitaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('balitas', function (Blueprint $table) {
            // Drop foreign key constraint dulu
            $table->dropForeign(['orang_tua_id']); 
            // Baru drop kolom
            $table->dropColumn('orang_tua_id');
        });
    
        Schema::table('balitas', function (Blueprint $table) {
            // Buat ulang kolom sebagai varchar (string)
            $table->string('orang_tua_id')->nullable();
        });
    }
    
    public function down()
    {
        Schema::table('balitas', function (Blueprint $table) {
            $table->dropColumn('orang_tua_id');
        });
    
        Schema::table('balitas', function (Blueprint $table) {
            // Kembalikan kolom ke tipe foreign key (bigInteger unsigned)
            $table->unsignedBigInteger('orang_tua_id')->nullable();
            // Tambah foreign key lagi (sesuaikan tabel dan kolomnya)
            $table->foreign('orang_tua_id')->references('id')->on('orang_tuas')->onDelete('cascade');
        });
    }
    
    
    
}
