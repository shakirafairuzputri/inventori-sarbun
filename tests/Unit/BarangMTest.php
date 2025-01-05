<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\PersediaanBarang;
use App\Models\Barang;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class BarangMTest extends TestCase
{
    protected $barang;
    protected $user;
    protected $produksi;
    protected $retur; 
    protected $kategori;
    protected function setUp(): void
    {
        parent::setUp();
        Auth::loginUsingId(3);

        // Buat data barang secara manual
        $this->barang = Barang::create([
            'nama_brg' => 'Barang Test',
            'kelompok' => 'Barang',
            'kategori_brg_id' => 1,
            'satuan_brg_id' => 1,
            'stok_brg' => 10,
        ]);
    }

    /** @test */
    public function test_store_barang_masuk()
    {
        $data = [
            'tanggal' => date('Y-m-d'),
            'barang_id' => $this->barang->id,
            'tambah' => 10,
        ];

        // Simpan data barang masuk
        $response = $this->post(route('pegawai.store-persediaan-brgm'), $data);

        $response->assertRedirect(route('pegawai.persediaan-brgm'));
        $this->assertDatabaseHas('persediaan_barangs', [
            'tanggal' => $data['tanggal'],
            'barang_id' => $data['barang_id'],
            'tambah' => $data['tambah'],
            'pegawai_brgm' => Auth::id(),
        ]);
    }

    /** @test */
    public function test_update_barang_masuk()
    {
        // Buat data awal barang masuk secara manual
        $persediaanBarang = PersediaanBarang::create([
            'barang_id' => $this->barang->id,
            'tanggal' => date('Y-m-d'),
            'tambah' => 10,
            'pegawai_brgm' => Auth::id(),
        ]);

        // Data untuk update
        $updateData = [
            'tanggal' => date('Y-m-d'),
            'barang_id' => $this->barang->id,
            'tambah' => 20,
        ];

        // Update barang masuk
        $response = $this->put(route('pegawai.persediaan-brgm.update', $persediaanBarang->id), $updateData);

        $response->assertRedirect(route('pegawai.persediaan-brgm'));
        $this->assertDatabaseHas('persediaan_barangs', [
            'id' => $persediaanBarang->id,
            'tanggal' => $updateData['tanggal'],
            'barang_id' => $updateData['barang_id'],
            'tambah' => $updateData['tambah'],
            'pegawai_brgm' => Auth::id(),
        ]);
    }

    /** @test */
    public function test_delete_barang_masuk()
    {
        // Buat data awal barang masuk secara manual
        $persediaanBarang = PersediaanBarang::create([
            'barang_id' => $this->barang->id,
            'tanggal' => date('Y-m-d'),
            'tambah' => 10,
            'pegawai_brgm' => Auth::id(),
        ]);

        // Hapus barang masuk
        $response = $this->delete(route('pegawai.destroy-brgm', $persediaanBarang->id));

        $response->assertRedirect(route('pegawai.persediaan-brgm'));

        // Pastikan data tidak ada lagi di database
        $this->assertDatabaseMissing('persediaan_barangs', [
            'id' => $persediaanBarang->id,
            'tambah' => 10,
        ]);
    }
}
