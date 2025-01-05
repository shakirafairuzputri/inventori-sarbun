<?php

namespace App\Http\Controllers\Supervisor;

use App\Imports\BarangImport;
use App\Models\Barang;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Validators\ValidationException;

class BarangController extends Controller
{
    public function viewBarang()
    {
        $barangs = Barang::all();
        return view ('supervisor.daftar-brg', compact ('barangs'));
    }
    // Menyimpan data bahan (STORE)
    public function storeBarang(Request $request)
    {
        // Validasi data input
        $request->validate([
            'nama_brg' => 'required',
            'kelompok' => 'required',
            'kategori_brg_id' => 'required|exists:kategori_brgs,id',
            'satuan_brg_id' => 'required|exists:satuans,id',
            'stok_brg' => 'required|numeric',
        ]);

        // Cek apakah barang dengan nama dan kategori yang sama sudah ada di database
        $existingBarang = Barang::where('nama_brg', $request->nama_brg)
            ->where('kategori_brg_id', $request->kategori_brg_id)
            ->first();

        if ($existingBarang) {
            // Jika barang sudah ada, kembalikan ke halaman sebelumnya dengan pesan error
            return redirect()->back()->with('error', 'Barang dengan nama dan kategori yang sama sudah ada!');
        }

        // Jika barang belum ada, simpan data baru
        Barang::create([
            'nama_brg' => $request->nama_brg,
            'kelompok' => $request->kelompok,
            'kategori_brg_id' => $request->kategori_brg_id,
            'satuan_brg_id' => $request->satuan_brg_id,
            'stok_brg' => $request->stok_brg,
        ]);

        // Arahkan kembali dengan pesan sukses
        return redirect()->route('supervisor.daftar-brg')->with('success', 'Data Barang Berhasil Ditambahkan');
    }
    public function editBarang($id)
    {
        $barangs = Barang::findOrFail($id);
        return view('supervisor.edit-daftar-brg', compact('barangs'));
    }
    public function updateBarang(Request $request, $id)
    {
        $request->validate([
            'nama_brg' => 'required',
            'kelompok' => 'required',
            'kategori_brg_id' => 'required|exists:kategori_brgs,id', 
            'satuan_brg_id' => 'required|exists:satuans,id',
            'stok_brg' => 'required|numeric',
        ]);

        $barangs = Barang::findOrFail($id);

        $barangs->update([
            'nama_brg' => $request->input('nama_brg'),
            'kelompok' => $request->input('kelompok'),
            'kategori_brg_id' => $request->input('kategori_brg_id'),  
            'satuan_brg_id' => $request->input('satuan_brg_id'),
            'stok_brg' => $request->input('stok_brg'),
        ]);
        return redirect()->route('supervisor.daftar-brg')->with('success', 'Data Barang Berhasil Diperbarui');
    }

    public function destroyBarang($id)
    {
        // Temukan data berdasarkan ID
        $barangs = Barang::findOrFail($id);
        // Hapus data
        $barangs->delete();
        // Redirect atau kirim respon setelah penghapusan
        return redirect()->route('supervisor.daftar-brg')->with('success', 'Data berhasil dihapus');
    }
    public function importExcel(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        if (!$request->hasFile('file')) {
            return redirect()->back()->with('error', 'File tidak ditemukan. Silakan unggah file yang valid.');
        }

        Log::info('File uploaded: ' . $request->file('file')->getClientOriginalName());

        DB::beginTransaction();
        try {
            // Melakukan impor dengan BarangImport
            Excel::import(new BarangImport, $request->file('file'));

            // Menghitung jumlah barang baru yang berhasil ditambahkan setelah proses import
            $addedCount = Barang::where('created_at', '>=', now()->subMinutes(5))->count();  // Menentukan waktu untuk menghitung barang baru

            // Jika ada data yang berhasil ditambahkan
            if ($addedCount > 0) {
                Log::info("$addedCount barang berhasil diimpor!");
                DB::commit();
                return redirect()->route('supervisor.daftar-brg')->with('success', "$addedCount barang berhasil diimpor!");
            } else {
                // Jika tidak ada data yang ditambahkan, rollback transaksi
                DB::rollBack();
                Log::warning('Tidak ada barang yang berhasil diimpor. Semua data sudah ada.');
                return redirect()->back()->with('error', 'Tidak ada barang yang berhasil diimpor, semua data sudah ada.');
            }
        } catch (ValidationException $e) {
            DB::rollBack();
            $failures = $e->failures();
            foreach ($failures as $failure) {
                Log::error('Validation error on row ' . $failure->row() . ': ' . json_encode($failure->errors()));
            }
            return redirect()->back()->with('error', 'Gagal mengimpor data. Periksa format file.');
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Error during import: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal mengimpor data: ' . $e->getMessage());
        }
    }
}