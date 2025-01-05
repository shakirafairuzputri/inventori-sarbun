<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Satuan;

class SatuanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Data kategori yang akan di-seed
        $satuan = [
            ['satuan_brg' => 'KG'],
            ['satuan_brg' => 'PCS'],
            ['satuan_brg' => 'Box'],
            ['satuan_brg' => 'Ikat'],
            ['satuan_brg' => 'Botol'],
            ['satuan_brg' => 'Liter'],
            ['satuan_brg' => 'Bks'],
            ['satuan_brg' => 'Pack'],
        ];

        // Loop untuk menyimpan data ke database
        foreach ($satuan as $data) {
            Satuan::create($data);
        }
    }
}
