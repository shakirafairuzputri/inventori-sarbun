<?php

namespace App\Http\Controllers\Supervisor;

use App\Models\Satuan;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SatuanController extends Controller
{
    public function viewSatuan(){
        $satuans = Satuan::all();

        return view('supervisor.unit', compact ('satuans'));
    }

    public function storeSatuan(Request $request)
    {
        $request->validate([
            'satuan_brg' => 'required',
        ]);

        // Daftar satuan yang tidak boleh diinput
        $restrictedUnits = ['ton', 'kwintal', 'ons'];

        // Periksa apakah input `satuan_brg` mengandung satuan yang dilarang
        if (in_array(strtolower($request->input('satuan_brg')), $restrictedUnits)) {
            return redirect()->route('supervisor.tambah-unit')
                ->withErrors(['satuan_brg' => 'Satuan ini tidak boleh ditambahkan.'])
                ->withInput();
        }

        // Cek apakah data satuan_brg sudah ada
        $existingData = Satuan::where('satuan_brg', $request->input('satuan_brg'))->first();
        if ($existingData) {
            return redirect()->route('supervisor.tambah-unit')
                ->withErrors(['satuan_brg' => 'Data satuan barang sudah ada.'])
                ->withInput();
        }

        // Jika data belum ada dan tidak dilarang, simpan data baru
        Satuan::create($request->all());
        return redirect()->route('supervisor.unit')->with('success', 'Data Berhasil Ditambahkan');
    }

    public function destroySatuan($id){
        $satuans = Satuan::findOrFail($id);

        $satuans->delete();
        return redirect()->route('supervisor.unit')->with('success', 'Data Berhasil Dihapus');

    }
}