<?php

namespace Tests\Feature;

use App\Models\Bahan;
use App\Models\KategoriBhn;
use App\Models\PembelianBahan;
use App\Models\ReturBahan;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class PembelianTest extends TestCase
{
    protected $bahan;
    protected $pembelian;
    protected $retur; 
    protected $kategori; 
    protected function setUp(): void
    {
        parent::setUp();
        // Setup required data
        Auth::loginUsingId(8);
        $this->kategori = KategoriBhn::create(['kategori' => 'Kategori Test']); // Membuat kategori baru
        $this->bahan = Bahan::create([
            'nama' => 'Bahan Test', 
            'kategori_id' => $this->kategori->id,
            'satuan' => 'KG' // Menambahkan nilai untuk kolom 'satuan'
        ]); // Sesuaikan dengan kategori yang ada
        $this->retur = ReturBahan::create([
            'tanggal' => '2024-10-30',
            'bahan_id' => $this->bahan->id,
            'retur_baik' => 0,
            'retur_rusak' => 0,
            'user_id' => Auth::id(),
        ]);
        $this->pembelian = PembelianBahan::create([
            'tanggal' => '2024-10-30',
            'retur_id' => $this->retur->id,
            'pembelian' => 100,
            'tambahan_sore' => 50,
            'user_id' => Auth::id(),
        ]);
       
    }

    public function test_view_pembelian()
    {
        $response = $this->get(route('pegawai.persediaan-beli'));

        $response->assertStatus(200);
        $response->assertViewIs('pegawai.persediaan-beli');
        $response->assertViewHas('pembelianBahans');
        $response->assertViewHas('kategoris');
    }

    public function test_store_pembelian()
    {
        // Siapkan data yang diperlukan untuk pengujian
        $tanggal = '2024-10-30';

        // Buat kategori yang valid dengan nilai untuk kolom kategori
        $kategori = KategoriBhn::create([
            'kategori' => 'Kategori Contoh', // Pastikan Anda memberikan nilai untuk kategori
        ]);

        // Buat bahan yang valid
        $bahan = Bahan::create([
            'nama' => 'Bahan Contoh',
            'kategori_id' => $kategori->id, // Gunakan kategori yang baru dibuat
            'satuan' => 'kg', // Misalnya, satuan
        ]);

        // Simulasikan permintaan POST untuk menyimpan pembelian
        $response = $this->post(route('pegawai.store-persediaan-beli'), [
            'tanggal' => $tanggal,
            'bahan_id' => $bahan->id, // Tambahkan bahan_id yang valid
            'pembelian' => 200,
            'tambahan_sore' => 100,
        ]);

        // Pastikan berhasil melakukan redirect
        $response->assertRedirect(route('pegawai.persediaan-beli'));
        $response->assertSessionHas('success', 'Pembelian bahan berhasil ditambahkan.');

        // Periksa bahwa data ada di database
        $this->assertDatabaseHas('pembelian_bahans', [
            'tanggal' => $tanggal,
            'pembelian' => 200,
            'tambahan_sore' => 100,
            // Pastikan kita menyimpan dengan retur_id yang baru dibuat
            'retur_id' => ReturBahan::where('bahan_id', $bahan->id)
                                   ->where('tanggal', $tanggal)
                                   ->first()->id,
        ]);
    }

    public function test_update_pembelian()
    {
        // Update data
        $response = $this->put(route('pegawai.persediaan-beli.update', $this->pembelian->id), [
            'tanggal' => '2024-10-31',
            'bahan_id' => $this->bahan->id,
            'pembelian' => 300,
            'tambahan_sore' => 150,
        ]);

        $response->assertRedirect(route('pegawai.persediaan-beli'));
        $response->assertSessionHas('success', 'Pembelian bahan berhasil diperbarui.');

        $this->pembelian->refresh(); // Refresh the model instance

        $this->assertEquals('2024-10-31', $this->pembelian->tanggal);
        $this->assertEquals(300, $this->pembelian->pembelian);
        $this->assertEquals(150, $this->pembelian->tambahan_sore);
    }

    public function test_destroy_pembelian()
    {
        // Delete the data
        $response = $this->delete(route('pegawai.persediaan-beli.destroy', $this->pembelian->id));

        $response->assertRedirect(route('pegawai.persediaan-beli'));
        $response->assertSessionHas('success', 'Pembelian bahan berhasil dihapus.');

        $this->assertDatabaseMissing('pembelian_bahans', [
            'id' => $this->pembelian->id,
        ]);
    }
}
