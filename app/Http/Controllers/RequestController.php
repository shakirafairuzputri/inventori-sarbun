<?php

namespace App\Http\Controllers;

use App\Models\Bahan;
use App\Models\Barang;
use Illuminate\Http\Request;
use App\Models\Requests;
use Illuminate\Support\Facades\Auth;


class RequestController extends Controller
{
    public function viewRequest(){
        $request_input = Requests::all();

        return view ('pegawai.request-input', compact ('request_input'));
    }
    public function tambahRequest(){
        $request_input = Requests::all();

        return view ('pegawai.tambah-request', compact ('request_input'));
    }
    public function storeRequest(Request $request)
    {
        // Validasi awal input
        $validated = $request->validate([
            'kelompok' => 'required|string',
            'nama' => 'required|string',
            'deskripsi' => 'nullable|string',
        ]);

        if ($validated['kelompok'] === 'Bahan Utama') {
            $existsInBahan = Bahan::where('nama', $validated['nama'])->exists();
            if ($existsInBahan) {
                return redirect()->back()->withErrors(['nama' => 'Nama sudah ada di tabel bahan.'])->withInput();
            }
        } elseif (in_array($validated['kelompok'], ['Bahan Lain', 'Barang'])) {
            $existsInBarang = Barang::where('nama_brg', $validated['nama'])->exists();
            if ($existsInBarang) {
                return redirect()->back()->withErrors(['nama' => 'Nama sudah ada di tabel barang.'])->withInput();
            }
        }

        Requests::create([
            'user_id' => Auth::id(),
            'kelompok' => $validated['kelompok'],
            'nama' => $validated['nama'],
            'deskripsi' => $validated['deskripsi'],
            'status' => 'Pending',
        ]);

        return redirect()->route('pegawai.request-input')->with('success', 'Data berhasil ditambahkan');
    }

    public function viewRequestS(){
        $request_input = Requests::all();

        return view ('supervisor.request-input', compact ('request_input'));
    }
    public function approveRequest($requestId)
    {
        $request = Requests::findOrFail($requestId);
        $request->status = 'approved';
        $request->save();

        return redirect()->route('supervisor.request-input')->with('success', 'Request Approved');
    }
}
