<?php

namespace App\Http\Controllers\Pegawai;

use Illuminate\Http\Request;
use App\Models\PersediaanBahan;
use App\Http\Controllers\Controller;
use App\Models\Bahan;
use App\Models\KategoriBhn;

// class PersediaanBahanController extends Controller
// {
//     public function viewRetur(Request $request)
//     {
//     $tanggal = $request->input('tanggal', date('Y-m-d'));
//     $kategori_id = $request->input('kategori_id', null); // Ambil input kategori_id

//     // Ambil semua kategori untuk dropdown
//     $kategoris = KategoriBhn::all();
    

//     // Query untuk mengambil persediaan_bahans dengan relasi bahan dan kategori
//     $query = PersediaanBahan::with('bahan.kategori')
//         ->select('id', 'tanggal', 'bahan_id', 'satuan', 'retur_baik', 'retur_rusak')
//         ->where('tanggal', $tanggal);

//     // Jika kategori dipilih, tambahkan filter berdasarkan kategori_id
//     if ($kategori_id) {
//         $query->whereHas('bahan', function($q) use ($kategori_id) {
//             $q->where('kategori_id', $kategori_id);
//         });
//     }

//     $persediaan_bahans = $query->get();

//     return view('pegawai.persediaan-retur', compact('persediaan_bahans', 'tanggal', 'kategori_id', 'kategoris'));
//     }
    
//     public function storeRetur(Request $request)
//     {
//     // Validasi input
//     $request->validate([
//         'tanggal' => 'required|date',
//         'bahan_id' => 'required|exists:bahans,id', // Validate that bahan_id exists in bahans table
//         'retur_baik' => 'required|numeric',
//         'retur_rusak' => 'required|numeric',
//     ]);
//     $bahan = Bahan::findOrFail($request->input('bahan_id'));

//     // Cari data yang ada berdasarkan tanggal, nama, kategori_id, dan satuan
//     $existingData = PersediaanBahan::where('tanggal', $request->input('tanggal'))
//                                    ->where('bahan_id', $request->input('bahan_id'))
//                                    ->first();

//     if ($existingData) {
//         // Jika data sudah ada, perbarui kolom yang relevan
//         $existingData->update([
//             'retur_baik' => $existingData->retur_baik + $request->input('retur_baik'), // Tambahkan retur baik ke data yang ada
//             'retur_rusak' => $existingData->retur_rusak + $request->input('retur_rusak'), // Tambahkan retur rusak ke data yang ada

//         ]);
//     } else {
//        // Jika data belum ada, buat data baru
//         PersediaanBahan::create([
//             'tanggal' => $request->input('tanggal'),
//             'bahan_id' => $request->input('bahan_id'),
//             'kategori' => $bahan->kategori_id, // Automatically pull from bahans table
//             'satuan' => $bahan->satuan, // Automatically pull from bahans table
//             'retur_baik' => $request->input('retur_baik'),
//             'retur_rusak' => $request->input('retur_rusak'),
  
//         ]);
//     }

//     // Redirect kembali ke halaman dengan pesan sukses
//     return redirect()->route('pegawai.persediaan-retur')->with('success', 'Data Retur Bahan Berhasil Ditambahkan');
//     }

//     public function editRetur($id) {
//         // Ambil data berdasarkan ID
//         $persediaan_bahans = PersediaanBahan::with('bahan')->findOrFail($id);
//         $bahans = Bahan::all();
    
//         return view('pegawai.edit-persediaan-retur', compact('persediaan_bahans', 'bahans'));
//     }
//     public function updateRetur(Request $request, $id) {
//         // Validasi input
//         $request->validate([
//             'tanggal' => 'required|date',
//             'bahan_id' => 'required|exists:bahans,id', // Validasi bahan_id
//             'retur_baik' => 'required|numeric',
//             'retur_rusak' => 'required|numeric',
//         ]);
    
//         $bahans = Bahan::findOrFail($request->input('bahan_id'));
    
//         // Ambil data yang ada berdasarkan ID
//         $existingData = PersediaanBahan::findOrFail($id);
    
//         // Perbarui data
//         $existingData->update([
//             'tanggal' => $request->input('tanggal'),
//             'bahan_id' => $request->input('bahan_id'),
//             'kategori' => $bahans->kategori_id,
//             'satuan' => $bahans->satuan,
//             'retur_baik' => $request->input('retur_baik'),
//             'retur_rusak' => $request->input('retur_rusak'),
//         ]);
    
//         // Redirect kembali ke halaman dengan pesan sukses
//         return redirect()->route('pegawai.persediaan-retur')->with('success', 'Data Retur Bahan Berhasil Diperbarui');
//     }
    
