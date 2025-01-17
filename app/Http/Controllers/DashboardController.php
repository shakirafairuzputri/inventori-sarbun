<?php

namespace App\Http\Controllers;

use App\Models\Requests;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Bahan;
use App\Models\Barang;
use App\Models\LaporKesalahan;
use Illuminate\Support\Facades\Auth;


class DashboardController extends Controller
{
    public function dashboardAdmin()
    {
        $totalUsers = User::count();
        $totalPegawaiAktif = User::where('role', 'pegawai')->where('status', 'aktif')->count();

        return view('admin.dashboardo', compact('totalUsers', 'totalPegawaiAktif'));
    }
    public function dashboardSupervisor()
    {
        $totalBahans = Bahan::count();
        $totalBarangs = Barang::count();
        $totalRequest = Requests::where('status', 'Pending')->count();

        // Ambil semua data bahan dengan relasi kategori
        $bahans = Bahan::with('kategori')->get();
        $bahansMenipis = $bahans->filter(function ($bahan) {
            return ($bahan->satuan == 'KG' && $bahan->stok <= 2) ||
                ($bahan->satuan == 'POT' && $bahan->stok <= 5);
        })->sortBy('stok'); // Urutkan berdasarkan stok dari yang terkecil

        // Ambil semua data barang dengan relasi kategori
        $barangs = Barang::with('kategori_brg')->get();
        $barangMenipis = $barangs->filter(function ($barang) {
            return ($barang->satuan_brg == 'Bks' && $barang->stok_brg <= 5) ||
                ($barang->satuan_brg == 'Pcs' && $barang->stok_brg <= 5) ||
                ($barang->satuan_brg == 'Botol' && $barang->stok_brg <= 5) ||
                ($barang->satuan_brg == 'Jerigen' && $barang->stok_brg <= 2) ||
                (!in_array($barang->satuan_brg, ['Bks', 'Pcs', 'Botol', 'Jerigen']) && $barang->stok_brg <= 3);
        })->sortBy('stok_brg'); // Urutkan berdasarkan stok barang dari yang terkecil

        return view('supervisor.dashboard', compact('totalBahans', 'totalBarangs', 'totalRequest', 'bahansMenipis', 'barangMenipis', 'barangs'));
    }

    public function dashboardPegawai()
    {
        $pegawaiId = Auth::id();
        $totalLaporan = LaporKesalahan::where('user_id', $pegawaiId)
            ->where('status', 'diproses')
            ->count();

        return view('pegawai.dashboardp', compact('totalLaporan'));
    }
}
