<?php

namespace App\Http\Controllers\Pegawai;

use Illuminate\Http\Request;
use App\Models\PersediaanBarang;
use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\KategoriBrg;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
class PersediaanBarangController extends Controller
{
    //BARANG MASUK
    public function viewBrgMasuk(Request $request){
        $tanggal = $request->input('tanggal', date('Y-m-d'));
        $kategori_id = $request->input('kategori_id', null);
        $kelompok = $request->input('kelompok', null);

        $kategoris = KategoriBrg::all();

        $query = PersediaanBarang::with(['barang.kategori_brg', 'userMasuk'])
        ->select('id', 'tanggal', 'barang_id', 'tambah', 'pegawai_brgm')
        ->where('tanggal', $tanggal);

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
        return view('pegawai.persediaan-brgm', compact('persediaan_barangs', 'tanggal', 'kategori_id', 'kelompok', 'kategoris'));
    }

    public function storeBrgMasuk(Request $request)
    {
        // Validasi input
        $request->validate([
            'tanggal' => 'required|date',
            'barang_id' => 'required|exists:barangs,id', // Validate that barangs_id exists in barang table
            'tambah' => 'required|numeric',
        ]);
        $barang = Barang::findOrFail($request->input('barang_id'));
        

        // Cari data yang ada berdasarkan tanggal, nama, kategori_id, dan satuan
        $existingData = PersediaanBarang::where('tanggal', $request->input('tanggal'))
                                    ->where('barang_id', $request->input('barang_id'))
                                    ->first();

        if ($existingData) {
            // Jika data sudah ada, perbarui kolom yang relevan
            $existingData->update([
                'tambah' => $existingData->tambah + $request->input('tambah'), // Tambahkan retur baik ke data yang ada

            ]);
        } else {
            // Jika data belum ada, buat data baru
            PersediaanBarang::create([
                'tanggal' => $request->input('tanggal'),
                'barang_id' => $request->input('barang_id'),
                'kategori_brg' => $barang->kategori_brg_id,
                'satuan_brg' => $barang->satuan_brg_id,
                'tambah' => $request->input('tambah'),
                'pegawai_brgm' => Auth::id(),
            ]);
        }

        // Redirect kembali ke halaman dengan pesan sukses
        return redirect()->route('pegawai.persediaan-brgm')->with('success', 'Data Barang Mausk Berhasil Ditambahkan');
    }

    public function editBrgMasuk($id) {
        // Ambil data berdasarkan ID
        $persediaan_barangs = PersediaanBarang::with('barang')->findOrFail($id);
        $barangs = Barang::all();
    
        return view('pegawai.edit-persediaan-brgm', compact('persediaan_barangs', 'barangs'));
    }
    public function updateBrgMasuk(Request $request, $id) {
        // Validasi input
        $request->validate([
            'tanggal' => 'required|date',
            'barang_id' => 'required|exists:barangs,id', // Validasi bahan_id
            'tambah' => 'required|numeric',
        ]);
    
        $barangs = Barang::findOrFail($request->input('barang_id'));
    
        // Ambil data yang ada berdasarkan ID
        $existingData = PersediaanBarang::findOrFail($id);
    
        // Perbarui data
        $existingData->update([
            'tanggal' => $request->input('tanggal'),
            'barang_id' => $request->input('barang_id'),
            'kategori_brg' => $barangs->kategori_brg_id,
            'satuan_brg' => $barangs->satuan_brg_id,
            'tambah' => $request->input('tambah'),
            'pegawai_brgm' => Auth::id(),
        ]);
    
        // Redirect kembali ke halaman dengan pesan sukses
        return redirect()->route('pegawai.persediaan-brgm')->with('success', 'Data Barang Masuk Berhasil Diperbarui');
    }
    public function destroyBrgMasuk($id)
    {
        $persediaan_barangs = PersediaanBarang::where('id', $id)
            ->where(function ($query) {
                $query->whereNotNull('tambah');
            })
            ->firstOrFail();
        
        $persediaan_barangs->update([
            'tambah' => null, 
        ]);

        if (is_null($persediaan_barangs->stok_awal) && is_null($persediaan_barangs->tambah) && is_null($persediaan_barangs->kurang) && is_null($persediaan_barangs->sisa)) {
            $persediaan_barangs->delete();
        }

        return redirect()->route('pegawai.persediaan-brgm')->with('success', 'Data Barang Masuk berhasil dihapus');
    }
    // BARANG KELUAR
    public function viewBrgKeluar(Request $request)
    {
        $tanggal = $request->input('tanggal', date('Y-m-d'));
        $kategori_id = $request->input('kategori_id', null); // Ambil input kategori_id
        $kelompok = $request->input('kelompok', null);

        // Ambil semua kategori untuk dropdown
        $kategoris = KategoriBrg::all();

        // Query untuk mengambil persediaan_barangs dengan relasi barang dan kategori
        $query = PersediaanBarang::with(['barang.kategori_brg', 'userKeluar'])
            ->select('id', 'tanggal', 'barang_id', 'kurang', 'pegawai_brgk')
            ->where('tanggal', $tanggal);


        // Jika kategori dipilih, tambahkan filter berdasarkan kategori_id
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

        // Pastikan semua variabel dikirim ke view
        return view('pegawai.persediaan-brgk', compact('persediaan_barangs', 'tanggal', 'kategori_id', 'kelompok', 'kategoris'));
    }


