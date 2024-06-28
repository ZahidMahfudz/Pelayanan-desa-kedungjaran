<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $userData = [
            [
                'name'=> 'admin',
                'Username' => 'admin',
                'password' => bcrypt('admin123'),
                'role' => 'admin'
            ],
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

        DB::table('penduduks')->insert([
            [
                'NIK' => '1234567890123456',
                'nama_depan' => 'Budi',
                'nama_belakang' => 'Santoso',
                'tanggal_lahir' => '1980-01-01',
                'desa' => 'Desa Maju',
                'RT' => 1,
                'RW' => 1,
                'status' => 'menetap',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'NIK' => '2345678901234567',
                'nama_depan' => 'Siti',
                'nama_belakang' => 'Aisyah',
                'tanggal_lahir' => '1985-02-02',
                'desa' => 'Desa Sejahtera',
                'RT' => 2,
                'RW' => 2,
                'status' => 'pindah',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'NIK' => '3456789012345678',
                'nama_depan' => 'Ahmad',
                'nama_belakang' => 'Zulkarnain',
                'tanggal_lahir' => '1990-03-03',
                'desa' => 'Desa Makmur',
                'RT' => 3,
                'RW' => 3,
                'status' => 'meninggal',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
