<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAlamatToBalitasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('balitas', function (Blueprint $table) {
            $table->text('alamat')->nullable()->after('orang_tua_id');
        });
    }
    
    public function down()
    {
        Schema::table('balitas', function (Blueprint $table) {
            $table->dropColumn('alamat');
        });
    }
    
}
