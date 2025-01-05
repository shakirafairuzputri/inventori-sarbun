<?php

namespace Tests\Unit;

use App\Models\Bahan;
use App\Models\Barang;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;
use App\Models\User;

class StokMenipisTest extends TestCase
{
    use WithoutMiddleware;

    public function test_stok_menipis()
    {
        // Login sebagai supervisor yang sudah ada
        $supervisor = User::find(2); 
        $this->actingAs($supervisor);

        // Mengakses dashboard supervisor
        $response = $this->get(route('supervisor.dashboard'));

        // Memastikan status response 200
        $response->assertStatus(200);

        // Memastikan data total bahan dan barang
        $totalBahans = Bahan::count();
        $totalBarangs = Barang::count();
        $response->assertViewHas('totalBahans', $totalBahans);
        $response->assertViewHas('totalBarangs', $totalBarangs);

        // Memastikan data bahan yang stok menipis
        $bahansMenipis = Bahan::where(function ($query) {
            $query->where('satuan', 'KG')->where('stok', '<=', 2)
                  ->orWhere(function ($query) {
                      $query->where('satuan', 'POT')->where('stok', '<=', 5);
                  });
        })->get();

        $response->assertViewHas('bahansMenipis', $bahansMenipis);

        // Memastikan data barang dengan stok menipis
        $barangs = Barang::where('stok_brg', '<=', 3)->get();
        $response->assertViewHas('barangs', $barangs);
    }
}
