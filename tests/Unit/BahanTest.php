<?php

namespace Tests\Unit;

use App\Models\Bahan;
use App\Models\User;
use Tests\TestCase;

class BahanTest extends TestCase
{
    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Ambil pengguna supervisor yang sudah ada dari database
        $this->user = User::firstOrCreate([
            'email' => 'supervisor@example.com', // Pastikan email ini sudah ada di database Anda
        ], [
            'name' => 'Supervisor', // Anda bisa mengisi ini jika pengguna tidak ada
            'password' => bcrypt('password123'), // Anda bisa mengisi password jika pengguna tidak ada
            'role' => 'supervisor',
            'status' => 'Aktif',
        ]);
        
        // Login sebagai supervisor
        $this->actingAs($this->user);
    }

    public function test_store_bahan()
    {
        $response = $this->post(route('supervisor.store-bahan'), [
            'nama' => 'Bahan A',
            'kategori_id' => 1, // Pastikan kategori ini ada
            'satuan' => 'kg',
            'stok' => 100,
        ]);

        // Cek respons dan status
        $response->assertStatus(302);
        $response->assertRedirect(route('supervisor.daftar-bhn'));
        $response->assertSessionHas('success', 'Data Bahan Berhasil Ditambahkan');

        // Pastikan data disimpan di database
        $this->assertDatabaseHas('bahans', [
            'nama' => 'Bahan A',
            'satuan' => 'kg',
            'stok' => 100,
        ]);
    }

    public function test_view_bahan()
    {
        // Membuat data bahan secara manual
        Bahan::create([
            'nama' => 'Bahan Uji',
            'kategori_id' => 1,
            'satuan' => 'kg',
            'stok' => 10,
        ]);

        $response = $this->get(route('supervisor.daftar-bhn'));

        $response->assertStatus(200);
        $response->assertSee('Bahan Uji'); // Pastikan nama bahan muncul di halaman
    }

    public function test_update_bahan()
    {
        // Membuat data bahan untuk diupdate
        $bahan = Bahan::create([
            'nama' => 'Bahan Lama',
            'kategori_id' => 1,
            'satuan' => 'kg',
            'stok' => 10,
        ]);

        // Melakukan update
        $response = $this->put(route('supervisor.update-bahan', $bahan->id), [
            'nama' => 'Bahan Baru Diperbarui',
            'kategori_id' => 1,
            'satuan' => 'liter',
            'stok' => 20,
        ]);

        $response->assertStatus(302);
        $response->assertRedirect(route('supervisor.daftar-bhn'));
        $this->assertDatabaseHas('bahans', [
            'nama' => 'Bahan Baru Diperbarui',
            'stok' => 20,
        ]);
    }

    public function test_delete_bahan()
    {
        // Membuat data bahan untuk dihapus
        $bahan = Bahan::create([
            'nama' => 'Bahan Untuk Dihapus',
            'kategori_id' => 1,
            'satuan' => 'kg',
            'stok' => 10,
        ]);

        $response = $this->delete(route('supervisor.destroy-bahan', $bahan->id));

        $response->assertStatus(302);
        $response->assertRedirect(route('supervisor.daftar-bhn'));
        $this->assertDatabaseMissing('bahans', [
            'id' => $bahan->id,
            'nama' => 'Bahan Untuk Dihapus',
        ]);    }

    // public function test_create_bahan_fail_validation()
    // {
    //     // Kirim request POST tanpa data yang diperlukan
    //     $response = $this->post(route('supervisor.store-bahan'), [
    //         'nama' => '', // Nama tidak boleh kosong
    //         'kategori_id' => '', // Kategori tidak boleh kosong
    //         'satuan' => '',
    //         'stok' => '',
    //     ]);

    //     // Cek respons dan status
    //     $response->assertStatus(302);
    //     $response->assertSessionHasErrors(['nama', 'kategori_id', 'satuan', 'stok']);
    // }
}
