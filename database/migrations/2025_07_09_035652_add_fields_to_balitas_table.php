<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsToBalitasTable extends Migration
{
    public function up()

        {
            Schema::table('balitas', function (Blueprint $table) {
                $table->string('nik_anak')->nullable()->after('id');
            });
        }
    
        public function down()
        {
            Schema::table('balitas', function (Blueprint $table) {
                $table->dropColumn('nik_anak');
            });
        }
}
