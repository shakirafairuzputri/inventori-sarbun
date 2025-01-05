<?php
namespace App\Imports;

use App\Models\Barang;
use App\Models\KategoriBrg;
use App\Models\Satuan;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class BarangImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        // Cek apakah kategori dengan nama yang sama ada
        $kategori = KategoriBrg::where('kategori_brg', $row['kategori'])->first();
        if (!$kategori) {
            // Log pesan jika kategori tidak ditemukan
            Log::warning("Kategori '{$row['kategori']}' tidak ditemukan. Data tidak diimpor.");
            return null; // Tidak mengimpor data jika kategori tidak ada
        }

        // Cek apakah satuan dengan nama yang sama ada
        $satuan = Satuan::where('satuan_brg', $row['satuan'])->first();
        if (!$satuan) {
            // Log pesan jika satuan tidak ditemukan
            Log::warning("Satuan '{$row['satuan']}' tidak ditemukan. Data tidak diimpor.");
            return null; // Tidak mengimpor data jika satuan tidak ada
        }

        // Cek apakah barang dengan nama dan kategori yang sama sudah ada di database
        $existingBarang = Barang::where('nama_brg', $row['nama'])
            ->where('kategori_brg_id', $kategori->id)
            ->first();

        if ($existingBarang) {
            // Log pesan jika barang duplikat ditemukan
            Log::info("Barang dengan nama '{$row['nama']}' dan kategori '{$row['kategori']}' sudah ada. Data tidak diimpor.");
            return null; // Tidak mengimpor duplikat
        }

        // Jika barang belum ada, buat data baru
        return new Barang([
            'nama_brg' => $row['nama'],
            'kelompok' => $row['kelompok'],
            'kategori_brg_id' => $kategori->id, // Gunakan ID kategori yang ditemukan
            'satuan_brg_id' => $satuan->id, // Gunakan ID satuan yang ditemukan
            'stok_brg' => $row['stok'] ?? 0,
        ]);
    }
}
