<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\PersediaanBahan;
use Carbon\Carbon;

class ReturSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PersediaanBahan::create([
            'tanggal' => Carbon::now()->subDays(1), // Tanggal kemarin
            'nama' => 'Bahan A',
            'kategori_id' => 1,
            'satuan' => 'kg',
            'stok_awal' => 100,
            'retur_baik' => 5,
            'retur_rusak' => 2,
            'kadaluarsa' => Carbon::now()->subDays(1),
            'pembelian' => 50,
            'produksi_baik' => 20,
            'produksi_paket' => 10,
            'produksi_rusak' => 3,
            'stok_siang' => 130,
            'cek_fisik' => 125,
            'selisih' => -5,
            'tambahan_sore' => 0,
            'keterangan' => 'Semua lancar',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
    }
}
