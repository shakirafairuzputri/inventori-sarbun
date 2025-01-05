<?php

namespace Tests\Feature;

use App\Models\Bahan;
use App\Models\KategoriBhn;
use App\Models\ReturBahan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class ReturTest extends TestCase
{
    protected $bahanId; // Deklarasikan properti di sini
    protected function setUp(): void
    {
        parent::setUp();
        // Buat kategori dan bahan untuk pengujian
        $kategori = KategoriBhn::create(['kategori' => 'Kategori Test']);
        $bahan = Bahan::create([
            'nama' => 'Bahan Test',
            'kategori_id' => $kategori->id,
            'satuan' => 'pcs', // Pastikan untuk menambahkan satuan
        ]);

        $this->bahanId = $bahan->id; // Simpan ID bahan untuk digunakan di test lain

        Auth::loginUsingId(8);
    }


    /** @test */
    public function test_view_retur_bahan()
    {
        $response = $this->get(route('pegawai.persediaan-retur'));
        $response->assertStatus(200);
        $response->assertViewIs('pegawai.persediaan-retur');
    }

    /** @test */
    public function test_store_retur_bahan()
    {
        $response = $this->post(route('pegawai.persediaan-retur.store'), [
            'tanggal' => '2024-10-30',
            'bahan_id' => $this->bahanId, // Pastikan menggunakan ID bahan yang benar
            'retur_baik' => 10,
            'retur_rusak' => 5,
        ]);

        $response->assertRedirect(route('pegawai.persediaan-retur'));
        $response->assertSessionHas('success', 'Retur bahan berhasil ditambahkan.');

        // Pastikan data tersimpan di database
        $this->assertDatabaseHas('retur_bahans', [
            'tanggal' => '2024-10-30',
            'bahan_id' => $this->bahanId, // Menggunakan ID bahan yang benar
            'retur_baik' => 10,
            'retur_rusak' => 5,
            'user_id' => Auth::id(),
            'status' => 'Belum Dikembalikan',
        ]);
    }

    /** @test */
    public function test_update_retur_bahan()
    {
        $returBahan = ReturBahan::create([
            'tanggal' => '2024-10-30',
            'bahan_id' => $this->bahanId,
            'retur_baik' => 10,
            'retur_rusak' => 5,
            'user_id' => Auth::id(),
            'status' => 'Belum Dikembalikan',
        ]);

        $response = $this->put(route('pegawai.persediaan-retur.update', $returBahan->id), [
            'tanggal' => '2024-10-31',
            'bahan_id' => $this->bahanId,
            'retur_baik' => 15,
            'retur_rusak' => 8,
        ]);

        $response->assertRedirect(route('pegawai.persediaan-retur'));
        $response->assertSessionHas('success', 'Retur bahan berhasil diperbarui.');

        $this->assertDatabaseHas('retur_bahans', [
            'id' => $returBahan->id,
            'tanggal' => '2024-10-31',
            'bahan_id' => $this->bahanId,
            'retur_baik' => 15,
            'retur_rusak' => 8,
        ]);
    }

    /** @test */
    public function test_delete_retur_bahan()
    {
        $returBahan = ReturBahan::create([
            'tanggal' => '2024-10-30',
            'bahan_id' => $this->bahanId,
            'retur_baik' => 10,
            'retur_rusak' => 5,
            'user_id' => Auth::id(),
            'status' => 'Belum Dikembalikan',
        ]);

        $response = $this->delete(route('pegawai.persediaan-retur.destroy', $returBahan->id));
        $response->assertRedirect(route('pegawai.persediaan-retur'));
        $response->assertSessionHas('success', 'Retur bahan berhasil dihapus.');

        // Periksa apakah data berhasil dihapus
        $this->assertDatabaseMissing('retur_bahans', [
            'id' => $returBahan->id,
        ]);
    }
}
