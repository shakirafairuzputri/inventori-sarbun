<?php

namespace App\Http\Controllers\Supervisor;

use App\Models\KategoriBhn;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class KategoriBahanController extends Controller
{
    public function viewKategoriBhn(){
        $kategori_bhns = KategoriBhn::all();

        return view('supervisor.kategori-bhn', compact ('kategori_bhns'));
    }

    public function storeKategoriBhn(Request $request)
    {
        $request->validate([
            'kategori' => 'required|unique:kategori_bhns,kategori',
        ]);

        // Daftar kategori yang tidak boleh diinput
        $restrictedCategories = ['reptil', 'babi', 'serangga'];

        // Periksa apakah kategori yang diinput termasuk dalam daftar kategori terlarang
        if (in_array(strtolower($request->input('kategori')), $restrictedCategories)) {
            return redirect()->route('supervisor.tambah-kategori-bhn')
                ->withErrors(['kategori' => 'Kategori ini tidak boleh ditambahkan.'])
                ->withInput();
        }

        $existingData = KategoriBhn::where('kategori', $request->input('kategori'))->first();
        if ($existingData) {
            return redirect()->route('supervisor.tambah-kategori-bhn')
                ->withErrors(['kategori' => 'Data kategori sudah ada.'])
                ->withInput();
        }

        KategoriBhn::create($request->all());
        return redirect()->route('supervisor.kategori-bhn')->with('success', 'Data Berhasil Ditambahkan');
    }


    public function destroyKategoriBhn($id){
        $kategori_bhns = KategoriBhn::findOrFail($id);

        $kategori_bhns->delete();
        return redirect()->route('supervisor.kategori-bhn')->with('success', 'Data Berhasil Dihapus');

    }
}
