<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\PersediaanBahan;


class PersediaanBahanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PersediaanBahan::create([
            'tanggal' => '2024-10-01',
            'produksi_id' => 3, // Make sure this ID exists in the produksi_bahans table
            'stok_siang' => 100,
            'cek_fisik' => 100,
            'selisih' => 0,
            'keterangan' => 'Test entry',
        ]);
    }
}
