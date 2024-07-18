<?php

namespace Database\Factories;

use App\Models\suratskd;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\suratskd>
 */
class SuratskdFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = suratskd::class;

    public function definition()
    {
        return [
            'keperluan' => $this->faker->sentence(),
            'daftarsurat_id' => \App\Models\DaftarSurat::factory(),
        ];
    }
}
