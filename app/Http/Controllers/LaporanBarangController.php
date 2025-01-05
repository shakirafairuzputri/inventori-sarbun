<?php

namespace App\Http\Controllers;

use App\Models\KategoriBrg;
use Illuminate\Http\Request;
use App\Models\Barang;
use App\Models\PersediaanBarang;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use PDF;

class LaporanBarangController extends Controller
{
    public function viewLaporanBrg(Request $request)
    {
        $tanggal = $request->input('tanggal', Carbon::today()->toDateString());
        $kelompok = $request->input('kelompok', null);
        $kategori_brg = $request->input('kategori_brg', null);  // Ambil kategori_brg dari request
    
        // Query untuk mengambil data persediaan barang
        $persediaanBarang = PersediaanBarang::with(['barang.kategori_brg', 'barang.satuan_brg'])
            ->where('tanggal', $tanggal)
            ->when($kelompok, function ($query) use ($kelompok) {
                $query->whereHas('barang', function ($q) use ($kelompok) {
                    $q->where('kelompok', $kelompok);
                });
            })
            ->when($kategori_brg, function ($query) use ($kategori_brg) {
                $query->whereHas('barang.kategori_brg', function ($q) use ($kategori_brg) {
                    $q->where('kategori_brg', $kategori_brg);
                });
            })
            ->get();
    
        $reportData = [];
    
        // Loop untuk menyiapkan data laporan
        foreach ($persediaanBarang as $data) {
            $reportData[] = [
                'tanggal' => $data->tanggal,
                'nama_barang' => $data->barang->nama_brg,
                'kelompok' => $data->barang->kelompok,
                'kategori_brg' => $data->barang->kategori_brg->kategori_brg,
                'satuan_brg' => $data->barang->satuan_brg->satuan_brg,
                'stok_awal' => $data->stok_awal ?? 0,
                'tambah' => $data->tambah ?? 0,
                'kurang' => $data->kurang ?? 0,
                'sisa' => $data->sisa ?? 0,
            ];
        }
    
        $kategorisBarang = KategoriBrg::all();
    
        return view('pegawai.laporan-brg', compact('reportData', 'tanggal', 'kelompok', 'kategori_brg', 'kategorisBarang'));
    }
    

    public function viewLaporanBrgS(Request $request) {
        $tanggalMulai = $request->input('tanggal_mulai', Carbon::today()->toDateString());
        $tanggalSelesai = $request->input('tanggal_selesai', Carbon::today()->toDateString());
        $kelompok = $request->input('kelompok', null);
        $kategori_brg = $request->input('kategori_brg', null);

        
        $persediaanBarang = PersediaanBarang::with('barang')
            ->whereBetween('tanggal', [$tanggalMulai, $tanggalSelesai])
            ->when($kelompok, function($query) use ($kelompok) {
                $query->whereHas('barang', function($q) use ($kelompok) {
                    $q->where('kelompok', $kelompok);
                });
            })
            ->when($kategori_brg, function ($query) use ($kategori_brg) {
                $query->whereHas('barang.kategori_brg', function ($q) use ($kategori_brg) {
                    $q->where('kategori_brg', $kategori_brg);
                });
            })
            ->get();
    
        $reportData = [];
    
        foreach ($persediaanBarang as $data) {
            $reportData[] = [
                'tanggal' => $data->tanggal,
                'nama_barang' => $data->barang->nama_brg,
                'kelompok' => $data->barang->kelompok,
                'kategori_brg' => $data->barang->kategori_brg->kategori_brg,
                'satuan_brg' => $data->barang->satuan_brg->satuan_brg,
                'stok_awal' => $data->stok_awal ?? 0,
                'tambah' => $data->tambah ?? 0,
                'kurang' => $data->kurang ?? 0,
                'sisa' => $data->sisa ?? 0, 
            ];
        }
        $kategorisBarang = KategoriBrg::all();
    
        return view('supervisor.laporan-brg', compact('reportData', 'tanggalMulai', 'tanggalSelesai', 'kelompok', 'kategori_brg', 'kategorisBarang'));
    }
    
    public function downloadPDF(Request $request)
    {
        $tanggal = $request->input('tanggal');
        $kelompok = $request->input('kelompok');
        $kategori_brg = $request->input('kategori_brg');

        if (!$tanggal) {
            return redirect()->back()->withErrors(['Tanggal tidak dipilih.']);
        }

        $reportData = $this->getReportData($tanggal, $kelompok, $kategori_brg);

        $pdf = FacadePdf::loadView('pdf-laporan-brg', compact('reportData', 'tanggal', 'kelompok', 'kategori_brg'));

        $formattedDate = \Carbon\Carbon::parse($tanggal)->format('d-m-Y');
        return $pdf->download('laporan_persediaan_barang_' . $formattedDate . '.pdf');
    }

