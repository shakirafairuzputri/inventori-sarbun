<?php

namespace Tests\Unit;

use App\Models\User;
use Tests\TestCase; 
use Illuminate\Foundation\Testing\RefreshDatabase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;
    protected $supervisor;
    protected $pegawai;

    protected function setUp(): void
    {
        parent::setUp();

        // Buat user dummy sekali saja untuk semua test
        $this->admin = User::factory()->create([
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'status' => 'Aktif',
        ]);

        $this->supervisor = User::factory()->create([
            'email' => 'supervisortes@example.com',
            'password' => bcrypt('password12'),
            'role' => 'supervisor',
            'status' => 'Aktif',
        ]);

        $this->pegawai = User::factory()->create([
            'email' => 'pegawai1@example.com',
            'password' => bcrypt('password456'),
            'role' => 'pegawai',
            'status' => 'Aktif',
        ]);
    }

    public function test_login_admin_success()
    {
        // Kirim request POST untuk login
        $response = $this->post('/loginproses', [
            'email' => 'admin@example.com',
            'password' => 'password',
        ]);

        // Cek respons dan status
        $response->assertStatus(302);
        $response->assertRedirect('/admin/dashboard');

        // Pastikan user berhasil login
        $this->assertAuthenticatedAs($this->admin);
    }

    public function test_login_supervisor_success()
    {
        // Kirim request POST untuk login
        $response = $this->post('/loginproses', [
            'email' => 'supervisortes@example.com',
            'password' => 'password12',
        ]);

        // Cek respons dan status
        $response->assertStatus(302);
        $response->assertRedirect('/supervisor/dashboard');

        // Pastikan user berhasil login
        $this->assertAuthenticatedAs($this->supervisor);
    }

    public function test_login_pegawai_success()
    {
        // Kirim request POST untuk login
        $response = $this->post('/loginproses', [
            'email' => 'pegawai1@example.com',
            'password' => 'password456',
        ]);

        // Cek respons dan status
        $response->assertStatus(302);
        $response->assertRedirect('/pegawai/dashboard');

        // Pastikan user berhasil login
        $this->assertAuthenticatedAs($this->pegawai);
    }

    public function test_login_fail_invalid_email()
    {
        // Kirim request POST dengan email tidak valid
        $response = $this->post('/loginproses', [
            'email' => 'invalid@example.com',
            'password' => 'password',
        ]);

        // Cek respons dan status
        $response->assertStatus(302);
        $response->assertRedirect('/');
        $response->assertSessionHasErrors(['email' => 'Email or Password is incorrect']);
    }

    public function test_login_fail_invalid_password()
    {
        // Kirim request POST dengan password salah
        $response = $this->post('/loginproses', [
            'email' => 'admin@example.com',
            'password' => 'wrongpassword',
        ]);

        // Cek respons dan status
        $response->assertStatus(302);
        $response->assertRedirect('/');
        $response->assertSessionHasErrors(['email' => 'Email or Password is incorrect']);
    }

    public function test_login_fail_inactive_user()
    {
        // Buat user dummy dengan status tidak aktif
        $inactiveUser = User::factory()->create([
            'email' => 'inactive@example.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'status' => 'Tidak Aktif',
        ]);

        // Kirim request POST untuk login
        $response = $this->post('/loginproses', [
            'email' => 'inactive@example.com',
            'password' => 'password',
        ]);

        // Cek respons dan status
        $response->assertStatus(302);
        $response->assertRedirect('/');
        $response->assertSessionHasErrors(['email' => 'Your account is inactive. Please contact admin.']);
    }
}
