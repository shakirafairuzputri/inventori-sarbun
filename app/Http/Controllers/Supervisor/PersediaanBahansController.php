<?php

namespace App\Http\Controllers\Supervisor;

use Illuminate\Http\Request;
use App\Models\ReturBahan;
use App\Models\PembelianBahan;
use App\Models\ProduksiBahan;
use App\Http\Controllers\Controller;
use App\Models\Bahan;
use App\Models\KategoriBhn;

class PersediaanBahansController extends Controller
{
    public function viewRetur(Request $request)
    {
        $tanggalMulai = $request->input('tanggal_mulai', date('Y-m-d')); 
        $tanggalSelesai = $request->input('tanggal_selesai', date('Y-m-d'));
        $kategori_id = $request->input('kategori_id', null);

        $kategoris = KategoriBhn::all();

        $query = ReturBahan::with(['bahan.kategori', 'user'])
            ->select('id', 'tanggal', 'bahan_id', 'retur_baik', 'retur_rusak', 'user_id', 'status')
            ->whereBetween('tanggal', [$tanggalMulai, $tanggalSelesai])
            ->where(function($q) {
                $q->where('retur_baik', '>', 0)
                ->orWhere('retur_rusak', '>', 0);
            });

        if ($kategori_id) {
            $query->whereHas('bahan', function($q) use ($kategori_id) {
                $q->where('kategori_id', $kategori_id);
            });
        }

        $returBahans = $query->get();
        return view('supervisor.inventori-retur', compact('returBahans', 'kategoris', 'tanggalMulai', 'tanggalSelesai', 'kategori_id'));
    }

    public function kembalikanRetur($id)
    {
        $returBahan = ReturBahan::findOrFail($id);
        
        $bahan = Bahan::findOrFail($returBahan->bahan_id);
        
        $returBahan->update([
            'status' => 'Sudah Diganti',
        ]);

        $bahan->stok += ($returBahan->retur_baik + $returBahan->retur_rusak); 
        $bahan->save();

        return redirect()->route('supervisor.inventori-retur')->with('success', 'Retur bahan berhasil dikembalikan.');
    }

    public function viewPembelian(Request $request)
    {
        $tanggalMulai = $request->input('tanggal_mulai', date('Y-m-d'));
        $tanggalSelesai = $request->input('tanggal_selesai', date('Y-m-d'));

        $kategori_id = $request->input('kategori_id', null); 

        $kategoris = KategoriBhn::all();

        $query = PembelianBahan::with(['bahan.kategori', 'user'])
            ->select('id', 'tanggal', 'bahan_id', 'pembelian', 'tambahan_sore', 'user_id')
            ->whereBetween('tanggal', [$tanggalMulai, $tanggalSelesai]); // Gunakan whereBetween untuk rentang tanggal

        if ($kategori_id) {
            $query->whereHas('bahan', function($q) use ($kategori_id) {
                $q->where('kategori_id', $kategori_id);
            });
        }

        $pembelianBahans = $query->get()->filter(function($pembelian) {
            return !(is_null($pembelian->pembelian) || is_null($pembelian->tambahan_sore) || $pembelian->pembelian == 0 || $pembelian->tambahan_sore == 0);
        });
        return view('supervisor.inventori-beli', compact('pembelianBahans', 'kategoris', 'tanggalMulai', 'tanggalSelesai', 'kategori_id'));
    }
    public function viewProduksi(Request $request)
    {
        // Ambil tanggal mulai dan tanggal selesai dari request, default ke hari ini
        $tanggalMulai = $request->input('tanggal_mulai', date('Y-m-d'));
        $tanggalSelesai = $request->input('tanggal_selesai', date('Y-m-d'));
        $kategori_id = $request->input('kategori_id', null); // Ambil input kategori_id

        $kategoris = KategoriBhn::all();

        $query = ProduksiBahan::with(['pembelian.bahan.kategori', 'user'])
            ->select('id', 'tanggal', 'pembelian_id', 'produksi_baik', 'produksi_paket', 'produksi_rusak', 'user_id')
            ->whereBetween('tanggal', [$tanggalMulai, $tanggalSelesai])
            ->where(function ($query) {
                $query->where('produksi_baik', '>', 0)
                    ->orWhere('produksi_baik', '!=', null);
            })
            ->where(function ($query) {
                $query->where('produksi_paket', '>', 0)
                    ->orWhere('produksi_paket', '!=', null);
            })
            ->where(function ($query) {
                $query->where('produksi_rusak', '>', 0)
                    ->orWhere('produksi_rusak', '!=', null);
            });
        
        if ($kategori_id) {
            $query->whereHas('pembelian.bahan', function($q) use ($kategori_id) {
                $q->where('kategori_id', $kategori_id);
            });
        }

        $produksiBahans = $query->get();
        return view('supervisor.inventori-produksi', compact('produksiBahans', 'kategoris', 'tanggalMulai', 'tanggalSelesai', 'kategori_id'));
    }
}
