<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddZScoreStuntingToPenimbanganTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('penimbangans', function (Blueprint $table) {
            $table->float('z_score_stunting')->nullable()->after('z_score'); // letakkan setelah kolom z_score gizi
        });
    }

    public function down(): void
    {
        Schema::table('penimbangans', function (Blueprint $table) {
            $table->dropColumn('z_score_stunting');
        });
    }
}
