<?
namespace App\Listeners;

use App\Events\ProduksiUpdated;
use App\Models\PersediaanBahan;

class UpdatePersediaanOnProduksiUpdated
{
    public function handle(ProduksiUpdated $event)
    {
        // Mengakses data produksi melalui $event->produksi
        $produksi = $event->produksi;
        
        // Ambil bahan_id melalui relasi dengan pembelian
        $bahanId = $produksi->pembelian->bahan_id;

        // Logika untuk memperbarui persediaan
        $persediaan = PersediaanBahan::where('tanggal', $produksi->tanggal)
            ->whereHas('produksi', function ($query) use ($bahanId) {
                $query->where('bahan_id', $bahanId);
            })
            ->first();

        if ($persediaan) {
            $persediaan->update([
                'produksi_baik' => $produksi->produksi_baik,
                'produksi_paket' => $produksi->produksi_paket,
                'produksi_rusak' => $produksi->produksi_rusak,
            ]);
        }
    }
}
