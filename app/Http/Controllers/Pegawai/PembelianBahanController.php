<?php

namespace App\Http\Controllers\Pegawai;

use Illuminate\Http\Request;
use App\Models\PembelianBahan;
use App\Http\Controllers\Controller;
use App\Models\Bahan;
use App\Models\KategoriBhn;
use App\Models\ProduksiBahan;
use App\Models\PersediaanBahan;
use App\Models\ReturBahan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PembelianBahanController extends Controller
{
    public function viewPembelian(Request $request)
    {
        $tanggal = $request->input('tanggal', date('Y-m-d'));
        $kategori_id = $request->input('kategori_id', null); 

        $kategoris = KategoriBhn::all();

        $query = PembelianBahan::with(['bahan.kategori'])
            ->select('id', 'tanggal', 'bahan_id', 'pembelian', 'tambahan_sore')
            ->where('tanggal', $tanggal);

        if ($kategori_id) {
            $query->whereHas('bahan', function($q) use ($kategori_id) {
                $q->where('kategori_id', $kategori_id);
            });
        }

        $pembelianBahans = $query->get()->filter(function($pembelian) {
            return !(is_null($pembelian->pembelian) && is_null($pembelian->tambahan_sore) || $pembelian->pembelian == 0 && $pembelian->tambahan_sore == 0);
        });

        return view('pegawai.persediaan-beli', compact('pembelianBahans', 'kategoris', 'tanggal', 'kategori_id'));
    }

    public function storePembelian(Request $request)
    {
        $validated = $request->validate([
            'tanggal' => 'required|date',
            'bahan_id' => 'required|exists:bahans,id', // Validasi bahan
            'pembelian' => 'nullable|numeric',
            'tambahan_sore' => 'nullable|numeric',
        ]);

        // Set nilai default untuk pembelian dan tambahan sore
        $pembelian = $validated['pembelian'] ?? 0; // Jika null, set ke 0
        $tambahanSore = $validated['tambahan_sore'] ?? 0; // Jika null, set ke 0

        // Cari atau buat data pembelian untuk bahan_id dan tanggal yang sama
        $existingData = PembelianBahan::where('tanggal', $validated['tanggal'])
                                        ->where('bahan_id', $validated['bahan_id'])
                                        ->first();

        if ($existingData) {
            // Jika data pembelian sudah ada, tambahkan nilai pembelian dan tambahan sore
            $existingData->pembelian += $pembelian;
            $existingData->tambahan_sore += $tambahanSore;
            $existingData->user_id = Auth::id();
            $existingData->save();
        } else {
            // Buat data pembelian baru
            $existingData = PembelianBahan::create([
                'tanggal' => $validated['tanggal'],
                'bahan_id' => $validated['bahan_id'], // Hubungkan langsung dengan bahan_id
                'pembelian' => $pembelian,
                'tambahan_sore' => $tambahanSore,
                'user_id' => Auth::id(),
            ]);
        }

        // Simpan atau update data ke tabel produksi dengan pembelian_id
        $produksiBahan = ProduksiBahan::updateOrCreate([
            'tanggal' => $validated['tanggal'],
            'pembelian_id' => $existingData->id,
        ]);

        // Simpan atau update data ke tabel persediaan bahan dengan produksi_id
        PersediaanBahan::updateOrCreate([
            'tanggal' => $validated['tanggal'],
            'produksi_id' => $produksiBahan->id, // Gunakan produksi_id dari data yang baru atau yang sudah ada
        ]);

        // Redirect dengan pesan sukses
        return redirect()->route('pegawai.persediaan-beli')->with('success', 'Pembelian bahan berhasil ditambahkan.');
    }


    public function editPembelian($id)
    {
        $pembelianBahans = PembelianBahan::findOrFail($id);
        $bahans = Bahan::all();

        $bahanId = $pembelianBahans->bahan_id;
        return view('pegawai.edit-persediaan-beli', compact('pembelianBahans', 'bahanId', 'bahans'));
    }

    public function updatePembelian(Request $request, $id)
    {
        // Validasi input
        $validatedData = $request->validate([
            'tanggal' => 'required|date',
            'bahan_id' => 'required|exists:bahans,id',
            'pembelian' => 'nullable|numeric',
            'tambahan_sore' => 'nullable|numeric',
        ]);
    
        // Cari record pembelian bahan yang akan diperbarui
        $pembelianBahan = PembelianBahan::findOrFail($id);
        
        // Cari data bahan berdasarkan bahan_id yang diinputkan
        $bahan = Bahan::findOrFail($request->input('bahan_id'));
    
        // Perbarui data pembelian bahan
        $pembelianBahan->update([
            'tanggal' => $validatedData['tanggal'],
            'retur_id' => $pembelianBahan->retur_id, // Retur tetap seperti sebelumnya
            'bahan_id' => $request->input('bahan_id'),
            'pembelian' => $validatedData['pembelian'],
            'tambahan_sore' => $validatedData['tambahan_sore'],
            'user_id' => Auth::id(), // Perbarui dengan ID pengguna yang melakukan update
        ]);
    
        // Redirect dengan pesan sukses
        return redirect()->route('pegawai.persediaan-beli')->with('success', 'Pembelian bahan berhasil diperbarui.');
    }
        
    public function destroyPembelian($id)
    {
        // Cari data pembelian yang ada berdasarkan ID
        $pembelianBahan = PembelianBahan::findOrFail($id);

        // Update nilai pembelian dan tambahan sore menjadi 0
        $pembelianBahan->update([
            'pembelian' => 0,
            'tambahan_sore' => 0,
        ]);

        // Redirect kembali ke halaman dengan pesan sukses
        return redirect()->route('pegawai.persediaan-beli')->with('success', 'Data pembelian berhasil dihapus.');
    }
}
