<?php

namespace Tests\Feature;

use App\Models\KategoriBhn;
use App\Models\User;
use Tests\TestCase;

class KategoriBahanTest extends TestCase
{
    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Ambil pengguna supervisor yang sudah ada dari database
        $this->user = User::firstOrCreate([
            'email' => 'supervisor@example.com', // Pastikan email ini sudah ada di database Anda
        ], [
            'name' => 'Supervisor',
            'password' => bcrypt('password123'),
            'role' => 'supervisor',
            'status' => 'Aktif',
        ]);

        // Login sebagai supervisor
        $this->actingAs($this->user);
    }

    public function test_view_kategori_bhn()
    {
        $response = $this->get(route('supervisor.kategori-bhn'));

        $response->assertStatus(200);
        $response->assertViewIs('supervisor.kategori-bhn');
        $response->assertSee('Kategori Bahan'); // Sesuaikan dengan elemen yang ada di view
    }

    public function test_store_kategori_bhn_success()
    {
        $response = $this->post(route('supervisor.store-kategoribhn'), [
            'kategori' => 'Kategori A',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect(route('supervisor.kategori-bhn'));
        $response->assertSessionHas('success', 'Data Berhasil Ditambahkan');
        $this->assertDatabaseHas('kategori_bhns', [
            'kategori' => 'Kategori A',
        ]);
    }

    public function test_store_kategori_bhn_fail_validation()
    {
        // Kirim request POST tanpa kategori
        $response = $this->post(route('supervisor.store-kategoribhn'), [
            'kategori' => '', // Nama kategori tidak boleh kosong
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('kategori');
    }

    public function test_store_kategori_bhn_fail_existing_data()
    {
        // Buat data kategori terlebih dahulu
        KategoriBhn::create(['kategori' => 'cumi']);
        
        // Pastikan data kategori sudah ada
        $this->assertDatabaseHas('kategori_bhns', ['kategori' => 'cumi']);

        // Kirimkan request POST untuk kategori yang sama
        $response = $this->post(route('supervisor.store-kategoribhn'), [
            'kategori' => 'cumi', // Data sudah ada
        ]);

        // Periksa apakah pengalihan dan pesan kesalahan sesuai
        $response->assertStatus(302);
        $response->assertRedirect(route('supervisor.tambah-kategori-bhn'));
        $response->assertSessionHas('errors');
        // Pastikan data kategori masih ada di database
        $this->assertDatabaseHas('kategori_bhns', ['kategori' => 'cumi']);
    }


    public function test_destroy_kategori_bhn_success()
    {
        // Membuat data kategori untuk dihapus
        $kategori = KategoriBhn::create(['kategori' => 'Kategori C']);

        $response = $this->delete(route('supervisor.destroy-kategoribhn', $kategori->id));

        $response->assertStatus(302);
        $response->assertRedirect(route('supervisor.kategori-bhn'));
        $response->assertSessionHas('success', 'Data Berhasil Dihapus');
        $this->assertDatabaseMissing('kategori_bhns', [
            'id' => $kategori->id,
        ]);
    }
}
