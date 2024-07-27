<?php

namespace Database\Seeders;

use App\Models\daftarsurat;
use App\Models\namattdkades;
use App\Models\suratskd;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $userData = [
            [
                'name'=> 'operator',
                'Username' => 'operator',
                'password' => bcrypt('operator123'),
                'role' => 'operator'
            ],
        ];

        foreach($userData as $key => $val){
            User::create($val); 
        }

        // $faker = Faker::create('id_ID');
        // $niks = [];

        // // Generate 50 unique NIKs
        // while (count($niks) < 50) {
        //     $nik = $faker->unique()->numerify('################');
        //     $niks[] = $nik;
        // }

        // foreach ($niks as $nik) {
        //     DB::table('penduduks')->insert([
        //         'NIK' => $nik,
        //         'kk' => $faker->numerify('################'),
        //         'nama' => $faker->name,
        //         'jenis_kelamin' => $faker->randomElement(['L', 'P']),
        //         'tempat_lahir' => $faker->city,
        //         'tanggal_lahir' => $faker->date($format = 'Y-m-d', $max = 'now'),
        //         'agama' => $faker->randomElement(['Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha', 'Kong Hu Cu']),
        //         'status_perkawinan' => $faker->randomElement(['kawin', 'belum_kawin']),
        //         'shdk' => $faker->randomElement(['Kepala Keluarga', 'Istri', 'Anak']),
        //         'pendidikan' => $faker->randomElement(['SD', 'SMP', 'SMA', 'D1', 'D2', 'D3', 'S1', 'S2', 'S3']),
        //         'pekerjaan' => $faker->jobTitle,
        //         'nama_ayah' => $faker->name('male'),
        //         'nama_ibu' => $faker->name('female'),
        //         'dusun' => 'Dusun ' . $faker->numberBetween(1, 10),
        //         'RT' => $faker->numberBetween(1, 10),
        //         'RW' => $faker->numberBetween(1, 10),
        //         'kewarganegaraan'=> $faker->randomElement(['WNI', 'WNA']),
        //         'created_at' => now(),
        //         'updated_at' => now(),
        //     ]);
        // }

        // $ttd = [
        //     [
        //         'nama_kades'=> 'Ida Bagus Sanubari, S. Pd',
        //         'nama_gambar' => 'ttd_pak_bagus.png',
        //     ],
        // ];

        // foreach($ttd as $key => $val){
        //     namattdkades::create($val); 
        // }

    }
}
