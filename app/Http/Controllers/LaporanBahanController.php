<?php

namespace App\Http\Controllers;


use App\Models\KategoriBhn;
use Illuminate\Http\Request;
use App\Models\PersediaanBahan;
use App\Models\ProduksiBahan;
use App\Models\PembelianBahan;
use App\Models\ReturBahan;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use Illuminate\Support\Facades\DB;

use function PHPUnit\Framework\callback;

class LaporanBahanController extends Controller
{
    public function viewLaporanBhn(Request $request)
    {
        $tanggal = $request->input('tanggal', Carbon::today()->toDateString());
        $kategoriId = $request->input('kategori_id'); 

        $query = PersediaanBahan::with('produksi.pembelian.bahan.kategori')
            ->where('tanggal', $tanggal);

        if ($kategoriId) {
            $query->whereHas('produksi.pembelian.bahan.kategori', function ($query) use ($kategoriId) {
                $query->where('id', $kategoriId);
            });
        }

        $persediaanBahan = $query->get();

        $reportData = [];

        foreach ($persediaanBahan as $data) {
            $bahanId = $data->produksi->pembelian->bahan->id;
            $returBahan = ReturBahan::where('bahan_id', $bahanId)
                ->whereDate('tanggal', $data->tanggal) // Tambahkan filter tanggal
                ->select(DB::raw('SUM(retur_baik) as retur_baik'), DB::raw('SUM(retur_rusak) as retur_rusak'))
                ->groupBy('bahan_id')
                ->first();

            $reportData[] = [
                'id' => $data->id,
                'tanggal' => $data->tanggal,
                'nama_bhn' => optional($data->produksi->pembelian->bahan)->nama ?? 'Tidak Diketahui',
                'kategori' => optional($data->produksi->pembelian->bahan->kategori)->kategori ?? 'Tidak Diketahui',
                'satuan' => optional($data->produksi->pembelian->bahan)->satuan ?? 'Tidak Diketahui',
                'stok_awal' => $data->stok_awal ?? 0,
                'retur_baik' => $returBahan->retur_baik ?? 0,
                'retur_rusak' => $returBahan->retur_rusak ?? 0,
                'pembelian' => optional($data->produksi->pembelian)->pembelian ?? 0,
                'produksi_baik' => optional($data->produksi)->produksi_baik ?? 0,
                'produksi_paket' => optional($data->produksi)->produksi_paket ?? 0,
                'produksi_rusak' => optional($data->produksi)->produksi_rusak ?? 0,
                'stok_siang' => $data->stok_siang ?? 0,
                'cek_fisik' => $data->cek_fisik ?? 0,
                'selisih' => $data->selisih*-1 ?? 0,
                'tambahan_sore' => optional($data->produksi->pembelian)->tambahan_sore ?? 0,
                'stok_akhir' => $data->stok_akhir ?? 0,
                'keterangan' => $data->keterangan ?? '',
            ];
        }

        $kategoris = KategoriBhn::all();

        return view('pegawai.laporan-bhn', compact('reportData', 'tanggal', 'kategoris'));
    }

public function viewLaporanBhnS(Request $request)
{
    $tanggalMulai = $request->input('tanggal_mulai', Carbon::today()->toDateString());
    $tanggalSelesai = $request->input('tanggal_selesai', Carbon::today()->toDateString());
    $kategoriId = $request->input('kategori_id'); 
    
    $kategoriOptions = KategoriBhn::all();
    $persediaanBahan = PersediaanBahan::with('produksi.pembelian.bahan.kategori')
        ->whereBetween('tanggal', [$tanggalMulai, $tanggalSelesai])
        ->when($kategoriId, function ($query) use ($kategoriId) {
            $query->whereHas('produksi.pembelian.bahan.kategori', function ($q) use ($kategoriId) {
                $q->where('id', $kategoriId);
            });
        })
        ->get();
    
    $reportData = [];

    foreach ($persediaanBahan as $data) {
        $bahanId = $data->produksi->pembelian->bahan->id;
        $returBahan = ReturBahan::where('bahan_id', $bahanId)
            ->whereDate('tanggal', $data->tanggal) // Tambahkan filter tanggal
            ->select(DB::raw('SUM(retur_baik) as retur_baik'), DB::raw('SUM(retur_rusak) as retur_rusak'))
            ->groupBy('bahan_id')
            ->first();

        $reportData[] = [
            'id' => $data->id,
            'tanggal' => $data->tanggal,
            'nama_bhn' => optional($data->produksi->pembelian->bahan)->nama ?? 'Tidak Diketahui',
            'kategori' => optional($data->produksi->pembelian->bahan->kategori)->kategori ?? 'Tidak Diketahui',
            'satuan' => optional($data->produksi->pembelian->bahan)->satuan ?? 'Tidak Diketahui',
            'stok_awal' => $data->stok_awal ?? 0,
            'retur_baik' => $returBahan->retur_baik ?? 0,
            'retur_rusak' => $returBahan->retur_rusak ?? 0,
            'pembelian' => optional($data->produksi->pembelian)->pembelian ?? 0,
            'produksi_baik' => optional($data->produksi)->produksi_baik ?? 0,
            'produksi_paket' => optional($data->produksi)->produksi_paket ?? 0,
            'produksi_rusak' => optional($data->produksi)->produksi_rusak ?? 0,
            'stok_siang' => $data->stok_siang ?? 0,
            'cek_fisik' => $data->cek_fisik ?? 0,
            'selisih' => $data->selisih*-1 ?? 0,
            'tambahan_sore' => optional($data->produksi->pembelian)->tambahan_sore ?? 0,
            'stok_akhir' => $data->stok_akhir ?? 0,
            'keterangan' => $data->keterangan ?? '',
        ];
    }
    
    return view('supervisor.laporan-bhn', compact('reportData', 'tanggalMulai', 'tanggalSelesai', 'kategoriId', 'kategoriOptions'));
}

    

