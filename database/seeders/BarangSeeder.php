<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Barang;
use Faker\Factory as Faker;

class BarangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Inisialisasi Faker
        $faker = Faker::create();

        // Loop untuk membuat 100 data barang
        foreach (range(1, 100) as $index) {
            Barang::create([
                'nama_brg' => 'Barang ' . $index, // Menetapkan nama unik untuk tiap barang
                'kelompok' => $faker->randomElement(['Bahan', 'Barang']), // Kelompok barang
                'kategori_brg_id' => $faker->numberBetween(1, 3), // Asumsi kategori memiliki ID antara 1 dan 5
                'satuan_brg_id' => $faker->numberBetween(1, 3), // Asumsi satuan memiliki ID antara 1 dan 3
                'stok_brg' => $faker->numberBetween(5, 20), // Stok barang antara 10 dan 1000
            ]);
        }
    }
}
