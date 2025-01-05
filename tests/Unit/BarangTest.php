<?php

namespace Tests\Unit;

use App\Models\Barang;
use App\Models\User;
use Tests\TestCase;

class BarangTest extends TestCase
{
    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->user = User::firstOrCreate([
            'email' => 'supervisor@example.com',
        ], [
            'name' => 'Supervisor', // Anda bisa mengisi ini jika pengguna tidak ada
            'password' => bcrypt('password123'), // Anda bisa mengisi password jika pengguna tidak ada
            'role' => 'supervisor',
            'status' => 'Aktif',
        ]);
        
        // Login sebagai supervisor
        $this->actingAs($this->user);
    }

    public function test_create_barang_success()
    {
        $response = $this->post(route('supervisor.store-barang'), [
            'nama_brg' => 'Barang A',
            'kelompok' => 'Kelompok A', // Tambahkan kelompok setelah nama barang
            'kategori_brg_id' => 1, // Pastikan kategori ini ada
            'satuan_brg_id' => 1,
            'stok_brg' => 100,
        ]);

        // Cek respons dan status
        $response->assertStatus(302);
        $response->assertRedirect(route('supervisor.daftar-brg'));
        $response->assertSessionHas('success', 'Data Barang Berhasil Ditambahkan');

        // Pastikan data disimpan di database
        $this->assertDatabaseHas('barangs', [
            'nama_brg' => 'Barang A',
            'kelompok' => 'Kelompok A', // Tambahkan pemeriksaan kelompok
            'stok_brg' => 100,
        ]);
    }

    public function test_read_barang()
    {
        // Membuat data Barang secara manual
        Barang::create([
            'nama_brg' => 'Barang Uji',
            'kelompok' => 'Kelompok Uji', // Tambahkan kelompok
            'kategori_brg_id' => 1, // Pastikan ini sesuai dengan kategori yang ada
            'satuan_brg_id' => 1,
            'stok_brg' => 10,
        ]);

        $response = $this->get(route('supervisor.daftar-brg'));

        $response->assertStatus(200);
        $response->assertSee('Barang Uji'); // Pastikan nama Barang muncul di halaman
        $response->assertSee('Kelompok Uji'); // Pastikan kelompok muncul di halaman
    }

    public function test_update_barang_success()
    {
        // Membuat data Barang untuk diupdate
        $barang = Barang::create([
            'nama_brg' => 'Barang Lama',
            'kelompok' => 'Kelompok Lama', // Tambahkan kelompok
            'kategori_brg_id' => 1,
            'satuan_brg_id' => 1,
            'stok_brg' => 10,
        ]);

        // Melakukan update
        $response = $this->put(route('supervisor.update-barang', $barang->id), [
            'nama_brg' => 'Barang Baru Diperbarui',
            'kelompok' => 'Kelompok Baru', // Tambahkan kelompok baru
            'kategori_brg_id' => 1,
            'satuan_brg_id' => 2,
            'stok_brg' => 20,
        ]);

        $response->assertStatus(302);
        $response->assertRedirect(route('supervisor.daftar-brg'));
        $this->assertDatabaseHas('barangs', [
            'nama_brg' => 'Barang Baru Diperbarui',
            'kelompok' => 'Kelompok Baru', // Tambahkan pemeriksaan kelompok baru
            'stok_brg' => 20,
        ]);
    }

    public function test_create_barang_fail_validation()
    {
        // Kirim request POST tanpa data yang diperlukan
        $response = $this->post(route('supervisor.store-barang'), [
            'nama_brg' => '', // Nama tidak boleh kosong
            'kelompok' => '', // Kelompok tidak boleh kosong
            'kategori_brg_id' => '', // Kategori tidak boleh kosong
            'satuan_brg_id' => '',
            'stok_brg' => '',
        ]);

        // Cek respons dan status
        $response->assertStatus(302);
        $response->assertSessionHasErrors(['nama_brg', 'kelompok', 'kategori_brg_id', 'satuan_brg_id', 'stok_brg']);
    }
}
