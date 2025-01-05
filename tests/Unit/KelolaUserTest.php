<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class KelolaUserTest extends TestCase
{
    // Test untuk memastikan halaman akun dapat ditampilkan
    public function test_view_akun()
    {
        $admin = User::find(1);

        $response = $this->actingAs($admin)->get(route('admin.kelola-user'));

        $response->assertStatus(200); 
        $response->assertViewIs('admin.kelola-user'); 
    }

    // Test untuk menyimpan akun baru
    public function test_store_akun()
    {
        $admin = User::find(1);
        $data = [
            'name' => 'User Test',
            'email' => 'user@test.com',
            'password' => 'password123',
            'role' => 'pegawai',
            'status' => 'Aktif',
        ];

        $response = $this->actingAs($admin)->post(route('admin.store-user'), $data);

        $this->assertDatabaseHas('users', [
            'email' => 'user@test.com',
            'name' => 'User Test',
        ]);

        $response->assertRedirect(route('admin.kelola-user'));
        $response->assertSessionHas('success', 'User berhasil ditambahkan');
    }

    // Test untuk memperbarui akun
    public function test_update_akun()
    {
        $admin = User::find(1);
        // Membuat pengguna untuk diuji
        $user = User::create([
            'name' => 'Shakira',
            'email' => 'shakira@test.com',
            'password' => Hash::make('oldpassword'),
            'role' => 'pegawai',
            'status' => 'Aktif',
        ]);

        $data = [
            'name' => 'Fairuz',
            'email' => 'fairuz@test.com',
            'role' => 'pegawai',
            'status' => 'Aktif',
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ];

        $response = $this->actingAs($admin)->put(route('admin.update-user', $user->id), $data);

        $user->refresh(); // Memperbarui data user setelah update

        $this->assertEquals('Fairuz', $user->name);
        $this->assertEquals('fairuz@test.com', $user->email);
        $this->assertEquals('pegawai', $user->role);
        $this->assertTrue(Hash::check('newpassword123', $user->password)); // Memastikan password di-hash

        $response->assertRedirect(route('admin.kelola-user'));
        $response->assertSessionHas('success', 'User updated successfully!');
    }
}