    public function storeBrgKeluar(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'barang_id' => 'required|exists:barangs,id',
            'kurang' => 'required|numeric',
        ]);

        // Cari barang berdasarkan ID
        $barang = Barang::findOrFail($request->input('barang_id'));

        // Cari data persediaan barang yang ada berdasarkan tanggal dan barang_id
        $existingData = PersediaanBarang::where('tanggal', $request->input('tanggal'))
                                        ->where('barang_id', $request->input('barang_id'))
                                        ->first();

        // Jika data sudah ada, perbarui kolom 'kurang' dan 'sisa'
        if ($existingData) {
            $existingData->update([
                'kurang' => $existingData->kurang + $request->input('kurang'), 
                'pegawai_brgk' => Auth::id(),
            ]);
        } else {
            PersediaanBarang::create([
                'tanggal' => $request->input('tanggal'),
                'barang_id' => $request->input('barang_id'),
                'kategori_brg' => $barang->kategori_brg_id, // Ambil kategori dari barang
                'satuan_brg' => $barang->satuan_brg_id, // Ambil satuan dari barang
                'kurang' => $request->input('kurang'),
                'pegawai_brgk' => Auth::id(),

            ]);
        }
        Log::info('Storing Barang Keluar', [
            'tanggal' => $request->input('tanggal'),
            'barang_id' => $request->input('barang_id'),
            'kurang' => $request->input('kurang'),
            'pegawai_brgk' => Auth::id(),
        ]);
        

        // Redirect dengan pesan sukses
        return redirect()->route('pegawai.persediaan-brgk')->with('success', 'Data Barang Keluar Berhasil Ditambahkan');
    }
    public function editBrgKeluar($id) {
        // Ambil data berdasarkan ID
        $persediaan_barangs = PersediaanBarang::with('barang')->findOrFail($id);
        $barangs = Barang::all();
    
        return view('pegawai.edit-persediaan-brgk', compact('persediaan_barangs', 'barangs'));
    }
    public function updateBrgKeluar(Request $request, $id) {
        // Validasi input
        $request->validate([
            'tanggal' => 'required|date',
            'barang_id' => 'required|exists:barangs,id', // Validasi bahan_id
            'kurang' => 'required|numeric',
        ]);
    
        $barangs = Barang::findOrFail($request->input('barang_id'));
    
        // Ambil data yang ada berdasarkan ID
        $existingData = PersediaanBarang::findOrFail($id);
    
        // Perbarui data
        $existingData->update([
            'tanggal' => $request->input('tanggal'),
            'barang_id' => $request->input('barang_id'),
            'kategori_brg' => $barangs->kategori_brg_id,
            'satuan_brg' => $barangs->satuan_brg_id,
            'kurang' => $request->input('kurang'),
            'pegawai_brgk' => Auth::id(),

        ]);
    
        // Redirect kembali ke halaman dengan pesan sukses
        return redirect()->route('pegawai.persediaan-brgk')->with('success', 'Data Barang Masuk Berhasil Diperbarui');
    }    
    public function destroyBrgKeluar($id)
    {
        $persediaan_barangs = PersediaanBarang::where('id', $id)
            ->where(function ($query) {
                $query->whereNotNull('kurang');
            })
            ->firstOrFail();
        
        $persediaan_barangs->update([
            'kurang' => null, 
        ]);

        if (is_null($persediaan_barangs->stok_awal) && is_null($persediaan_barangs->tambah) && is_null($persediaan_barangs->kurang) && is_null($persediaan_barangs->sisa)) {
            $persediaan_barangs->delete();
        }

        return redirect()->route('pegawai.persediaan-brgk')->with('success', 'Data Barang Keluar berhasil dihapus');
    }

    
}
