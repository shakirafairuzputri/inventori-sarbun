<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\KategoriBrg;

class KategoriBarangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Data kategori yang akan di-seed
        $kategori_brg = [
            ['kategori_brg' => 'Plastik & Kemasan'],
            ['kategori_brg' => 'Sayur & Buah'],
            ['kategori_brg' => 'Bumbu & Rempah'],
            ['kategori_brg' => 'Alat Makan & Minum'],
            ['kategori_brg' => 'Bahan Baku'],
            ['kategori_brg' => 'Produk Minuman'],
            ['kategori_brg' => 'Pembersih'],
        ];

        // Loop untuk menyimpan data ke database
        foreach ($kategori_brg as $data) {
            KategoriBrg::create($data);
        }
    }
}
