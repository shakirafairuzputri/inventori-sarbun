<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Bahan;
use Faker\Factory as Faker;

class BahanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Initialize Faker
        $faker = Faker::create();

        // Loop untuk membuat 100 data
        foreach (range(1, 150) as $index) {
            Bahan::create([
                'nama' => 'Bahan ' . $index, // Nama bahan mengikuti pola Bahan 1, Bahan 2, dst.
                'kategori_id' => $faker->numberBetween(1, 3), // Asumsi ada 3 kategori
                'satuan' => $faker->randomElement(['KG', 'POT']), // Pilihan satuan
                'stok' => $faker->numberBetween(10, 20), // Stok bahan
            ]);
        }
    }
}
