<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\PersediaanBahan;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DownloadLaporanTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        // PersediaanBahan::create([
        //     'tanggal' => '2024-10-30',
        //     'produksi_id' => 1, 
        //     'stok_awal' => 1,
        //     'stok_siang' => 3,
        //     'cek_fisik' => 0,
        //     'selisih' => 0,
        //     'stok_akhir' => 4,
        //     'keterangan' => 'none',
            
        // ]);
        // PersediaanBahan::create([
        //     'tanggal' => '2024-10-31',
        //     'produksi_id' => 1, 
        //     'stok_awal' => 2,
        //     'stok_siang' => 4,
        //     'cek_fisik' => 0,
        //     'selisih' => 0,
        //     'stok_akhir' => 4,
        //     'keterangan' => 'none',
            
        // ]);
    }

    public function test_download_pdf_bhn_without_range()
    {
        $this->actingAs(User::find(3)); // Pastikan User ID 3 valid dan memiliki akses

        $tanggal = '2024-10-31'; 

        // Uji endpoint download dengan metode GET
        $response = $this->get(route('pegawai.download-laporan-bhn', [
            'tanggal' => $tanggal,
        ]));

        // Debugging: Jika redirect, tampilkan konten respons
        if ($response->isRedirect()) {
            $this->fail('Redirected response: ' . $response->getContent());
        }

        // Periksa status response
        $response->assertStatus(200);

        // Periksa konten tipe PDF
        $this->assertStringContainsString('application/pdf', $response->headers->get('Content-Type'));

        // Periksa nama file yang diunduh
        $this->assertStringContainsString('laporan_persediaan_bahan_31-10-2024.pdf', $response->headers->get('Content-Disposition'));
    }


    public function test_download_pdf_bhn_with_range()
    {
        $this->actingAs(User::find(2)); // Pastikan User ID 2 valid dan memiliki akses

        $tanggalMulai = '2024-10-30'; // Pastikan tanggal ini ada di database
        $tanggalSelesai = '2024-10-31'; // Pastikan tanggal ini ada di database

        // Uji endpoint download dengan metode GET
        $response = $this->get(route('supervisor.download-laporan-bhn', [
            'tanggalMulai' => $tanggalMulai,
            'tanggalSelesai' => $tanggalSelesai,
        ]));

        // Debugging: Jika redirect, tampilkan konten respons
        if ($response->isRedirect()) {
            $this->fail('Redirected response: ' . $response->getContent());
        }

        // Periksa status response
        $response->assertStatus(200);

        // Periksa konten tipe PDF
        $this->assertStringContainsString('application/pdf', $response->headers->get('Content-Type'));

        // Periksa nama file yang diunduh
        $this->assertStringContainsString('laporan_persediaan_bahan_30-10-2024_s.d._31-10-2024.pdf', $response->headers->get('Content-Disposition'));
    }
    public function test_download_pdf_brg_without_range()
    {
        $this->actingAs(User::find(3)); // Pastikan User ID 3 valid dan memiliki akses

        $tanggal = '2024-10-31'; 

        // Uji endpoint download dengan metode GET
        $response = $this->get(route('pegawai.download-laporan', [
            'tanggal' => $tanggal,
        ]));

        // Debugging: Jika redirect, tampilkan konten respons
        if ($response->isRedirect()) {
            $this->fail('Redirected response: ' . $response->getContent());
        }

        // Periksa status response
        $response->assertStatus(200);

        // Periksa konten tipe PDF
        $this->assertStringContainsString('application/pdf', $response->headers->get('Content-Type'));

        // Periksa nama file yang diunduh
        $this->assertStringContainsString('laporan_persediaan_barang_31-10-2024.pdf', $response->headers->get('Content-Disposition'));
    }


    public function test_download_pdf_brg_with_range()
    {
        $this->actingAs(User::find(2)); // Pastikan User ID 2 valid dan memiliki akses

        $tanggalMulai = '2024-10-30'; // Pastikan tanggal ini ada di database
        $tanggalSelesai = '2024-10-31'; // Pastikan tanggal ini ada di database

        // Uji endpoint download dengan metode GET
        $response = $this->withoutMiddleware()->get(route('supervisor.download-laporan', [
            'tanggal_mulai' => $tanggalMulai,
            'tanggal_selesai' => $tanggalSelesai,
        ]));

        // Debugging: Jika redirect, tampilkan konten respons
        if ($response->isRedirect()) {
            $this->fail('Redirected response: ' . $response->getContent());
        }

        // Periksa status response
        $response->assertStatus(200);

        // Periksa konten tipe PDF
        $this->assertStringContainsString('application/pdf', $response->headers->get('Content-Type'));

        // Periksa nama file yang diunduh
        // $this->assertStringContainsString('laporan_persediaan_barang_30-10-2024_s.d._31-10-2024.pdf', $response->headers->get('Content-Disposition'));
    }
}