    private function getReportData($tanggal, $kelompok = null, $kategori_brg = null)
    {
        $persediaanBarang = PersediaanBarang::with('barang.kategori_brg', 'barang.satuan_brg')
            ->where('tanggal', $tanggal)
            ->when($kelompok, function($query) use ($kelompok) {
                $query->whereHas('barang', function($q) use ($kelompok) {
                    $q->where('kelompok', $kelompok);
                });
            })
            ->when($kategori_brg, function($query) use ($kategori_brg) {
                $query->whereHas('barang.kategori_brg', function($q) use ($kategori_brg) {
                    $q->where('kategori_brg', $kategori_brg);  // Filter berdasarkan kategori barang
                });
            })
            ->get();

        $reportData = [];

        foreach ($persediaanBarang as $data) {
            $reportData[] = [
                'tanggal' => $data->tanggal,
                'nama_barang' => $data->barang->nama_brg,
                'kelompok' => $data->barang->kelompok,
                'kategori_brg' => $data->barang->kategori_brg->kategori_brg,
                'satuan_brg' => $data->barang->satuan_brg->satuan_brg,
                'stok_awal' => $data->stok_awal ?? 0,
                'tambah' => $data->tambah ?? 0,
                'kurang' => $data->kurang ?? 0,
                'sisa' => $data->sisa ?? 0,
            ];
        }

        return $reportData;
    }


    public function downloadPDFS(Request $request)
    {
        $tanggalMulai = $request->input('tanggal_mulai');
        $tanggalSelesai = $request->input('tanggal_selesai');
        $kelompok = $request->input('kelompok');
        $kategori_brg = $request->input('kategori_brg');

        if (!$tanggalMulai || !$tanggalSelesai) {
            return redirect()->back()->withErrors(['Tanggal tidak dipilih.']);
        }

        if ($tanggalMulai > $tanggalSelesai) {
            return redirect()->back()->withErrors(['Tanggal mulai harus sebelum atau sama dengan tanggal selesai.']);
        }

        $reportData = $this->getReportDataS($tanggalMulai, $tanggalSelesai, $kelompok, $kategori_brg);

        $pdf = FacadePdf::loadView('pdf-laporan-brgs', compact('reportData', 'tanggalMulai', 'tanggalSelesai', 'kelompok'));

        $formattedStartDate = \Carbon\Carbon::parse($tanggalMulai)->format('d-m-Y');
        $formattedEndDate = \Carbon\Carbon::parse($tanggalSelesai)->format('d-m-Y');

        return $pdf->download('laporan_persediaan_barang_' . $formattedStartDate . '_s.d_' . $formattedEndDate . '.pdf');
    }

    private function getReportDataS($tanggalMulai, $tanggalSelesai, $kelompok = null, $kategori_brg = null)
    {
        $persediaanBarang = PersediaanBarang::with('barang.kategori_brg', 'barang.satuan_brg')
            ->whereBetween('tanggal', [$tanggalMulai, $tanggalSelesai])
            ->when($kelompok, function($query) use ($kelompok) {
                $query->whereHas('barang', function($q) use ($kelompok) {
                    $q->where('kelompok', $kelompok);
                });
            })
            ->when($kategori_brg, function($query) use ($kategori_brg) {
                $query->whereHas('barang.kategori_brg', function($q) use ($kategori_brg) {
                    $q->where('kategori_brg', $kategori_brg);  // Filter berdasarkan kategori barang
                });
            })
            ->get();

        $reportData = [];

        foreach ($persediaanBarang as $data) {
            $reportData[] = [
                'tanggal' => $data->tanggal,
                'nama_barang' => $data->barang->nama_brg,
                'kelompok' => $data->barang->kelompok,
                'kategori_brg' => $data->barang->kategori_brg->kategori_brg,
                'satuan_brg' => $data->barang->satuan_brg->satuan_brg,
                'stok_awal' => $data->stok_awal ?? 0,
                'tambah' => $data->tambah ?? 0,
                'kurang' => $data->kurang ?? 0,
                'sisa' => $data->sisa ?? 0,
            ];
        }

        return $reportData;
    }
}