//     public function destroyRetur($id)
//     {
//     // Cari data persediaan bahan berdasarkan ID
//     $persediaan_bahans = PersediaanBahan::where('id', $id)
//         ->where(function ($query) {
//             $query->whereNotNull('retur_baik')
//                   ->orWhereNotNull('retur_rusak');
//         })
//         ->firstOrFail();
    
//     // Hapus data retur baik dan retur rusak dari data yang ada
//     $persediaan_bahans->update([
//         'retur_baik' => null, // Atur retur baik menjadi null
//         'retur_rusak' => null, // Atur retur rusak menjadi null
//     ]);

//     // Jika setelah update retur, data tidak relevan lagi, hapus seluruh row jika diperlukan
//     if (is_null($persediaan_bahans->stok_awal) && is_null($persediaan_bahans->kadaluarsa) && is_null($persediaan_bahans->pembelian)  && is_null($persediaan_bahans->tambahan_sore) && is_null($persediaan_bahans->produksi_baik) && is_null($persediaan_bahans->produksi_paket)  && is_null($persediaan_bahans->produksi_rusak)) {
//         $persediaan_bahans->delete();
//     }

//     // Redirect kembali ke halaman retur dengan pesan sukses
//     return redirect()->route('pegawai.persediaan-retur')->with('success', 'Data Retur Bahan berhasil dihapus');
//     }

//     //Pembelian
//     public function viewPembelian(Request $request){
//         $tanggal = $request->input('tanggal', date('Y-m-d'));
//         $kategori_id = $request->input('kategori_id', null); // Ambil input kategori_id

//         // Ambil semua kategori untuk dropdown
//         $kategoris = KategoriBhn::all();

//         // Query untuk mengambil persediaan_bahans dengan relasi bahan dan kategori
//         $query = PersediaanBahan::with('bahan.kategori') // Mengambil relasi bahan dan kategori
//                 ->select('id','tanggal', 'bahan_id', 'kategori', 'satuan', 'kadaluarsa', 'pembelian','tambahan_sore')
//                 ->where('tanggal', $tanggal);

//         // Jika kategori dipilih, tambahkan filter berdasarkan kategori_id
//         if ($kategori_id) {
//             $query->whereHas('bahan', function($q) use ($kategori_id) {
//                 $q->where('kategori_id', $kategori_id);
//             });
//         }

//         $persediaan_bahans = $query->get();

//         return view('pegawai.persediaan-beli', compact('persediaan_bahans', 'tanggal', 'kategori_id', 'kategoris'));
//     }

//     public function storePembelian(Request $request){
//     // Validasi input
//     $request->validate([
//         'tanggal' => 'required|date',
//         'bahan_id' => 'required|exists:bahans,id',
//         'kadaluarsa' => 'required|date',
//         'pembelian' => 'required|numeric',
//         'tambahan_sore' => 'required|numeric',
//     ]);

//     $bahan = Bahan::findOrFail($request->input('bahan_id'));

//     // Cari data yang ada berdasarkan tanggal, nama, kategori_id, dan satuan
//     $existingData = PersediaanBahan::where('tanggal', $request->input('tanggal'))
//                                    ->where('bahan_id', $request->input('bahan_id'))
//                                    ->first();

//     if ($existingData) {
//         // Jika data sudah ada, perbarui kolom yang relevan
//         $existingData->update([
//             'kadaluarsa' => $request->input('kadaluarsa'), // Jika kadaluarsa perlu diupdate
//             'pembelian' => $existingData->pembelian + $request->input('pembelian'), // Tambahkan pembelian ke data yang ada
//             'tambahan_sore' => $existingData->tambahan_sore + $request->input('tambahan_sore'), // Tambahkan tambahan sore ke data yang ada
//         ]);
//     } else {
//         // Jika data belum ada, buat data baru
//         PersediaanBahan::create([
//             'tanggal' => $request->input('tanggal'),
//             'bahan_id' => $request->input('bahan_id'),
//             'kategori' => $bahan->kategori_id, // Automatically pull from bahans table
//             'satuan' => $bahan->satuan, // Automatically pull from bahans table
//             'kadaluarsa' => $request->input('kadaluarsa'),
//             'pembelian' => $request->input('pembelian'),
//             'tambahan_sore' => $request->input('tambahan_sore'),
//         ]);
//     }

//     // Redirect kembali ke halaman dengan pesan sukses
//     return redirect()->route('pegawai.persediaan-beli')->with('success', 'Data Berhasil Ditambahkan atau Diperbarui');
//     }

