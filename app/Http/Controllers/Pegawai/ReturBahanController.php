<?php

namespace App\Http\Controllers\Pegawai;

use Illuminate\Http\Request;
use App\Models\ReturBahan;
use App\Models\PembelianBahan;
use App\Models\ProduksiBahan;
use App\Models\PersediaanBahan;
use App\Http\Controllers\Controller;
use App\Models\Bahan;
use App\Models\KategoriBhn;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ReturBahanController extends Controller
{
    public function viewRetur(Request $request)
    {
        $tanggal = $request->input('tanggal', date('Y-m-d'));
        $kategori_id = $request->input('kategori_id', null); 

        $kategoris = KategoriBhn::all();

        $query = ReturBahan::with('bahan.kategori')
            ->select('id', 'tanggal', 'bahan_id', 'retur_baik', 'retur_rusak', 'jenis_kerusakan')
            ->where('tanggal', $tanggal);


        if ($kategori_id) {
            $query->whereHas('bahan', function($q) use ($kategori_id) {
                $q->where('kategori_id', $kategori_id);
            });
        }
        $returBahans = $query->get();
        return view('pegawai.persediaan-retur', compact('returBahans', 'kategoris', 'tanggal', 'kategori_id'));
    }
    public function storeRetur(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'tanggal' => 'required|date',
            'bahan_id' => 'required|exists:bahans,id',
            'retur_baik' => 'nullable|numeric|min:0',
            'retur_rusak' => 'nullable|numeric|min:0',
            'jenis_kerusakan' => 'required|in:Kadaluarsa,Rusak',
        ]);

        $returBaik = $validated['retur_baik'] ?? 0;
        $returRusak = $validated['retur_rusak'] ?? 0;
        $jenisKerusakan = $validated['jenis_kerusakan'];

        // Ambil data stok bahan dari tabel bahan
        $bahan = Bahan::find($validated['bahan_id']);
        if (!$bahan) {
            return back()->withErrors(['bahan_id' => 'Data bahan tidak ditemukan.']);
        }

        // Validasi stok bahan
        if (($jenisKerusakan === 'Kadaluarsa' && $returBaik > $bahan->stok) ||
            ($jenisKerusakan === 'Rusak' && $returRusak > $bahan->stok)) {
            return back()->withErrors([
                'error' => 'Jumlah retur tidak boleh melebihi stok. (' . $bahan->stok . ').'
            ]);
        }

        // Cek apakah ada data retur untuk tanggal dan bahan_id yang sama
        $returBahan = ReturBahan::where('tanggal', $validated['tanggal'])
                                ->where('bahan_id', $validated['bahan_id'])
                                ->where('jenis_kerusakan', $jenisKerusakan)
                                ->first();

        if ($returBahan) {
            // Jika data sudah ada, tambahkan nilai retur_baik dan retur_rusak
            $returBahan->retur_baik += $returBaik;
            $returBahan->retur_rusak += $returRusak;
            $returBahan->save();
        } else {
            // Buat data retur baru jika belum ada
            $returBahan = ReturBahan::create([
                'tanggal' => $validated['tanggal'],
                'bahan_id' => $validated['bahan_id'],
                'retur_baik' => $returBaik,
                'retur_rusak' => $returRusak,
                'user_id' => Auth::id(),
                'jenis_kerusakan' => $jenisKerusakan,
                'status' => 'Sudah Dikembalikan',
            ]);
        }

        // Cek atau tambahkan data di tabel pembelian_bahan
        $pembelianBahan = PembelianBahan::firstOrCreate(
            ['tanggal' => $validated['tanggal'], 'bahan_id' => $validated['bahan_id']],
        );

        // Cek atau tambahkan data di tabel produksi_bahan
        $produksiBahan = ProduksiBahan::firstOrCreate(
            ['tanggal' => $validated['tanggal'], 'pembelian_id' => $pembelianBahan->id]
        );

        // Cek atau tambahkan data di tabel persediaan_bahans
        PersediaanBahan::firstOrCreate(
            ['tanggal' => $validated['tanggal'], 'produksi_id' => $produksiBahan->id]
        );

        // Redirect dengan pesan sukses
        return redirect()->route('pegawai.persediaan-retur')->with('success', 'Retur bahan berhasil ditambahkan.');
    }

    
                    
    public function editRetur($id)
    {
        $returBahan = ReturBahan::findOrFail($id);
        $bahans = Bahan::all();

        return view('pegawai.edit-persediaan-retur', compact('returBahan', 'bahans'));
        }

    public function updateRetur(Request $request, $id)
    {
        // Validasi input
        $returBahans = $request->validate([
            'tanggal' => 'required|date',
            'bahan_id' => 'required|exists:bahans,id',
            'retur_baik' => 'required|numeric',
            'retur_rusak' => 'required|numeric',
        ]);

        // Find the existing retur record
        $returBahan = ReturBahan::findOrFail($id);
        $bahan = Bahan::findOrFail($request->input('bahan_id'));

        // Update the data
        $returBahan->update([
            'tanggal' => $returBahans['tanggal'],
            'bahan_id' => $request->input('bahan_id'),
            'retur_baik' => $returBahans['retur_baik'],
            'retur_rusak' => $returBahans['retur_rusak'],
            'user_id' => Auth::id(), 
        ]);

        
        return redirect()->route('pegawai.persediaan-retur')->with('success', 'Retur bahan berhasil diperbarui.');
    }

    public function destroyRetur($id)
    {
        // Cari data retur yang ada berdasarkan ID
        $returBahan = ReturBahan::findOrFail($id);

        // Hapus data retur
        $returBahan->delete();

        // Redirect kembali ke halaman dengan pesan sukses
        return redirect()->route('pegawai.persediaan-retur')->with('success', 'Data retur berhasil dihapus.');
    }

}