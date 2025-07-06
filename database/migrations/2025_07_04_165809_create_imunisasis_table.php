<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateImunisasisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('imunisasis', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('balita_id');
            $table->string('jenis_imunisasi');
            $table->date('tanggal_imunisasi');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        
            $table->foreign('balita_id')->references('id')->on('balitas')->onDelete('cascade');
        });
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('imunisasis');
    }
}
