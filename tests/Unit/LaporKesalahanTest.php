<?php

namespace Tests\Unit;

use App\Models\User;
use App\Models\LaporKesalahan;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;

class LaporKesalahanTest extends TestCase
{
    use WithoutMiddleware;

    protected function setUp(): void
    {
        parent::setUp();
    }

    public function test_store_lapor_supervisor()
    {
        $supervisor = User::find(2); 
        $this->actingAs($supervisor);
        // Mengambil pegawai yang akan dilaporkan
        $user = User::where('role', 'pegawai')->first(); 
        $data = [
            'user_id' => $user->id, // Menggunakan ID pegawai, bukan supervisor
            'tanggal' => '2024-10-31',
            'kategori' => 'Kategori Test',
            'keterangan' => 'Keterangan Test',
        ];

        // Mengirim POST request untuk membuat laporan
        $response = $this->post(route('supervisor.lapor-kesalahan.store'), $data);

        // Memastikan status response 302 (redirect)
        $response->assertStatus(302);

        // Memastikan laporan baru tersimpan di database
        $this->assertDatabaseHas('lapor_kesalahans', [
            'user_id' => $user->id, // Menggunakan ID pegawai
            'tanggal' => '2024-10-31',
            'kategori' => 'Kategori Test',
            'keterangan' => 'Keterangan Test',
            'status' => 'Diproses',
        ]);
    }


    public function test_update_lapor_supervisor()
    {
        $supervisor = User::find(2); 
        $this->actingAs($supervisor);
        // Buat laporan yang ada untuk diuji
        $user = User::where('role', 'pegawai')->first(); 
        $lapor = LaporKesalahan::create([
            'user_id' => $user->id,
            'tanggal' => '2024-10-31',
            'kategori' => 'Kategori Test',
            'keterangan' => 'Keterangan Test',
            'status' => 'Diproses',
        ]);

        // Data untuk update laporan
        $data = [
            'user_id' => $user->id,
            'tanggal' => '2024-11-01',
            'kategori' => 'Kategori Update',
            'keterangan' => 'Keterangan Update',
        ];

        // Mengirim PUT request untuk memperbarui laporan
        $response = $this->put(route('supervisor.lapor.update', $lapor->id), $data);

        // Memastikan status response 302 (redirect)
        $response->assertStatus(302);

        // Memastikan laporan diperbarui di database
        $this->assertDatabaseHas('lapor_kesalahans', [
            'id' => $lapor->id,
            'tanggal' => '2024-11-01',
            'kategori' => 'Kategori Update',
            'keterangan' => 'Keterangan Update',
            'status' => 'Diproses', // Status tetap 'Diproses'
        ]);
    }

    public function test_view_lapor_pegawai()
    {
        $pegawai = User::find(3); 
        $this->actingAs($pegawai);
        // Buat laporan yang terkait dengan user ini
        LaporKesalahan::create([
            'user_id' => $pegawai->id,
            'tanggal' => '2024-10-31',
            'kategori' => 'Kategori Test',
            'keterangan' => 'Keterangan Test',
            'status' => 'Diproses',
        ]);

        // Mengakses halaman laporan pegawai
        $response = $this->get(route('pegawai.laporan'));

        // Memastikan status response 200
        $response->assertStatus(200);

        // Memastikan laporan ada di view
        $response->assertViewHas('laporans', function ($laporans) {
            return $laporans->count() > 0; // Pastikan ada laporan
        });
    }

    public function test_update_status_lapor_pegawai()
    {
        $pegawai = User::find(3); 
        $this->actingAs($pegawai);
        // Buat laporan yang terkait dengan user ini
        $lapor = LaporKesalahan::create([
            'user_id' => $pegawai->id,
            'tanggal' => '2024-10-31',
            'kategori' => 'Kategori Test',
            'keterangan' => 'Keterangan Test',
            'status' => 'Diproses',
        ]);

        // Mengirim request untuk memperbarui status laporan
        $response = $this->put(route('pegawai.lapor-kesalahan.update-status', $lapor->id));

        // Memastikan status response 302 (redirect)
        $response->assertStatus(302);

        // Memastikan status laporan diperbarui di database
        $this->assertDatabaseHas('lapor_kesalahans', [
            'id' => $lapor->id,
            'status' => 'Selesai', // Status diperbarui menjadi 'Selesai'
        ]);
    }
}
