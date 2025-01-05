<?php
namespace App\Imports;

use App\Models\Bahan;
use App\Models\KategoriBhn; // Pastikan model Kategori diimpor
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Log;

class BahanImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        try {
            Log::info('Processing row: ' . json_encode($row));

            // Validasi data tidak kosong
            if (empty($row['nama']) || empty($row['kategori']) || empty($row['satuan'])) {
                Log::warning('Skipped row due to incomplete data: ' . json_encode($row));
                return null;
            }

            // Mencari kategori berdasarkan nama kategori
            $kategori = KategoriBhn::where('kategori', $row['kategori'])->first();
            if (!$kategori) {
                Log::warning('Skipped row because kategori not found: ' . $row['kategori']);
                return null;
            }

            // Mengecek apakah bahan sudah ada
            $existingBahan = Bahan::where('nama', $row['nama'])
                ->where('kategori_id', $kategori->id)
                ->first();

            // Jika sudah ada, lewati baris ini
            if ($existingBahan) {
                Log::info('Skipped row because bahan already exists: ' . json_encode($row));
                return null;
            }

            // Menambahkan bahan baru jika belum ada
            Log::info('Creating new bahan: ' . $row['nama']);
            return new Bahan([
                'nama' => $row['nama'],
                'kategori_id' => $kategori->id,
                'satuan' => $row['satuan'],
                'stok' => $row['stok'] ?? 0,
            ]);
        } catch (\Exception $e) {
            // Menangani error dan mencatatnya
            Log::error('Error processing row: ' . $e->getMessage());
            return null;
        }
    }
}