//     public function editPembelian($id) {
//         // Ambil data berdasarkan ID
//         $persediaan_bahans = PersediaanBahan::with('bahan')->findOrFail($id);
//         $bahans = Bahan::all();
    
//         return view('pegawai.edit-persediaan-beli', compact('persediaan_bahans', 'bahans'));
//     }
//     public function updatePembelian(Request $request, $id) {
//         // Validasi input
//         $request->validate([
//             'tanggal' => 'required|date',
//             'bahan_id' => 'required|exists:bahans,id', // Validasi bahan_id
//             'kadaluarsa' => 'required|date',
//             'pembelian' => 'required|numeric',
//             'tambahan_sore' => 'required|numeric',
//         ]);
    
//         $bahans = Bahan::findOrFail($request->input('bahan_id'));
    
//         // Ambil data yang ada berdasarkan ID
//         $existingData = PersediaanBahan::findOrFail($id);
    
//         // Perbarui data
//         $existingData->update([
//             'tanggal' => $request->input('tanggal'),
//             'bahan_id' => $request->input('bahan_id'),
//             'kategori' => $bahans->kategori_id,
//             'satuan' => $bahans->satuan,
//             'kadaluarsa' => $request->input('kadaluarsa'),
//             'pembelian' => $request->input('pembelian'),
//             'tambahan_sore' => $request->input('tambahan_sore'),
//         ]);
    
//         // Redirect kembali ke halaman dengan pesan sukses
//         return redirect()->route('pegawai.persediaan-beli')->with('success', 'Data Retur Bahan Berhasil Diperbarui');
//     }

//     public function destroyPembelian($id)
//     {
//     // Cari data persediaan bahan berdasarkan ID
//     $persediaan_bahans = PersediaanBahan::where('id', $id)
//         ->where(function ($query) {
//             $query->whereNotNull('kadaluarsa')
//                     ->whereNotNull('pembelian')
//                     ->orWhereNotNull('tambahan_sore');
//         })
//         ->firstOrFail();
    
//     // Hapus data retur baik dan retur rusak dari data yang ada
//     $persediaan_bahans->update([
//         'kadaluarsa' => null, // Atur kadaluarsa menjadi null
//         'pembelian' => null, // Atur pembelian menjadi null
//         'tambahan_sore' => null, // Atur tambahan sore menjadi null
//     ]);

//     // Jika setelah update pembelian, data tidak relevan lagi, hapus seluruh row jika diperlukan
//     if (is_null($persediaan_bahans->stok_awal) && is_null($persediaan_bahans->retur_baik) && is_null($persediaan_bahans->retur_rusak) && is_null($persediaan_bahans->produksi_baik) && is_null($persediaan_bahans->produksi_paket)  && is_null($persediaan_bahans->produksi_rusak)) {
//         $persediaan_bahans->delete();
//     }

//     // Redirect kembali ke halaman retur dengan pesan sukses
//     return redirect()->route('pegawai.persediaan-beli')->with('success', 'Data Retur Bahan berhasil dihapus');
//     }

// //Produksi Bahan
//     public function viewProduksi(Request $request){
//         $tanggal = $request->input('tanggal', date('Y-m-d'));
//         $kategori_id = $request->input('kategori_id', null); // Ambil input kategori_id

//         // Ambil semua kategori untuk dropdown
//         $kategoris = KategoriBhn::all();

//         // Query untuk mengambil persediaan_bahans dengan relasi bahan dan kategori
//         $query = PersediaanBahan::with('bahan.kategori') // Mengambil relasi bahan dan kategori
//                 ->select('id','tanggal', 'bahan_id', 'kategori', 'satuan', 'produksi_baik', 'produksi_paket','produksi_rusak')
//                 ->where('tanggal', $tanggal);

//         // Jika kategori dipilih, tambahkan filter berdasarkan kategori_id
//         if ($kategori_id) {
//             $query->whereHas('bahan', function($q) use ($kategori_id) {
//                 $q->where('kategori_id', $kategori_id);
//             });
//         }

//         $persediaan_bahans = $query->get();

//         return view('pegawai.persediaan-produksi', compact('persediaan_bahans', 'tanggal', 'kategori_id', 'kategoris'));
//     }

//     public function storeProduksi(Request $request){
//         $request->validate([
//             'tanggal' => 'required|date',
//             'bahan_id' => 'required|exists:bahans,id',
//             'produksi_baik' => 'required|numeric',
//             'produksi_paket' => 'required|numeric',
//             'produksi_rusak' => 'required|numeric',
//         ]);

//         $bahans = Bahan::findOrFail($request->input('bahan_id'));
    
