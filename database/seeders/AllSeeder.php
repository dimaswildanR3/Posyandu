<?php

namespace Database\Seeders;

use App\Models\Balita;
use App\Models\OrangTua;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

class AllSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create('id_ID');
        User::updateOrCreate([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('1234'),
        ]);
        // Menginput Data Palsu Ke Database
       for ($i = 0; $i <= 50; $i++) {
    if ($i % 2) {
        $pendidikan = 'SMA';
        $jenis = 'Laki-Laki';
    } else {
        $pendidikan = 'Sarjana';
        $jenis = 'Perempuan';
    }

    // Simpan data orang tua dan ambil ID-nya
    $orangTua = OrangTua::create([
        'nama' => $faker->name(),
        'pendidikan' => $pendidikan,
        'pekerjaan' => $faker->jobTitle(),
        'alamat' => $faker->address(),
        'ket' => null
    ]);

    // Gunakan ID yang valid dari orang tua yang baru dibuat
    Balita::create([
        'nama_balita' => $faker->name,
        'tpt_lahir' => $faker->city,
        'tgl_lahir' => $faker->date('Y-m-d'),
        'orang_tua_id' => $orangTua->id, // ← fix di sini
        'jenis_kelamin' => $jenis,
        'ket' => null,
    ]);
}
    }
}
