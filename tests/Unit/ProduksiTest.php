<?php

namespace Tests\Feature;

use App\Models\Bahan;
use App\Models\KategoriBhn;
use App\Models\PembelianBahan;
use App\Models\ProduksiBahan;
use App\Models\ReturBahan;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class ProduksiTest extends TestCase
{
    protected $bahan;
    protected $pembelian;
    protected $produksi;
    protected $retur; 
    protected $kategori; 

    protected function setUp(): void
    {
        parent::setUp();
        
        Auth::loginUsingId(3);
        
        $this->kategori = KategoriBhn::create(['kategori' => 'Kategori Test']);
        
        $this->bahan = Bahan::create([
            'nama' => 'Bahan Test', 
            'kategori_id' => $this->kategori->id,
            'satuan' => 'KG'
        ]);
        
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
        
        $this->produksi = ProduksiBahan::create([
            'tanggal' => '2024-10-30',
            'pembelian_id' => $this->pembelian->id,
            'produksi_baik' => 200,
            'produksi_paket' => 50,
            'produksi_rusak' => 5,
            'user_id' => Auth::id(),
        ]);
    }

    public function test_view_produksi()
    {
        $response = $this->get(route('pegawai.persediaan-produksi'));

        $response->assertStatus(200);
        $response->assertViewIs('pegawai.persediaan-produksi');
        $response->assertViewHas('produksiBahans');
        $response->assertViewHas('kategoris');
    }

    public function test_store_produksi()
    {
        $tanggal = '2024-10-30';

        $response = $this->post(route('pegawai.store-persediaan-produksi'), [
            'tanggal' => $tanggal,
            'pembelian_id' => $this->pembelian->id,
            'produksi_baik' => 150,
            'produksi_paket' => 30,
            'produksi_rusak' => 10,
        ]);

        $response->assertRedirect(route('pegawai.persediaan-produksi'));
        $response->assertSessionHas('success', 'Produksi bahan berhasil ditambahkan.');

        // Verifikasi pembelian_id pada produksi
        $this->assertDatabaseHas('produksi_bahans', [
            'tanggal' => $tanggal,
            'produksi_baik' => 150,
            'produksi_paket' => 30,
            'produksi_rusak' => 10,
            'pembelian_id' => $this->pembelian->id,
        ]);
    }

    public function test_update_produksi()
    {
        $response = $this->put(route('pegawai.persediaan-produksi.update', $this->produksi->id), [
            'tanggal' => '2024-10-31',
            'pembelian_id' => $this->pembelian->id,
            'produksi_baik' => 250,
            'produksi_paket' => 60,
            'produksi_rusak' => 15,
        ]);

        $response->assertRedirect(route('pegawai.persediaan-produksi'));
        $response->assertSessionHas('success', 'Produksi bahan berhasil diperbarui.');

        $this->produksi->refresh();

        $this->assertEquals('2024-10-31', $this->produksi->tanggal);
        $this->assertEquals(250, $this->produksi->produksi_baik);
        $this->assertEquals(60, $this->produksi->produksi_paket);
        $this->assertEquals(15, $this->produksi->produksi_rusak);
    }

    public function test_destroy_produksi()
    {
        $response = $this->delete(route('pegawai.persediaan-produksi.destroy', $this->produksi->id));

        $response->assertRedirect(route('pegawai.persediaan-produksi'));
        $response->assertSessionHas('success', 'Produksi bahan berhasil dihapus.');

        $this->assertDatabaseMissing('produksi_bahans', [
            'id' => $this->produksi->id,
        ]);
    }
}
