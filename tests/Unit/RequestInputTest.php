<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Bahan;
use App\Models\Barang;
use App\Models\Requests;

class RequestInputTest extends TestCase
{
    /** @test */
    public function test_store_a_new_request()
    {
        // Login sebagai user dengan ID tertentu
        $userId = 3; // Pastikan user dengan ID ini ada di database
        $user = User::find($userId);
        if (!$user) {
            $this->fail("User dengan ID {$userId} tidak ditemukan di database.");
        }

        $this->actingAs($user);

        // Simulasi data yang akan disubmit
        $data = [
            'kelompok' => 'Bahan Utama',
            'nama' => 'Bahan Test',
            'deskripsi' => 'Deskripsi Test',
        ];

        // Kirim POST request
        $response = $this->post(route('pegawai.request-input.store'), $data);

        // Pastikan redirect ke route yang benar
        $response->assertRedirect(route('pegawai.request-input'));
        $response->assertSessionHas('success', 'Data berhasil ditambahkan');

        // Pastikan data tersimpan di database
        $this->assertDatabaseHas('requests', [
            'user_id' => $userId,
            'kelompok' => $data['kelompok'],
            'nama' => $data['nama'],
            'deskripsi' => $data['deskripsi'],
            'status' => 'Pending',
        ]);
    }

 /** @test */
    public function test_store_a_duplicate_request()
    {

        $userId = 3;
        $user = User::find($userId);
        if (!$user) {
            $this->fail("User dengan ID {$userId} tidak ditemukan di database.");
        }

        $this->actingAs($user);

        // Simulasi data yang sudah ada di tabel bahan
        $data = [
            'kelompok' => 'Bahan Utama',
            'nama' => 'Ayam',
            'deskripsi' => 'Deskripsi Test',
        ];

        // Kirim POST request
        $response = $this->post(route('pegawai.request-input.store'), $data);

        // Pastikan ada error pada field 'nama'
        $response->assertSessionHasErrors(['nama' => 'Nama sudah ada di tabel bahan.']);
    }

    /** @test */
    public function test_approve_a_request()
    {
        // Tambahkan data request secara manual
        $request = Requests::create([
            'user_id' => 1, // Pastikan user dengan ID ini ada
            'kelompok' => 'Bahan Utama',
            'nama' => 'Request Test',
            'deskripsi' => 'Deskripsi Test',
            'status' => 'Pending',
        ]);

        // Login sebagai supervisor (role tidak diperiksa, hanya login user ID 1)
        $this->actingAs(User::find(2));

        // Kirim GET request untuk menyetujui
        $response = $this->post(route('supervisor.approve-request', ['requestId' => $request->id]));

        // Pastikan redirect ke route yang benar
        $response->assertRedirect(route('supervisor.request-input'));
        $response->assertSessionHas('success', 'Request Approved');

        // Pastikan status berubah di database
        $this->assertDatabaseHas('requests', [
            'id' => $request->id,
            'status' => 'approved',
        ]);
    }

}
