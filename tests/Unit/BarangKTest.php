<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\PersediaanBarang;
use App\Models\Barang;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class BarangKTest extends TestCase
{
    protected $barang;
    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        Auth::loginUsingId(3);

        // Create manual data for barang
        $this->barang = Barang::create([
            'nama_brg' => 'Barang Test',
            'kelompok' => 'Barang',
            'kategori_brg_id' => 1,
            'satuan_brg_id' => 1,
            'stok_brg' => 10,
        ]);
    }

    /** @test */
    public function test_store_barang_keluar()
    {
        // Membuat barang secara manual
        $barang = Barang::create([
            'nama_brg' => 'Barang Aja',
            'kelompok' => 'Barang',
            'kategori_brg_id' => 1,
            'satuan_brg_id' => 1,
            'stok_brg' => 2,
        ]);

        // Siapkan data untuk permintaan
        $data = [
            'tanggal' => now()->format('Y-m-d'), // Menggunakan tanggal hari ini
            'barang_id' => $barang->id, // Menggunakan ID barang yang baru dibuat
            'kurang' => 5,
        ];

        // Store barang keluar
        $response = $this->post(route('pegawai.store-persediaan-brgk'), $data);

        // Assert redirection dan database memiliki data yang benar
        $response->assertRedirect(route('pegawai.persediaan-brgk'));
        // $this->assertDatabaseHas('persediaan_barangs', [
        //     'tanggal' => $data['tanggal'],
        //     'barang_id' => $data['barang_id'],
        //     'kurang' => $data['kurang'],
        //     'pegawai_brgk' => Auth::id(), // Memastikan ID pengguna yang sesuai
        // ]);
    }

    /** @test */
    public function test_update_barang_keluar()
    {
        // Create initial barang keluar data manually
        $persediaanBarang = PersediaanBarang::create([
            'tanggal' => date('Y-m-d'),
            'barang_id' => $this->barang->id,
            'kurang' => 5,
            'pegawai_brgk' => Auth::id(),
        ]);

        // Data for updating barang keluar
        $updateData = [
            'tanggal' => date('Y-m-d'),
            'barang_id' => $this->barang->id,
            'kurang' => 8,
        ];

        // Update barang keluar
        $response = $this->put(route('pegawai.persediaan-brgk.update', $persediaanBarang->id), $updateData);

        $response->assertRedirect(route('pegawai.persediaan-brgk'));
        $this->assertDatabaseHas('persediaan_barangs', [
            'id' => $persediaanBarang->id,
            'tanggal' => $updateData['tanggal'],
            'barang_id' => $updateData['barang_id'],
            'kurang' => $updateData['kurang'],
            'pegawai_brgk' => Auth::id(),
        ]);
    }

    /** @test */
    public function test_delete_barang_keluar()
    {
        // Create initial barang keluar data manually
        $persediaanBarang = PersediaanBarang::create([
            'tanggal' => date('Y-m-d'),
            'barang_id' => $this->barang->id,
            'kurang' => 5,
            'pegawai_brgk' => Auth::id(),
        ]);

        // Delete barang keluar
        $response = $this->delete(route('pegawai.destroy-brgk', $persediaanBarang->id));

        $response->assertRedirect(route('pegawai.persediaan-brgk'));

        // Ensure data is no longer in the database
        $this->assertDatabaseMissing('persediaan_barangs', [
            'id' => $persediaanBarang->id,
            'kurang' => 5,
        ]);
    }
}
