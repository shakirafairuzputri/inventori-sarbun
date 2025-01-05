<?php

namespace App\Http\Controllers\Supervisor;

use Illuminate\Http\Request;
use App\Models\PersediaanBarang;
use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\KategoriBrg;

class PersediaanBarangsController extends Controller
{
    public function viewBrgMasuk(Request $request)
    {
        $tanggalMulai = $request->input('tanggal_mulai', date('Y-m-d'));
        $tanggalSelesai = $request->input('tanggal_selesai', date('Y-m-d'));
        $kategori_id = $request->input('kategori_id', null);
        $kelompok = $request->input('kelompok', null);

        $kategoris = KategoriBrg::all();

        $query = PersediaanBarang::with(['barang.kategori_brg', 'userMasuk'])
            ->select('id', 'tanggal', 'barang_id', 'tambah', 'pegawai_brgm')
            ->whereBetween('tanggal', [$tanggalMulai, $tanggalSelesai]);

        if ($kategori_id) {
            $query->whereHas('barang', function($q) use ($kategori_id) {
                $q->where('kategori_brg_id', $kategori_id);
            });
        }

        if ($kelompok) {
            $query->whereHas('barang', function($q) use ($kelompok) {
                $q->where('kelompok', $kelompok);
            });
        }

        $persediaan_barangs = $query->get();

        return view('supervisor.inventori-brgm', compact('persediaan_barangs', 'tanggalMulai', 'tanggalSelesai', 'kategori_id', 'kelompok', 'kategoris'));
    }



    public function viewBrgKeluar(Request $request)
    {
        $tanggalMulai = $request->input('tanggal_mulai', date('Y-m-d')); // Default ke hari ini jika tidak ada input
        $tanggalSelesai = $request->input('tanggal_selesai', date('Y-m-d'));
        $kategori_id = $request->input('kategori_id', null);
        $kelompok = $request->input('kelompok', null);

        // Ambil semua kategori untuk dropdown
        $kategoris = KategoriBrg::all();

        // Query untuk mengambil persediaan_barangs dengan relasi barang dan kategori
        $query = PersediaanBarang::with(['barang.kategori_brg', 'userKeluar'])
            ->select('id', 'tanggal', 'barang_id', 'kurang', 'pegawai_brgk')
            ->whereBetween('tanggal', [$tanggalMulai, $tanggalSelesai]); // Filter berdasarkan rentang tanggal

        // Jika kategori dipilih, tambahkan filter berdasarkan kategori_id
        if ($kategori_id) {
            $query->whereHas('barang', function($q) use ($kategori_id) {
                $q->where('kategori_brg_id', $kategori_id);
            });
        }

        // Jika kelompok dipilih, tambahkan filter berdasarkan kelompok
        if ($kelompok) {
            $query->whereHas('barang', function($q) use ($kelompok) {
                $q->where('kelompok', $kelompok);
            });
        }

        $persediaan_barangs = $query->get();

        // Pastikan semua variabel dikirim ke view
        return view('supervisor.inventori-brgk', compact('persediaan_barangs', 'tanggalMulai', 'tanggalSelesai', 'kategori_id', 'kelompok', 'kategoris'));
    }
}
