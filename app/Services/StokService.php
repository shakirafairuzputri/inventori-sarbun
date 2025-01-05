<?php

namespace App\Services;

use App\Models\PersediaanBarang;
use App\Models\Barang;
use Illuminate\Support\Facades\Session;

class StokService
{
    public function getStokAwal($barang_id)
    {
        $session_key = "stok_awal_$barang_id";
        
        if (!Session::has($session_key)) {
            $persediaan = PersediaanBarang::where('barang_id', $barang_id)->first();
            $stok_awal = $persediaan ? $persediaan->stok_awal : 0;
            Session::put($session_key, $stok_awal);
        }

        return Session::get($session_key);
    }

    public function getStokBrg($barang_id)
    {
        $barang = Barang::find($barang_id);
        return $barang ? $barang->stok_brg : 0;
    }
}
