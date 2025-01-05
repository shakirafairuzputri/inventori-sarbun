<?php

namespace App\Http\Controllers\Supervisor;

use App\Models\KategoriBrg;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class KategoriBarangController extends Controller
{
    public function viewKategoriBrg(){
        $kategori_brgs = KategoriBrg::all();

        return view ('supervisor.kategori-brg', compact ('kategori_brgs'));
    }

    public function storeKategoriBrg(Request $request)
    {
        $request->validate([
            'kategori_brg' => 'required|unique:kategori_brgs,kategori_brg',
        ]);

        $existingData = KategoriBrg::where('kategori_brg', $request->input('kategori_brg'))->first();
        if ($existingData) {
            return redirect()->route('supervisor.tambah-kategori-brg')
                ->withErrors(['kategori_brg' => 'Data kategori barang sudah ada.'])
                ->withInput();
        }
        KategoriBrg::create($request->all());
        return redirect()->route('supervisor.kategori-brg')->with('success', 'Data Berhasil Ditambahkan');
    }

    public function destroyKategoriBrg($id){
        $kategori_brgs = KategoriBrg::findOrFail($id);

        $kategori_brgs->delete();
        return redirect()->route('supervisor.kategori-brg')->with('success', 'Data Berhasil Dihapus');

    }
}
