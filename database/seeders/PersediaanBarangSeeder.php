<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\PersediaanBarang;

class PersediaanBarangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PersediaanBarang::create([
            'tanggal' => '2024-10-23',
            'barang_id' => '7',
            'stok_awal' => 1,
            'tambah' => 3,
            'kurang' => 2,
            'sisa' => 2,
        ]);
        PersediaanBarang::create([
            'tanggal' => '2024-10-24',
            'barang_id' => '7',
            'tambah' => 3,
            'kurang' => 3,
        ]);
    }
}
