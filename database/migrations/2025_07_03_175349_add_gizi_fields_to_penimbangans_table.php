<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddGiziFieldsToPenimbangansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('penimbangans', function (Blueprint $table) {
            $table->integer('umur')->nullable()->after('tb'); // umur dalam bulan
            $table->string('status_gizi')->nullable()->after('umur'); // Gizi Baik, Kurang, Buruk
            $table->float('z_score')->nullable()->after('status_gizi'); // opsional
        });
    }

    public function down()
    {
        Schema::table('penimbangans', function (Blueprint $table) {
            $table->integer('umur')->nullable()->after('tb'); // umur dalam bulan
            $table->string('status_gizi')->nullable()->after('umur'); // Gizi Baik, Kurang, Buruk
            $table->float('z_score')->nullable()->after('status_gizi'); // opsional
        });
    }
}
