<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\PersediaanBahan;
use App\Models\PersediaanBarang;
use App\Models\User;
use Carbon\Carbon;

class LaporanBahanBarangTest extends TestCase
{
    /** @test */
    public function test_laporan_bahan_pegawai()
    {
        $user = User::find(3);

        $this->assertNotNull($user, "User dengan ID 3 tidak ditemukan");

        $this->actingAs($user);

        $tanggal = Carbon::today()->toDateString();
        $persediaanBahan = PersediaanBahan::where('tanggal', $tanggal)->first();

        $this->assertNotNull($persediaanBahan, "Data PersediaanBahan untuk tanggal $tanggal tidak ditemukan");

        $response = $this->get(route('pegawai.laporan-bhn', ['tanggal' => $tanggal]));

        $response->assertStatus(200);
        $response->assertViewHas('reportData');
    }

    /** @test */
    public function test_laporan_barang_pegawai()
    {
        $user = User::find(3);

        $this->assertNotNull($user, "User dengan ID 3 tidak ditemukan");

        $this->actingAs($user);

        $tanggal = Carbon::today()->toDateString();
        $persediaanBarang = PersediaanBarang::where('tanggal', $tanggal)->first();

        $this->assertNotNull($persediaanBarang, "Data PersediaanBarang untuk tanggal $tanggal tidak ditemukan");

        $response = $this->get(route('pegawai.laporan-brg', ['tanggal' => $tanggal]));

        // Assert
        $response->assertStatus(200);
        $response->assertViewHas('reportData');
    }

    /** @test */
    public function test_laporan_bahan_supervisor()
    {
        $user = User::find(2);

        $this->assertNotNull($user, "User supervisor dengan ID 3 tidak ditemukan");

        $this->actingAs($user);

        $tanggalMulai = Carbon::today()->subDays(7)->toDateString();
        $tanggalSelesai = Carbon::today()->toDateString();

        $persediaanBahan = PersediaanBahan::whereBetween('tanggal', [$tanggalMulai, $tanggalSelesai])->first();

        // Pastikan data persediaan bahan ada
        $this->assertNotNull($persediaanBahan, "Data PersediaanBahan untuk rentang tanggal $tanggalMulai sampai $tanggalSelesai tidak ditemukan");

        $response = $this->get(route('supervisor.laporan-bhn', ['tanggal_mulai' => $tanggalMulai, 'tanggal_selesai' => $tanggalSelesai]));

        // Assert: Periksa status dan data di view
        $response->assertStatus(200);
        $response->assertViewHas('reportData');
    }

    /** @test */
    public function test_laporan_barang_supervisor()
    {
        $user = User::find(2);

        $this->assertNotNull($user, "User supervisor dengan ID 3 tidak ditemukan");

        $this->actingAs($user);

        $tanggalMulai = Carbon::today()->subDays(7)->toDateString();
        $tanggalSelesai = Carbon::today()->toDateString();

        $persediaanBarang = PersediaanBarang::whereBetween('tanggal', [$tanggalMulai, $tanggalSelesai])->first();

        $this->assertNotNull($persediaanBarang, "Data PersediaanBarang untuk rentang tanggal $tanggalMulai sampai $tanggalSelesai tidak ditemukan");

        $response = $this->get(route('supervisor.laporan-brg', ['tanggal_mulai' => $tanggalMulai, 'tanggal_selesai' => $tanggalSelesai]));

        $response->assertStatus(200);
        $response->assertViewHas('reportData');
    }
}
