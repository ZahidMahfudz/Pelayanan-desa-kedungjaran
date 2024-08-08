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

        $faker = Faker::create('id_ID');

        for ($i = 0; $i < 50; $i++) {
            DB::table('penduduks')->insert([
                'NIK' => $faker->unique()->numerify('################'),
                'kk' => $faker->numerify('################'),
                'nama' => $faker->name,
                'jenis_kelamin' => $faker->randomElement(['L', 'P']),
                'tempat_lahir' => $faker->city,
                'tanggal_lahir' => $faker->date(),
                'agama' => $faker->randomElement(['Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha', 'Kong Hu Cu']),
                'status_perkawinan' => $faker->randomElement(['Belum Kawin', 'Kawin', 'Cerai Hidup', 'Cerai Mati']),
                'shdk' => $faker->randomElement(['Kepala Keluarga', 'Suami', 'Istri', 'Anak', 'Orang Tua']),
                'pendidikan' => $faker->randomElement(['Tidak/Belum Sekolah', 'SD', 'SMP', 'SMA', 'D1', 'D2', 'D3', 'S1', 'S2', 'S3']),
                'pekerjaan' => $faker->jobTitle,
                'nama_ayah' => $faker->name('male'),
                'nama_ibu' => $faker->name('female'),
                'dusun' => $faker->randomElement(['Dusun 1', 'Dusun 2', 'Dusun 3', 'Dusun 4']),
                'RT' => $faker->numberBetween(1, 10),
                'RW' => $faker->numberBetween(1, 5),
                'kewarganegaraan' => $faker->randomElement(['WNI', 'WNA']),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
