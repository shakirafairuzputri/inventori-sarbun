<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\KategoriBhn;

class KategoriBahanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Data kategori yang akan di-seed
        $kategori = [
            ['kategori' => 'Unggas'],
            ['kategori' => 'Daging'],
            ['kategori' => 'Ikan'],
        ];

        // Loop untuk menyimpan data ke database
        foreach ($kategori as $data) {
            KategoriBhn::create($data);
        }
    }
}
