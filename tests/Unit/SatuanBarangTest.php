<?php

namespace Tests\Feature;

use App\Models\Satuan;
use App\Models\User;
use Tests\TestCase;

class SatuanBarangTest extends TestCase
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

    public function test_view_satuan()
    {
        $response = $this->get(route('supervisor.unit'));

        $response->assertStatus(200);
        $response->assertViewIs('supervisor.unit');
        $response->assertSee('Satuan'); // Sesuaikan dengan elemen yang ada di view
    }

    public function test_store_satuan_success()
    {
        $response = $this->post(route('supervisor.store-satuan'), [
            'satuan_brg' => 'Satuan A',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect(route('supervisor.unit'));
        $response->assertSessionHas('success', 'Data Berhasil Ditambahkan');
        $this->assertDatabaseHas('satuans', [
            'satuan_brg' => 'Satuan A',
        ]);
    }

    public function test_store_satuan_fail_validation()
    {
        // Kirim request POST tanpa satuan
        $response = $this->post(route('supervisor.store-satuan'), [
            'satuan_brg' => '', // Nama satuan tidak boleh kosong
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('satuan_brg');
    }

    public function test_store_satuan_fail_existing_data()
    {
        // Membuat data satuan untuk pengujian
        Satuan::create(['satuan_brg' => 'Satuan B']);

        $response = $this->post(route('supervisor.store-satuan'), [
            'satuan_brg' => 'Satuan B', // Data sudah ada
        ]);

        $response->assertStatus(302);
        $response->assertRedirect(route('supervisor.unit'));
        $response->assertSessionHas('error', 'Data sudah ada.');
    }

    public function test_destroy_satuan_success()
    {
        // Membuat data satuan untuk dihapus
        $satuan = Satuan::create(['satuan_brg' => 'Satuan C']);

        $response = $this->delete(route('supervisor.destroy-satuan', $satuan->id));

        $response->assertStatus(302);
        $response->assertRedirect(route('supervisor.unit'));
        $response->assertSessionHas('success', 'Data Berhasil Dihapus');
        $this->assertDatabaseMissing('satuans', [
            'id' => $satuan->id,
        ]);
    }
}
