<?php

namespace Tests\Feature;

use App\Models\KategoriBrg;
use App\Models\User;
use Tests\TestCase;

class KategoriBarangTest extends TestCase
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

    public function test_view_kategori_brg()
    {
        $response = $this->get(route('supervisor.kategori-brg'));

        $response->assertStatus(200);
        $response->assertViewIs('supervisor.kategori-brg');
        $response->assertSee('Kategori Barang'); // Sesuaikan dengan elemen yang ada di view
    }

    public function test_store_kategori_brg_success()
    {
        $response = $this->post(route('supervisor.store-kategoribrg'), [
            'kategori_brg' => 'Kategori A',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect(route('supervisor.kategori-brg'));
        $response->assertSessionHas('success', 'Data Berhasil Ditambahkan');
        $this->assertDatabaseHas('kategori_brgs', [
            'kategori_brg' => 'Kategori A',
        ]);
    }

    public function test_store_kategori_brg_fail_validation()
    {
        // Kirim request POST tanpa kategori
        $response = $this->post(route('supervisor.store-kategoribrg'), [
            'kategori_brg' => '', // Nama kategori tidak boleh kosong
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('kategori_brg');
    }

    public function test_store_kategori_brg_fail_existing_data()
    {
        KategoriBrg::create(['kategori_brg' => 'Lem']);

        $this->assertDatabaseHas('kategori_brgs', ['kategori_brg' => 'Lem']);

        $response = $this->post(route('supervisor.store-kategoribrg'), [
            'kategori_brg' => 'Lem',
        ]);


        $response->assertStatus(302);
        $response->assertRedirect(route('supervisor.tambah-kategori-brg'));
        $response->assertSessionHas('errors');

        $this->assertDatabaseHas('kategori_brgs', ['kategori_brg' => 'Lem']);
    }

    public function test_destroy_kategori_brg_success()
    {
        // Membuat data kategori untuk dihapus
        $kategori = KategoriBrg::create(['kategori_brg' => 'Kategori C']);

        $response = $this->delete(route('supervisor.destroy-kategoribrg', $kategori->id));

        $response->assertStatus(302);
        $response->assertRedirect(route('supervisor.kategori-brg'));
        $response->assertSessionHas('success', 'Data Berhasil Dihapus');
        $this->assertDatabaseMissing('kategori_brgs', [
            'id' => $kategori->id,
        ]);
    }
}
