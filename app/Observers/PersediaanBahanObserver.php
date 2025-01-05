<?php

namespace App\Observers;

use App\Models\PersediaanBahan;
use App\Models\Bahan;
use App\Models\ReturBahan;
use App\Models\ProduksiBahan;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class PersediaanBahanObserver
{
    public function created(PersediaanBahan $persediaanBahan)
    {
        $this->updated($persediaanBahan);
    }

    public function updated(PersediaanBahan $persediaanBahan)
    {
        $bahanId = $persediaanBahan->produksi->pembelian->bahan->id ?? null;

        if (!$bahanId) {
            return; // Jika bahan tidak ditemukan, keluar
        }

        
        $stokAwal = $persediaanBahan->stok_awal ?? $this->getStokAwal($bahanId, $persediaanBahan->tanggal);

        $retur = ReturBahan::where('bahan_id', $bahanId)
            ->selectRaw('SUM(retur_baik) as retur_baik, SUM(retur_rusak) as retur_rusak')
            ->first();

        $returBaik = $retur->retur_baik ?? 0;
        $returRusak = $retur->retur_rusak ?? 0;

        $produksi = ProduksiBahan::where('tanggal', $persediaanBahan->tanggal)
            ->whereHas('pembelian', fn($query) =>
                $query->whereHas('bahan', fn($q) => $q->where('id', $bahanId))
            )->first();

        Log::info('ProduksiBahan Exists:', [
            'produksi' => ProduksiBahan::where('tanggal', $persediaanBahan->tanggal)
                ->whereHas('pembelian', fn($query) =>
                    $query->whereHas('bahan', fn($q) => $q->where('id', $bahanId))
                )->exists()
        ]);
            
        Log::info('Update Stok:', [
            'stokAwal' => $stokAwal,
            'returBaik' => $returBaik,
            'returRusak' => $returRusak,
            'pembelian' => $produksi->pembelian->pembelian,
            'produksi_baik' => $produksi->produksi_baik,
            'produksi_paket' => $produksi->produksi_paket,
            'produksi_rusak' => $produksi->produksi_rusak,
            'tambahanSore' => $produksi->pembelian->tambahan_sore,
            'cekFisik' => $persediaanBahan->cek_fisik,
        ]);
            
        $stokSiang = $stokAwal 
            - $returBaik
            - $returRusak
            + ($produksi->pembelian->pembelian)
            - ($produksi->produksi_baik)
            - ($produksi->produksi_paket)
            - ($produksi->produksi_rusak);

        $cekFisik = $persediaanBahan->cek_fisik;
        $selisih = $cekFisik ? $stokSiang - $cekFisik : 0;
        $tambahanSore = $produksi->pembelian->tambahan_sore;
        $stokAkhir = $stokSiang - $selisih + $tambahanSore;

        Log::info('Hasil Perhitungan:', [
            'stokSiang' => $stokSiang,
            'stokAkhir' => $stokAkhir,
            'selisih' => $selisih,
        ]);
        
        // Update tabel `persediaan_bahan`
        $persediaanBahan->update([
            'stok_awal' => $stokAwal,
            'stok_siang' => $stokSiang,
            'stok_akhir' => $stokAkhir,
            'selisih' => $selisih,
        ]);

        // Update tabel `bahan`
        $bahan = Bahan::find($bahanId);
        $bahan->stok = $stokAkhir;
        $bahan->save();
    }

    private function getStokAwal($bahanId, $tanggal)
{
    $kemarin = Carbon::parse($tanggal)->subDay()->toDateString();

    // Log sebelum query dijalankan
    Log::info('Query getStokAwal:', [
        'bahanId' => $bahanId,
        'tanggal' => $kemarin,
        'result' => PersediaanBahan::where('tanggal', $kemarin)
            ->whereHas('produksi.pembelian.bahan', fn($q) => $q->where('id', $bahanId))
            ->toSql(),
    ]);

    $dataKemarin = PersediaanBahan::where('tanggal', $kemarin)
        ->whereHas('produksi.pembelian.bahan', fn($q) => $q->where('id', $bahanId))
        ->first();

    return $dataKemarin->stok_akhir ?? Bahan::find($bahanId)->stok ?? 0;
}

}
