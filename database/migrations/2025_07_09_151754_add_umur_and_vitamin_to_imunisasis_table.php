<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUmurAndVitaminToImunisasisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('imunisasis', function (Blueprint $table) {
            $table->integer('umur')->nullable()->after('tanggal_imunisasi');
            $table->string('vitamin')->nullable()->after('umur');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('imunisasis', function (Blueprint $table) {
            $table->dropColumn(['umur', 'vitamin']);
        });
    }
}
