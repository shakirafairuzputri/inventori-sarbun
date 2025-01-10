<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\PersediaanBahan;
use App\Models\ProduksiBahan;
use App\Models\Bahan;
use App\Models\ReturBahan;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class ReportDailyBahan extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'report:daily-bahan';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate daily report for persediaan bahan';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $today = Carbon::today()->toDateString();
        $yesterday = Carbon::yesterday()->toDateString();

        $this->info('Tanggal Hari Ini: ' . $today);
        $this->info('Tanggal Kemarin: ' . $yesterday);

        $persediaanBahan = PersediaanBahan::with('produksi.pembelian.bahan.kategori')
            ->where('tanggal', $today)
            ->get();

        $this->info('Data Persediaan Hari Ini: ' . json_encode($persediaanBahan));

        foreach ($persediaanBahan as $data) {
            $bahanId = $data->produksi->pembelian->bahan->id;

            // Filter data retur hanya untuk tanggal yang sama
            $returBahan = ReturBahan::where('bahan_id', $bahanId)
                ->whereDate('tanggal', $data->tanggal) // Tambahkan filter tanggal
                ->select(DB::raw('SUM(retur_baik) as retur_baik'), DB::raw('SUM(retur_rusak) as retur_rusak'))
                ->first();

            $returBaik = $returBahan->retur_baik ?? 0;
            $returRusak = $returBahan->retur_rusak ?? 0;

            $yesterdayData = PersediaanBahan::where('tanggal', $yesterday)
                ->whereHas('produksi.pembelian.bahan', function ($query) use ($bahanId) {
                    $query->where('id', $bahanId);
                })
                ->first();

            $this->info('Data Hari Kemarin untuk Bahan ID: ' . $bahanId . ' - ' . json_encode($yesterdayData));

            $tanggalProduksi = $data->produksi->tanggal ?? $today;
            $produksiData = ProduksiBahan::where('tanggal', $tanggalProduksi)
                ->whereHas('pembelian.bahan', function ($query) use ($bahanId) {
                    $query->where('id', $bahanId);
                })
                ->with('pembelian.bahan.kategori')
                ->first();

            $this->info('Data Produksi: ' . json_encode($produksiData));

            if (!$produksiData || !$produksiData->pembelian) {
                $this->info('Tidak ada data produksi atau pembelian untuk bahan ID: ' . $bahanId);
            }

            if ($data->stok_awal !== null) {
                $stokAwal = $data->stok_awal;
                $this->info('Data stok awal: ' . $stokAwal);
            } else {
                if (!$yesterdayData) {
                    $stokAwal = $produksiData->pembelian->bahan->stok;
                    $this->info('Not Yesterday Data: ' . $stokAwal);
                } else {
                    if ($yesterdayData->stok_akhir == $produksiData->pembelian->bahan->stok) {
                        $stokAwal = $yesterdayData->stok_akhir;
                        $this->info('Yesterday Data: ');
                    } else {
                        $stokAwal = $produksiData->pembelian->bahan->stok;
                        $this->info('Bahan stok ambil');
                    }
                }
            }

            $this->info('Stok Awal untuk Bahan ID: ' . $bahanId . ' adalah: ' . $stokAwal);

            $stok_siang = $stokAwal
                - ($returBaik)
                - ($returRusak)
                + ($produksiData->pembelian->pembelian ?? 0)
                - ($produksiData->produksi_baik ?? 0)
                - ($produksiData->produksi_paket ?? 0)
                - ($produksiData->produksi_rusak ?? 0);

            $this->info('Stok Siang untuk Bahan ID: ' . $bahanId . ' adalah: ' . $stok_siang);

            $cekFisik = $data->cek_fisik;
            $selisih = $cekFisik ? $stok_siang - $cekFisik : 0;
            $tambahanSore = $produksiData->pembelian->tambahan_sore ?? 0;
            $stok_akhir = $stok_siang - $selisih + $tambahanSore;

            $this->info('Stok Akhir untuk Bahan ID: ' . $bahanId . ' adalah: ' . $stok_akhir);

            PersediaanBahan::updateOrCreate(
                [
                    'tanggal' => $tanggalProduksi,
                    'produksi_id' => $data->produksi_id,
                ],
                [
                    'stok_awal' => $stokAwal,
                    'stok_siang' => $stok_siang,
                    'stok_akhir' => $stok_akhir,
                    'cek_fisik' => $cekFisik,
                    'selisih' => $selisih,
                ]
            );

            $this->info('Data Persediaan Bahan Tersimpan untuk Bahan ID: ' . $bahanId);

            if (isset($data->produksi->pembelian->bahan)) {
                $bahan = $data->produksi->pembelian->bahan;
                $bahan->stok = $stok_akhir;
                $bahan->save();

                $this->info('Stok pada tabel `bahan` untuk Bahan ID: ' . $bahan->id . ' telah diperbarui menjadi: ' . $stok_akhir);
            } else {
                $this->info('Data bahan tidak ditemukan untuk update stok.');
            }
        }
    }
}
