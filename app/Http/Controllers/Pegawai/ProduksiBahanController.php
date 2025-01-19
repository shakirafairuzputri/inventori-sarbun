<?php

namespace App\Http\Controllers\Pegawai;

use App\Events\ProduksiUpdated;
use Illuminate\Http\Request;
use App\Models\ProduksiBahan;
use App\Models\ReturBahan;
use App\Models\PembelianBahan;
use App\Models\PersediaanBahan;
use App\Http\Controllers\Controller;
use App\Models\Bahan;
use App\Models\KategoriBhn;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProduksiBahanController extends Controller
{
    public function viewProduksi(Request $request)
    {
        $tanggal = $request->input('tanggal', date('Y-m-d'));
        $kategori_id = $request->input('kategori_id', null); // Ambil input kategori_id

        $kategoris = KategoriBhn::all();

        $query = ProduksiBahan::with('pembelian.bahan.kategori')
            ->select('id', 'tanggal', 'pembelian_id', 'produksi_baik', 'produksi_paket', 'produksi_rusak')
            ->where('tanggal', $tanggal)
            ->where(function ($query) {
                $query->where('produksi_baik', '>', 0)
                    ->orWhere('produksi_paket', '>', 0)
                    ->orWhere('produksi_rusak', '>', 0);
            });

        if ($kategori_id) {
            $query->whereHas('pembelian.bahan', function($q) use ($kategori_id) {
                $q->where('kategori_id', $kategori_id);
            });
        }

        $produksiBahans = $query->get();

        return view('pegawai.persediaan-produksi', compact('produksiBahans', 'kategoris', 'tanggal', 'kategori_id'));
    }

    public function storeProduksi(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'pembelian_id' => 'nullable|exists:pembelian_bahans,id',
            'produksi_baik' => 'nullable|numeric',
            'produksi_paket' => 'nullable|numeric',
            'produksi_rusak' => 'nullable|numeric',
        ]);

        // Ambil bahan_id berdasarkan pembelian_id atau input langsung
        if ($request->input('pembelian_id')) {
            $pembelian = PembelianBahan::findOrFail($request->input('pembelian_id'));
            $bahanId = $pembelian->bahan_id;
        } else {
            $bahanId = $request->input('bahan_id');
            $pembelian = PembelianBahan::where('tanggal', $request->input('tanggal'))
                ->where('bahan_id', $bahanId)
                ->first();

            if (!$pembelian) {
                $pembelian = PembelianBahan::create([
                    'tanggal' => $request->input('tanggal'),
                    'bahan_id' => $bahanId,
                    'pembelian' => 0,
                    'tambahan_sore' => 0,
                    'user_id' => Auth::id(),
                ]);
            }
        }

        // Ambil stok bahan dari tabel bahan
        $bahan = Bahan::findOrFail($bahanId);
        $stokTersedia = $bahan->stok;

        // Hitung total produksi
        $produksiBaik = $request->input('produksi_baik', 0);
        $produksiPaket = $request->input('produksi_paket', 0);
        $produksiRusak = $request->input('produksi_rusak', 0);
        $totalProduksi = $produksiBaik + $produksiPaket + $produksiRusak;

        // Cek apakah total produksi melebihi stok tersedia
        if ($totalProduksi > $stokTersedia) {
            return redirect()->back()->withErrors([
                'error' => 'Total produksi tidak boleh melebihi stok bahan yang tersedia (' . $stokTersedia . ').'
            ])->withInput();
        }

        // Cek atau buat data ProduksiBahan
        $produksi = ProduksiBahan::whereHas('pembelian', function ($query) use ($bahanId) {
            $query->where('bahan_id', $bahanId);
        })
        ->where('tanggal', $request->input('tanggal'))
        ->first();

        if ($produksi) {
            $produksi->produksi_baik += $produksiBaik;
            $produksi->produksi_paket += $produksiPaket;
            $produksi->produksi_rusak += $produksiRusak;
            $produksi->user_id = Auth::id();
            $produksi->save();
        } else {
            $produksi = ProduksiBahan::create([
                'tanggal' => $request->input('tanggal'),
                'pembelian_id' => $pembelian->id,
                'bahan_id' => $bahanId,
                'produksi_baik' => $produksiBaik,
                'produksi_paket' => $produksiPaket,
                'produksi_rusak' => $produksiRusak,
                'user_id' => Auth::id(),
            ]);
        }

        // Cek atau buat data PersediaanBahan
        $persediaanBahan = PersediaanBahan::where('tanggal', $request->input('tanggal'))
            ->where('produksi_id', $produksi->id)
            ->first();

        if (!$persediaanBahan) {
            PersediaanBahan::create([
                'tanggal' => $request->input('tanggal'),
                'produksi_id' => $produksi->id,
                // Tambahkan field lain yang diperlukan di sini
            ]);
        }

        return redirect()->route('pegawai.persediaan-produksi')->with('success', 'Produksi bahan berhasil ditambahkan.');
    }


    public function editProduksi($id)
    {
        $produksiBahan = ProduksiBahan::with('pembelian.bahan')->findOrFail($id);

        $bahanId = $produksiBahan->pembelian->bahan->id; 

        $bahans = Bahan::all();  // Ambil semua bahan untuk dropdown

        // Kirim data ke view
        return view('pegawai.edit-persediaan-produksi', compact('produksiBahan', 'bahanId', 'bahans'));
    }


    public function updateProduksi(Request $request, $id)
    {
        // Validasi input
        $validatedData = $request->validate([
            'tanggal' => 'required|date',
            'pembelian_id' => 'nullable|exists:pembelian_bahans,id',
            'produksi_baik' => 'required|numeric',
            'produksi_paket' => 'required|numeric',
            'produksi_rusak' => 'required|numeric',
        ]);

        // Find the existing produksi record
        $produksi = ProduksiBahan::findOrFail($id);

        // Update the data
        $produksi->update([
            'tanggal' => $validatedData['tanggal'],
            'produksi_baik' => $validatedData['produksi_baik'],
            'produksi_paket' => $validatedData['produksi_paket'],
            'produksi_rusak' => $validatedData['produksi_rusak'],
            'user_id' => Auth::id(),
        ]);

        $produksiBahanId = $produksi->id; 

        // Update or create in PersediaanBahan
        PersediaanBahan::updateOrCreate(
            [
                'tanggal' => $validatedData['tanggal'],
                'produksi_id' => $produksiBahanId, 
            ],
            [
                'produksi_baik' => $validatedData['produksi_baik'],
                'produksi_paket' => $validatedData['produksi_paket'],
                'produksi_rusak' => $validatedData['produksi_rusak'],
            ]
        );

        // Trigger the event
        event(new ProduksiUpdated($produksi));

        // Return JSON response or redirect
        // return response()->json(data: ['message' => 'Produksi berhasil diperbarui']);
        // Atau jika menggunakan redirect:
        return redirect()->route('pegawai.persediaan-produksi')->with('success', 'Produksi bahan berhasil diperbarui.');
    }


    public function destroyProduksi($id)
    {
        // Cari data produksi yang ada berdasarkan ID
        $produksiBahan = ProduksiBahan::findOrFail($id);

        // Update nilai produksi menjadi 0
        $produksiBahan->update([
            'produksi_baik' => 0,
            'produksi_paket' => 0,
            'produksi_rusak' => 0,
        ]);

        // Redirect kembali ke halaman dengan pesan sukses
        return redirect()->route('pegawai.persediaan-produksi')->with('success', 'Data produksi berhasil dihapus.');
    }
}
