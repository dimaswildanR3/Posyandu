<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAdditionalFieldsToOrangTuasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orang_tuas', function (Blueprint $table) {
            // Ibu
            $table->string('tempat_lahir_ibu')->nullable()->after('nama');
            $table->date('tanggal_lahir_ibu')->nullable()->after('tempat_lahir_ibu');

            // Suami
            $table->string('nama_suami')->nullable()->after('pekerjaan');
            $table->string('tempat_lahir_suami')->nullable()->after('nama_suami');
            $table->date('tanggal_lahir_suami')->nullable()->after('tempat_lahir_suami');
            $table->string('pendidikan_suami')->nullable()->after('tanggal_lahir_suami');
            $table->string('pekerjaan_suami')->nullable()->after('pendidikan_suami');

            // Alamat tambahan
            $table->string('kota')->nullable()->after('alamat');
            $table->string('kecamatan')->nullable()->after('kota');
            $table->string('no_tlpn')->nullable()->after('kecamatan');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orang_tuas', function (Blueprint $table) {
            $table->dropColumn([
                'tempat_lahir_ibu',
                'tanggal_lahir_ibu',
                'nama_suami',
                'tempat_lahir_suami',
                'tanggal_lahir_suami',
                'pendidikan_suami',
                'pekerjaan_suami',
                'kota',
                'kecamatan',
                'no_tlpn',
            ]);
        });
    }
}