    public function store(Request $request, $id)
    {
        // Validasi input
        $request->validate([
            'cek_fisik' => 'nullable|numeric',
            'keterangan' => 'nullable|string|max:255',
        ]);

        // Cari data yang akan diperbarui
        $reportData = PersediaanBahan::findOrFail($id);

        // Buat array untuk menampung data yang akan diperbarui
        $updateData = [];

        // Periksa apakah cek_fisik diisi, jika iya tambahkan ke array
        if ($request->filled('cek_fisik')) {
            $updateData['cek_fisik'] = $request->cek_fisik;
        }

        // Periksa apakah keterangan diisi, jika iya tambahkan ke array
        if ($request->filled('keterangan')) {
            $updateData['keterangan'] = $request->keterangan;
        }

        // Jika ada data yang perlu diperbarui, lakukan update
        if (!empty($updateData)) {
            $reportData->update($updateData);
        }

        return redirect()->back()->with('success', 'Data berhasil disimpan.');
    }


    public function downloadPDF(Request $request)
    {
        $tanggal = $request->input('tanggal');
        $kategori_id = $request->input('kategori_id');

        if (!$tanggal) {
            return redirect()->back()->withErrors(['Tanggal tidak dipilih.']);
        }

        $reportData = $this->getReportDataBahan($tanggal, $kategori_id);

        $pdf = FacadePdf::loadView('pdf-laporan-bhn', compact('reportData', 'tanggal'))
                        ->setPaper('a4', 'landscape');

        $formattedDate = \Carbon\Carbon::parse($tanggal)->format('d-m-Y');
        return $pdf->download('laporan_persediaan_bahan_' . $formattedDate . '.pdf');
    }

    private function getReportDataBahan($tanggal, $kategoriId = null)
    {
        // Query awal untuk mengambil persediaan bahan berdasarkan tanggal
        $query = PersediaanBahan::with('produksi.pembelian.bahan.kategori')
            ->where('tanggal', $tanggal);

        // Jika kategori_id ada, filter berdasarkan kategori
        if ($kategoriId) {
            $query->whereHas('produksi.pembelian.bahan.kategori', function ($query) use ($kategoriId) {
                $query->where('id', $kategoriId);
            });
        }

        $persediaanBahan = $query->get();

        $reportData = [];

        foreach ($persediaanBahan as $data) {
            $bahanId = $data->produksi->pembelian->bahan->id;
            $returBahan = ReturBahan::where('bahan_id', $bahanId)
                ->whereDate('tanggal', $data->tanggal) // Cocokkan tanggal dengan entri laporan
                ->select(
                    DB::raw('DATE(tanggal) as tanggal'),
                    DB::raw('SUM(retur_baik) as retur_baik'),
                    DB::raw('SUM(retur_rusak) as retur_rusak')
                )
                ->groupBy(DB::raw('bahan_id'), DB::raw('DATE(tanggal)'))
                ->first();

            if ($returBahan) {
                $returBaik = $returBahan->retur_baik;
                $returRusak = $returBahan->retur_rusak;
            } else {
                $returBaik = 0;
                $returRusak = 0;
            }


            // Mengumpulkan data untuk laporan
            $reportData[] = [
                'tanggal' => $data->tanggal,
                'nama_bahan' => $data->produksi->pembelian->bahan->nama,
                'kategori' => $data->produksi->pembelian->bahan->kategori->kategori,
                'satuan' => $data->produksi->pembelian->bahan->satuan,
                'stok_awal' => $data->stok_awal  ?? 0,
                'retur_baik' => $returBaik ?? 0,
                'retur_rusak' => $returRusak ?? 0,
                'pembelian' => $data->produksi->pembelian->pembelian ?? 0,
                'produksi_baik' => $data->produksi->produksi_baik ?? 0,
                'produksi_paket' => $data->produksi->produksi_paket ?? 0,
                'produksi_rusak' => $data->produksi->produksi_rusak ?? 0,
                'stok_siang' => $data->stok_siang ?? 0,
                'cek_fisik' => $data->cek_fisik ?? 0,
                'selisih' => $data->selisih*-1 ?? 0,
                'tambahan_sore' => $data->produksi->pembelian->tambahan_sore ?? 0,
                'stok_akhir' => $data->stok_akhir ?? 0,
                'keterangan' => $data->keterangan ?? '',
            ];
        }

        return $reportData;
    }

