<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RequestController;
use App\Http\Controllers\Supervisor\BahanController;
use App\Http\Controllers\Supervisor\BarangController;
use App\Http\Controllers\Supervisor\KategoriBahanController;
use App\Http\Controllers\Supervisor\KategoriBarangController;
use App\Http\Controllers\Supervisor\SatuanController;
use App\Http\Controllers\Supervisor\PersediaanBahansController;
use App\Http\Controllers\Supervisor\PersediaanBarangsController;
use App\Http\Controllers\Supervisor\LaporKesalahanController;
use App\Http\Controllers\Pegawai\PersediaanBahanController;
use App\Http\Controllers\Pegawai\ReturBahanController;
use App\Http\Controllers\Pegawai\PembelianBahanController;
use App\Http\Controllers\Pegawai\ProduksiBahanController;
use App\Http\Controllers\Pegawai\PersediaanBarangController;
use App\Http\Controllers\Admin\AkunController;
use App\Http\Controllers\LaporanBarangController;
use App\Http\Controllers\LaporanBahanController;
use App\Models\ReturBahan;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;




/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
// Route::get('/',[HomeController::class, 'index']);
// Route::get('/', [HomeController::class, 'viewBeranda'])->name('beranda');

//AUTH
Route::get('/', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/loginproses', [AuthController::class, 'loginproses'])->name('loginproses');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


// Admin routes
Route::group(['prefix' => 'admin', 'middleware' => ['auth', 'role:admin'], 'as' => 'admin.'], function () {
    Route::get('/dashboard', [DashboardController::class, 'dashboardAdmin'])->name('dashboardo');
    // Route::get('/kelola-user', [HomeController::class, 'kelolauser'])->name('kelola-user');
    Route::get('/tambah-user', [HomeController::class, 'tambahuser'])->name('tambah-user');
    // routes/web.php
    Route::get('/kelola-user', [AkunController::class, 'viewAkun'])->name('kelola-user');
    Route::post('/tambah-user', [AkunController::class, 'storeAkun'])->name('store-user');
    Route::get('/user/{id}/edit', [AKunController::class, 'editAkun'])->name('edit-user');
    Route::put('/user/{id}', [AkunController::class, 'updateAkun'])->name('update-user');

});

// Supervisor routes
Route::group(['prefix' => 'supervisor', 'middleware' => ['auth', 'role:supervisor'], 'as' => 'supervisor.'], function () {
    Route::get('/dashboard', [DashboardController::class, 'dashboardSupervisor'])->name('dashboard');
    Route::get('/inventori-retur', [PersediaanBahansController::class, 'viewRetur'])->name('inventori-retur');
    // routes/web.php
    Route::post('/retur/{id}/kembalikan', [PersediaanBahansController::class, 'kembalikanRetur'])->name('retur-kembalikan');

    Route::get('/inventori-beli', [PersediaanBahansController::class, 'viewPembelian'])->name('inventori-beli');
    Route::get('/inventori-produksi', [PersediaanBahansController::class, 'viewProduksi'])->name('inventori-produksi');

    Route::get('/inventori-brgm', [PersediaanBarangsController::class, 'viewBrgMasuk'])->name('inventori-brgm');
    Route::get('/inventori-brgk', [PersediaanBarangsController::class, 'viewBrgKeluar'])->name('inventori-brgk');

    Route::get('/kategori-bhn', [HomeController::class, 'kategoribhn'])->name('kategori-bhn');
    Route::get('/kategori-brg', [HomeController::class, 'kategoribrg'])->name('kategori-brg');
    Route::get('/unit', [HomeController::class, 'unit'])->name('unit');
    Route::get('/daftar-bhn', [HomeController::class, 'daftarbhn'])->name('daftar-bhn');
    Route::get('/daftar-brg', [HomeController::class, 'daftarbrg'])->name('daftar-brg');


    Route::get('/tambah-kategori-bhn', [HomeController::class, 'tambahkategoribhn'])->name('tambah-kategori-bhn');
    Route::get('/tambah-kategori-brg', [HomeController::class, 'tambahkategoribrg'])->name('tambah-kategori-brg');
    Route::get('/tambah-unit', [HomeController::class, 'tambahunit'])->name('tambah-unit');
    Route::get('/tambah-daftar-bhn', [HomeController::class, 'tambahdaftarbhn'])->name('tambah-daftar-bhn');
    Route::get('/tambah-daftar-brg', [HomeController::class, 'tambahdaftarbrg'])->name('tambah-daftar-brg');
    Route::get('/edit-daftar-bhn/{id}', [HomeController::class, 'editdaftarbhn'])->name('edit-daftar-bhn');
    Route::get('/edit-daftar-brg/{id}', [HomeController::class, 'editdaftarbrg'])->name('edit-daftar-brg');
    

    Route::get('/lapor', [LaporKesalahanController::class, 'viewLapor'])->name('lapor');
    Route::get('/tambah-lapor', [HomeController::class, 'tambahlapor'])->name('tambah-lapor');
    Route::post('/lapor',[LaporKesalahanController::class, 'storeLapor'])->name('lapor-kesalahan.store');
    Route::get('/lapor-kesalahan/{id}/edit', [LaporKesalahanController::class, 'editLapor'])->name('lapor.edit');
    Route::put('/lapor/{id}', [LaporKesalahanController::class, 'updateLapor'])->name('lapor.update');

    //bahan crud
    // Route::resource('/bahan', BahanController::class);
    Route::post('/bahan', [BahanController::class, 'store'])->name('store-bahan');
    Route::get('/daftar-bhn', [BahanController::class, 'viewBahan'])->name('daftar-bhn');
    Route::get('/bahan/{id}/edit', [BahanController::class, 'editBahan'])->name('edit-bahan');
    Route::put('/bahan/{id}', [BahanController::class, 'updateBahan'])->name('update-bahan');
    Route::delete('/bahan/{id}', [BahanController::class, 'destroyBahan'])->name('destroy-bahan');
    Route::post('/import-bahan', [BahanController::class, 'importExcel'])->name('import-bahan');

    
    //barang crud
    Route::post('/barang', [BarangController::class, 'storeBarang'])->name('store-barang');
    Route::get('/daftar-brg',[BarangController::class, 'viewBarang'])->name('daftar-brg');
    Route::get('/barang/{id}/edit', [BarangController::class, 'editBarang'])->name('edit-barang');
    Route::put('/barang/{id}', [BarangController::class, 'updateBarang'])->name('update-barang');
    Route::delete('/barang/{id}', [BarangController::class, 'destroyBarang'])->name('destroy-barang');
    Route::post('/import-barang', [BarangController::class, 'importExcel'])->name('import-barang');
    
    //kategori bahan crud
    Route::post('/kategori-bahan', [KategoriBahanController::class, 'storeKategoriBhn'])->name('store-kategoribhn');
    Route::get('/kategori-bhn',[KategoriBahanController::class, 'viewKategoriBhn'])->name('kategori-bhn');
    Route::delete('/kategori-bhn/{id}', [KategoriBahanController::class, 'destroyKategoriBhn'])->name('destroy-kategoribhn');

    //kategori barang crud
    Route::post('/kategori-barang', [KategoriBarangController::class, 'storeKategoriBrg'])->name('store-kategoribrg');
    Route::get('/kategori-brg', [KategoriBarangController::class, 'viewKategoriBrg'])->name('kategori-brg');
    Route::delete('/kategori-brg/{id}', [KategoriBarangController::class, 'destroyKategoriBrg'])->name('destroy-kategoribrg');

    //satuan barang crud
    Route::post('/satuan', [SatuanController::class, 'storeSatuan'])->name('store-satuan');
    Route::get('/unit', [SatuanController::class, 'viewSatuan'])->name('unit');
    Route::delete('/satuan/{id}', [SatuanController::class, 'destroySatuan'])->name('destroy-satuan');

    //Laporan Bahan
    Route::get('/laporan-persediaan-bhn', [LaporanBahanController::class,'viewLaporanBhnS' ])->name('laporan-bhn');
    Route::get('/laporan-bhn/download', [LaporanBahanController::class, 'downloadPDFS'])->name('download-laporan-bhn');


    //Laporan Barang
    Route::get('/laporan-persediaan-brg', [LaporanBarangController::class,'viewLaporanBrgS' ])->name('laporan-brg');
    Route::get('/laporan/download', [LaporanBarangController::class, 'downloadPDFS'])->name('download-laporan');

    //Request input
    Route::get('/request-input', [RequestController::class, 'viewRequestS'])->name('request-input');
    // In routes/web.php
    Route::post('/approve-request/{requestId}', [RequestController::class, 'approveRequest'])->name('approve-request');

});

// Pegawai routes
Route::group(['prefix' => 'pegawai', 'middleware' => ['auth', 'role:pegawai'], 'as' => 'pegawai.'], function () {
    Route::get('/dashboard', [DashboardController::class, 'dashboardPegawai'])->name('dashboardp');
    // Route::get('/persediaan-retur', [HomeController::class, 'persediaanretur'])->name('persediaan-retur');
    // Route::get('/persediaan-beli', [HomeController::class, 'persediaanbeli'])->name('persediaan-beli');
    // Route::get('/persediaan-produksi', [HomeController::class, 'persediaanproduksi'])->name('persediaan-produksi');
    
    Route::get('/laporan', [LaporKesalahanController::class, 'viewpegawaiLaporans'])->name('laporan');
    // Route to show the reports assigned to the logged-in employee

    // Route to update the status of the report
    Route::put('/lapor-kesalahan/{id}/selesai', [LaporKesalahanController::class, 'updateStatus'])->name('lapor-kesalahan.update-status');


    Route::get('/tambah-persediaan-retur', [HomeController::class, 'tambahpersediaanretur'])->name('tambah-persediaan-retur');
    // Route::get('/edit-persediaan-retur/{id}', [HomeController::class, 'editpersediaanretur'])->name('edit-persediaan-retur');
    Route::get('/tambah-persediaan-beli', [HomeController::class, 'tambahpersediaanbeli'])->name('tambah-persediaan-beli');
    Route::get('/tambah-persediaan-produksi', [HomeController::class, 'tambahpersediaanproduksi'])->name('tambah-persediaan-produksi');
    Route::get('/tambah-persediaan-brgm', [HomeController::class, 'tambahpersediaanbrgm'])->name('tambah-persediaan-brgm');
    Route::get('/tambah-persediaan-brgk', [HomeController::class, 'tambahpersediaanbrgk'])->name('tambah-persediaan-brgk');

    
    //Retur Bahan CRUD
    Route::post('/persediaan-retur', [ReturBahanController::class, 'storeRetur'])->name(('persediaan-retur.store'));
    Route::get('/persediaan-retur', [ReturBahanController::class, 'viewRetur'])->name(('persediaan-retur'));
    Route::get('persediaan-retur/{id}/edit', [ReturBahanController::class, 'editRetur'])->name('persediaan-retur.edit');
    Route::put('persediaan-retur/{id}', [ReturBahanController::class, 'updateRetur'])->name('persediaan-retur.update');
    Route::delete('/persediaan-retur/{id}', [ReturBahanController::class, 'destroyRetur'])->name('persediaan-retur.destroy');

    // //Pembelian Bahan CRUD
    Route::post('/persediaan-beli', [PembelianBahanController::class, 'storePembelian'])->name(('store-persediaan-beli'));
    Route::get('/persediaan-beli', [PembelianBahanController::class, 'viewPembelian'])->name(('persediaan-beli'));
    Route::get('persediaan-beli/{id}/edit', [PembelianBahanController::class, 'editPembelian'])->name('persediaan-beli.edit');
    Route::put('persediaan-beli/{id}', [PembelianBahanController::class, 'updatePembelian'])->name('persediaan-beli.update');
    Route::delete('/persediaan-beli/{id}', [PembelianBahanController::class, 'destroyPembelian'])->name('persediaan-beli.destroy');

    // //Produksi Bahan CRUD
    Route::post('/persediaan-produksi', [ProduksiBahanController::class, 'storeProduksi'])->name(('store-persediaan-produksi'));
    Route::get('/persediaan-produksi', [ProduksiBahanController::class, 'viewProduksi'])->name(('persediaan-produksi'));
    Route::get('persediaan-produksi/{id}/edit', [ProduksiBahanController::class, 'editProduksi'])->name('persediaan-produksi.edit');
    Route::put('persediaan-produksi/{id}', [ProduksiBahanController::class, 'updateProduksi'])->name('persediaan-produksi.update');
    Route::delete('/persediaan-produksi/{id}',  [ProduksiBahanController::class, 'destroyProduksi'])->name('persediaan-produksi.destroy');

    //Barang Masuk
    Route::post('/persediaan-brgm', [PersediaanBarangController::class, 'storeBrgMasuk'])->name('store-persediaan-brgm');
    Route::get('/persediaan-brgm', [PersediaanBarangController::class, 'viewBrgMasuk'])->name('persediaan-brgm');
    Route::get('persediaan-brgm/{id}/edit', [PersediaanBarangController::class, 'editBrgMasuk'])->name('persediaan-brgm.edit');
    Route::put('persediaan-brgm/{id}', [PersediaanBarangController::class, 'updateBrgMasuk'])->name('persediaan-brgm.update');
    Route::delete('/persediaan-brgm/{id}', action: [PersediaanBarangController::class, 'destroyBrgMasuk'])->name('destroy-brgm');


    //Barang Keluar
    Route::post('/persediaan-brgk', [PersediaanBarangController::class, 'storeBrgKeluar'])->name('store-persediaan-brgk');
    Route::get('/persediaan-brgk', [PersediaanBarangController::class, 'viewBrgKeluar'])->name('persediaan-brgk');
    Route::get('persediaan-brgk/{id}/edit', [PersediaanBarangController::class, 'editBrgKeluar'])->name('persediaan-brgk.edit');
    Route::put('persediaan-brgk/{id}', [PersediaanBarangController::class, 'updateBrgKeluar'])->name('persediaan-brgk.update');
    Route::delete('/persediaan-brgk/{id}', action: [PersediaanBarangController::class, 'destroyBrgKeluar'])->name('destroy-brgk');

    //laporan barang
    Route::get('/laporan-persediaan-brg', [LaporanBarangController::class,'viewLaporanBrg' ])->name('laporan-brg');
    Route::get('/laporan/download', [LaporanBarangController::class, 'downloadPDF'])->name('download-laporan');

    //laporan Bahan
    Route::get('/laporan-persediaan-bhn', [LaporanBahanController::class,'viewLaporanBhn' ])->name('laporan-bhn');
    Route::put('/persediaan/{id}',[LaporanBahanController::class, 'store'])->name('persediaan-store');
    Route::get('/laporan-bhn/download', [LaporanBahanController::class, 'downloadPDF'])->name('download-laporan-bhn');

    //Request input
    Route::get('/request-input', [RequestController::class, 'viewRequest'])->name('request-input');
    Route::get('/tambah-request-input', [RequestController::class, 'tambahRequest'])->name('request-input.tambah');
    Route::post('/tambah-request-input',[RequestController::class, 'storeRequest'])->name('request-input.store');

});
Route::get('/run-scheduler', function () {
    Artisan::call('schedule:run');
    return 'Scheduler has been run!';
});






