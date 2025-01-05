<?php

namespace App\Http\Controllers;

use App\Models\KategoriBhn;
use App\Models\KategoriBrg;
use App\Models\Satuan;
use App\Models\Bahan;
use App\Models\Barang;
use App\Models\User;
use App\Models\PersediaanBahan;
use Illuminate\Http\Request;

class HomeController extends Controller
{
 
    public function viewBeranda(){
        return view('beranda');
    }
    // public function stok(){
    //     return view('supervisor.stok');
    // }
    public function viewLaporanBrg(){
        return view ('pdf-laporan-brgs');
    }
    public function kategoribhn(){
        return view('supervisor.kategori-bhn');
    }
    public function kategoribrg(){
        return view('supervisor.kategori-brg');
    }
    public function unit(){
        return view('supervisor.unit');
    }
    public function daftarbhn(){
        return view('supervisor.daftar-bhn');
    }
    public function daftarbrg(){
        return view('supervisor.daftar-brg');
    }
    public function tambahkategoribhn(){
        return view('supervisor.tambah-kategori-bhn');
    }
    public function tambahkategoribrg(){
        return view('supervisor.tambah-kategori-brg');
    }
    public function tambahunit(){
        return view('supervisor.tambah-unit');
    }
    public function tambahdaftarbhn(Request $request)
    {
        $nama = $request->get('nama');
        $kategori_bhns = KategoriBhn::all();
        return view('supervisor.tambah-daftar-bhn', compact('nama', 'kategori_bhns'));
    }
    
    public function editdaftarbhn($id)
    {
        $bahans = Bahan::findOrFail($id);
        $kategori_bhns = KategoriBhn::all();
        return view('supervisor.edit-daftar-bhn', compact('kategori_bhns', 'bahans'));
    }
    
    public function tambahdaftarbrg(Request $request){
        $nama = $request->get('nama');
        $kategori_brgs = KategoriBrg::all();
        $satuans = Satuan::all();
        return view('supervisor.tambah-daftar-brg', compact('kategori_brgs', 'satuans','nama'));
    }
    public function editdaftarbrg($id){

        $barangs = Barang::findOrFail($id);
        $kategori_brgs = KategoriBrg::all();
        $satuans = Satuan::all();
        return view('supervisor.edit-daftar-brg', compact('kategori_brgs', 'satuans', 'barangs'));
    }
    public function tambahlapor(){
        $users = User::where('role', 'pegawai')->get();
        return view('supervisor.tambah-lapor', compact('users'));
    }

    public function dashboardo(){
        return view('admin.dashboardo');
    }
    public function kelolauser(){
        return view('admin.kelola-user');
    }
    public function tambahuser(){
        return view('admin.tambah-user');
    }
    public function dashboardp(){
        return view('pegawai.dashboardp');
    }
    public function persediaanretur(){
        
        return view('pegawai.persediaan-retur');
    }
    public function persediaanbeli(){
        return view('pegawai.persediaan-beli');
    }
    public function persediaanproduksi(){
        return view('pegawai.persediaan-produksi');
    }
    public function persediaanbrgm(){
        return view('pegawai.persediaan-brgm');
    }
    public function persediaanbrgk(){
        return view('pegawai.persediaan-brgk');
    }
    public function laporank(){
        return view('pegawai.laporan');
    }
    public function tambahpersediaanretur(){
        $bahans = Bahan::all();
        $kategori_bhns = KategoriBhn::all();
        return view('pegawai.tambah-persediaan-retur', compact('kategori_bhns', 'bahans'));
    }
    // public function editpersediaanretur($id){
    //     $persediaan_bahans = PersediaanBahan::with('bahan')->findOrFail($id);
    //     $bahans = Bahan::all();
    //     $kategori_bhns = KategoriBhn::all();
    //     return view('pegawai.edit-persediaan-retur', compact('persediaan_bahans', 'kategori_bhns', 'bahans'));
    // }
    public function tambahpersediaanbeli(){
        $bahans = Bahan::all();
        $kategori_bhns = KategoriBhn::all();
        return view('pegawai.tambah-persediaan-beli', compact('kategori_bhns', 'bahans'));
    }
    public function tambahpersediaanproduksi(){
        $bahans = Bahan::all();
        $kategori_bhns = KategoriBhn::all();
        return view('pegawai.tambah-persediaan-produksi', compact('kategori_bhns', 'bahans'));
    }
    public function tambahpersediaanbrgm(){
        $barangs = Barang::all();
        return view('pegawai.tambah-persediaan-brgm', compact('barangs'));
    }
    public function tambahpersediaanbrgk(){
        $barangs = Barang::all();
        return view('pegawai.tambah-persediaan-brgk', compact('barangs'));
    }
}