<?php

namespace App\Http\Controllers\Supervisor;

use App\Imports\BahanImport;
use App\Models\Bahan;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Validators\ValidationException;

class BahanController extends Controller
{
    // tampil semua data bahan (READ)
    public function viewBahan()
    {
        $bahans = Bahan::all();
        return view ('supervisor.daftar-bhn', compact ('bahans'));
    }
    public function store(Request $request)
    {
        // Validasi data input
        $request->validate([
            'nama' => 'required',
            'kategori_id' => 'required|exists:kategori_bhns,id',
            'satuan' => 'required',
            'stok' => 'required|numeric',
        ]);

        // Cek apakah bahan dengan nama dan kategori yang sama sudah ada di database
        $existingBahan = Bahan::where('nama', $request->nama)
            ->where('kategori_id', $request->kategori_id)
            ->first();

        if ($existingBahan) {
            // Jika bahan sudah ada, kembalikan ke halaman sebelumnya dengan pesan error
            return redirect()->back()->with('error', 'Bahan dengan nama dan kategori yang sama sudah ada!');
        }

        // Jika bahan belum ada, simpan data baru
        Bahan::create($request->all());

        // Arahkan kembali dengan pesan sukses
        return redirect()->route('supervisor.daftar-bhn')->with('success', 'Data Bahan Berhasil Ditambahkan');
    }
    public function editBahan($id)
    {
        $bahans = Bahan::findOrFail($id);
        return view('supervisor.edit-daftar-bhn', compact('bahans'));
    }
    public function updateBahan(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required',
            'kategori_id' => 'required|exists:kategori_bhns,id',
            'satuan' => 'required',
            'stok' => 'required|numeric',
        ]);

        $bahan = Bahan::findOrFail($id);

        $bahan->update([
            'nama' => $request->input('nama'),
            'kategori_id' => $request->input('kategori_id'), 
            'satuan' => $request->input('satuan'),
            'stok' => $request->input('stok'),
        ]);

        return redirect()->route('supervisor.daftar-bhn')->with('success', 'Data Bahan Berhasil Diperbarui');
    }   

    public function destroyBahan($id)
    {
        $bahans = Bahan::findOrFail($id);
        $bahans->delete();
        return redirect()->route('supervisor.daftar-bhn')->with('success', 'Data berhasil dihapus');
    }public function importExcel(Request $request)
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
            // Melakukan impor dengan BahanImport
            Excel::import(new BahanImport, $request->file('file'));
    
            // Menghitung jumlah bahan baru yang berhasil ditambahkan setelah proses import
            $addedCount = Bahan::where('created_at', '>=', now()->subMinutes(5))->count();  // Menentukan waktu untuk menghitung bahan baru
    
            // Jika ada data yang berhasil ditambahkan
            if ($addedCount > 0) {
                Log::info("$addedCount data berhasil diimpor!");
                DB::commit();
                return redirect()->route('supervisor.daftar-bhn')->with('success', "$addedCount data berhasil diimpor!");
            } else {
                // Jika tidak ada data yang ditambahkan, rollback transaksi
                DB::rollBack();
                Log::warning('Tidak ada data yang berhasil diimpor. Semua data sudah ada.');
                return redirect()->back()->with('error', 'Tidak ada data yang berhasil diimpor, semua data sudah ada.');
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