    public function downloadPDFS(Request $request)
    {
        $tanggalMulai = $request->input('tanggalMulai');
        $tanggalSelesai = $request->input('tanggalSelesai');
        $kategori_id = $request->input('kategori_id');

        // Pastikan kedua tanggal ada
        if (!$tanggalMulai || !$tanggalSelesai) {
            return redirect()->back()->withErrors(['Tanggal tidak dipilih.']);
        }

        // Ambil data berdasarkan rentang tanggal
        $reportDatas = $this->getReportDataBahanS($tanggalMulai, $tanggalSelesai , $kategori_id);
        

        $pdf = FacadePdf::loadView('pdf-laporan-bhns', compact('reportDatas', 'tanggalMulai', 'tanggalSelesai'))
                        ->setPaper('a4', 'landscape');

        // Format tanggal untuk nama file
        $formattedStartDate = \Carbon\Carbon::parse($tanggalMulai)->format('d-m-Y');
        $formattedEndDate = \Carbon\Carbon::parse($tanggalSelesai)->format('d-m-Y');
        
        // Ganti nama file dengan rentang tanggal
        return $pdf->download('laporan_persediaan_bahan_' . $formattedStartDate . '_s.d._' . $formattedEndDate . '.pdf')
           ->header('Content-Disposition', 'attachment; filename="laporan_persediaan_bahan_' . $formattedStartDate . '_s.d._' . $formattedEndDate . '.pdf"');
    }
    private function getReportDataBahanS($tanggalMulai, $tanggalSelesai, $kategoriId = null)
    {
        $persediaanBahan = PersediaanBahan::with('produksi.pembelian.bahan.kategori')
        ->whereBetween('tanggal', [$tanggalMulai, $tanggalSelesai])
        ->when($kategoriId, function ($query) use ($kategoriId) {
            // Menambahkan filter dengan kategori_id
            $query->whereHas('produksi.pembelian.bahan.kategori', function ($q) use ($kategoriId) {
                $q->where('id', $kategoriId); // Filter berdasarkan kategori_id
            });
        })
        ->get();

        $reportDatas = [];

        foreach ($persediaanBahan as $data) {
            $bahanId = $data->produksi->pembelian->bahan->id;
            $returBahan = ReturBahan::where('bahan_id', $bahanId)
                ->whereDate('tanggal', $data->tanggal)
                ->select(
                    DB::raw('DATE(tanggal) as tanggal'),
                    DB::raw('SUM(retur_baik) as retur_baik'),
                    DB::raw('SUM(retur_rusak) as retur_rusak')
                )
                ->groupBy(DB::raw('bahan_id'), DB::raw('DATE(tanggal)'))
                ->first();

            if ($returBahan) {
                $returBaik = $returBahan->retur_baik;
                $returRusak = $returBahan->retur_rusak;
            } else {
                $returBaik = 0;
                $returRusak = 0;
            }

            $reportDatas[] = [
                'tanggal' => $data->tanggal,
                'nama_bahan' => $data->produksi->pembelian->bahan->nama,
                'kategori' => $data->produksi->pembelian->bahan->kategori->kategori,
                'satuan' => $data->produksi->pembelian->bahan->satuan,
                'stok_awal' => $data->stok_awal ?? 0,
                'retur_baik' => $returBaik ?? 0,
                'retur_rusak' => $returRusak ?? 0,
                'pembelian' => $data->produksi->pembelian->pembelian ?? 0,
                'produksi_baik' => $data->produksi->produksi_baik ?? 0,
                'produksi_paket' => $data->produksi->produksi_paket ?? 0,
                'produksi_rusak' => $data->produksi->produksi_rusak ?? 0,
                'stok_siang' => $data->stok_siang ?? 0,
                'cek_fisik' => $data->cek_fisik ?? 0,
                'selisih' => $data->selisih*-1 ?? 0,
                'tambahan_sore' => $data->produksi->pembelian->tambahan_sore ?? 0,
                'stok_akhir' => $data->stok_akhir ?? 0,
                'keterangan' => $data->keterangan ?? '',
            ];
        }

        return $reportDatas;
    }

}
