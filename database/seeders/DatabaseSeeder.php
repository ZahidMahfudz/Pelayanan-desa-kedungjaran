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

    }
}
