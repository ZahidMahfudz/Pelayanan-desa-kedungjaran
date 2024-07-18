<?php

namespace Database\Factories;

use App\Models\daftarsurat;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\daftarsurat>
 */
class DaftarsuratFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = daftarsurat::class;

    public function definition(): array
    {
        $date = $this->faker->dateTimeThisYear();
        $year = $date->format('Y');
        $month = $date->format('m');
        $day = $date->format('d');
        $prefix = "NS";
        $number = str_pad($this->faker->unique()->numberBetween(1, 9999), 4, '0', STR_PAD_LEFT);
        $nomor_surat = "{$prefix}/{$year}/{$month}/{$day}/{$number}";

        return [
            'nomor_surat' => $nomor_surat,
            'tanggal_surat' => $date->format('Y-m-d'),
            'jenis_surat' => $this->faker->word(),
            'pemohon' => $this->faker->name(),
            'status_surat' => $this->faker->randomElement(['belum_cetak', 'sudah_cetak']),
            'status_ttd' => $this->faker->randomElement(['belum', 'sudah']),
        ];
    }
}
