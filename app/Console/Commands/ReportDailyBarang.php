<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\PersediaanBarang;
use App\Models\Barang;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class ReportDailyBarang extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'report:daily';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate daily report for barang masuk and barang keluar';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $today = Carbon::today()->toDateString();
        $this->info("Tanggal Hari Ini: $today");
        $yesterday = Carbon::yesterday()->toDateString();
        $this->info("Tanggal Hari Kemarin: $yesterday"); 

        $persediaanBarang = PersediaanBarang::with('barang')->where('tanggal', $today)->get();

        foreach ($persediaanBarang as $data) {
            $yesterdayData = PersediaanBarang::where('tanggal', $yesterday)
                ->where('barang_id', $data->barang_id)
                ->first();
            $this->info("Data hari kemarin: $yesterdayData");

            if ($data->stok_awal === null) {
                if ($yesterdayData) {
                    $stokAwal = $yesterdayData->sisa;
                } else {
                    $stokAwal = $data->barang->stok_brg;
                }
            } else {
                $stokAwal = $data->stok_awal;
            }
            $sisa = $stokAwal + ($data->tambah ?? 0) - ($data->kurang ?? 0);

            $persediaanBarang = PersediaanBarang::updateOrCreate(
                [
                    'tanggal' => $today,
                    'barang_id' => $data->barang_id,
                ],
                [
                    'stok_awal' => $stokAwal, 
                    'sisa' => $sisa,
                ]
            );
            if ($data->tambah > 0 || $data->kurang > 0) {
                $data->barang->update(['stok_brg' => $sisa]);
            }
        }
    }
}