//         // Cari data yang ada berdasarkan tanggal, nama, kategori_id, dan satuan
//         $existingData = PersediaanBahan::where('tanggal', $request->input('tanggal'))
//                                         ->where('bahan_id', $request->input('bahan_id'))
//                                         ->first();
    
//         if ($existingData) {
//             // Jika data sudah ada, perbarui kolom yang relevan
//             $existingData->update([
//                 'produksi_baik' => $existingData->produksi_baik + $request->input('produksi_baik'), // Tambahkan produksi baik ke data yang ada
//                 'produksi_paket' => $existingData->produksi_paket + $request->input('produksi_paket'), // Tambahkan produksi paket ke data yang ada
//                 'produksi_rusak' => $existingData->produksi_rusak + $request->input('produksi_rusak'), // Tambahkan produksi rusak ke data yang ada
//             ]);
//         } else {
//             // Jika data belum ada, buat data baru
//             PersediaanBahan::create([
//                 'tanggal' => $request->input('tanggal'),
//                 'bahan_id' => $request->input('bahan_id'),
//                 'kategori' => $bahans->kategori_id, // Automatically pull from bahans table
//                 'satuan' => $bahans->satuan, // Automatically pull from bahans table
//                 'produksi_baik' => $request->input('produksi_baik'),
//                 'produksi_paket' => $request->input('produksi_paket'),
//                 'produksi_rusak' => $request->input('produksi_rusak'),
//             ]);
//         }
//         return redirect()->route('pegawai.persediaan-produksi')->with('success', 'Data Berhasil Ditambahkan atau Diperbarui');
//     }
//     public function editProduksi($id) {
//         // Ambil data berdasarkan ID
//         $persediaan_bahans = PersediaanBahan::with('bahan')->findOrFail($id);
//         $bahans = Bahan::all();
    
//         return view('pegawai.edit-persediaan-produksi', compact('persediaan_bahans', 'bahans'));
//     }
//     public function updateproduksi(Request $request, $id) {
//         // Validasi input
//         $request->validate([
//             'tanggal' => 'required|date',
//             'bahan_id' => 'required|exists:bahans,id', // Validasi bahan_id
//             'produksi_baik' => 'required|numeric',
//             'produksi_paket' => 'required|numeric',
//             'produksi_rusak' => 'required|numeric',
//         ]);
    
//         $bahans = Bahan::findOrFail($request->input('bahan_id'));
    
//         // Ambil data yang ada berdasarkan ID
//         $existingData = PersediaanBahan::findOrFail($id);
    
//         // Perbarui data
//         $existingData->update([
//             'tanggal' => $request->input('tanggal'),
//             'bahan_id' => $request->input('bahan_id'),
//             'kategori' => $bahans->kategori_id,
//             'satuan' => $bahans->satuan,
//             'produksi_baik' => $request->input('produksi_baik'),
//             'produksi_paket' => $request->input('produksi_paket'),
//             'produksi_rusak' => $request->input('produksi_rusak'),
//         ]);
    
//         // Redirect kembali ke halaman dengan pesan sukses
//         return redirect()->route('pegawai.persediaan-produksi')->with('success', 'Data Retur Bahan Berhasil Diperbarui');
//     }
//     public function destroyProduksi($id)
//     {
//     // Cari data persediaan bahan berdasarkan ID
//     $persediaan_bahans = PersediaanBahan::where('id', $id)
//         ->where(function ($query) {
//             $query->whereNotNull('produksi_baik')
//                     ->whereNotNull('produksi_paket')
//                     ->orWhereNotNull('produksi_rusak');
//         })
//         ->firstOrFail();
    
//     // Hapus data retur baik dan retur rusak dari data yang ada
//     $persediaan_bahans->update([
//         'produksi_baik' => null, // Atur kadaluarsa menjadi null
//         'produksi_paket' => null, // Atur pembelian menjadi null
//         'produksi_rusak' => null, // Atur tambahan sore menjadi null
//     ]);

//     // Jika setelah update pembelian, data tidak relevan lagi, hapus seluruh row jika diperlukan
//     if (is_null($persediaan_bahans->stok_awal) && is_null($persediaan_bahans->retur_baik) && is_null($persediaan_bahans->retur_rusak) && is_null($persediaan_bahans->kadaluarsa) && is_null($persediaan_bahans->pembelian)  && is_null($persediaan_bahans->tambahan_sore)) {
//         $persediaan_bahans->delete();
//     }

//     // Redirect kembali ke halaman retur dengan pesan sukses
//     return redirect()->route('pegawai.persediaan-produksi')->with('success', 'Data Retur Bahan berhasil dihapus');
//     }
// 
