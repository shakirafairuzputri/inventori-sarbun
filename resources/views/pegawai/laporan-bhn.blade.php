@section('title', 'Laporan Persediaan Bahan')
@extends('layout.sidebarp')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Laporan Persediaan Bahan</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Laporan Persediaan Bahan</li>
    </ol>
    <style>
        @media print {
            @page {
                size: landscape;
                margin: 0.2cm;
            }
            body {
                font-size: 0.8rem;
            }
            .table {
                width: 100%;
                table-layout: fixed;
            }
            table, th, td {
                font-size: 6px;
                padding: 0;
                word-wrap: break-word;
            }
            .breadcrumb, .card-header, .btn, form .input-group, form .text-muted, .dataTables_info, .dataTables_length, .dataTables_filter {
                display: none !important;
            }
            th:last-child, td:last-child {
                display: none;
            }
        }
    </style>

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-table me-1"></i> Tabel Data
        </div>

        <div class="d-flex justify-content-end mt-2 mb-0 mx-3">
            <form action="{{ route('pegawai.laporan-bhn') }}" method="GET" class="d-flex align-items-center">
                <div class="row g-2 align-items-center">
                    <div class="col-auto me-2">
                        <div class="text-muted" style="font-size: 0.85em; margin-bottom: 2px;">Tanggal</div>
                        <div class="input-group me-2">
                            <!-- Ikon kalender dari Font Awesome -->
                            <span class="input-group-text">
                                <i class="fas fa-calendar-alt"></i> <!-- Ini adalah ikon kalender -->
                            </span>
                            <input type="date" class="form-control" name="tanggal" value="{{ $tanggal }}">
                        </div>
                    </div>
                    <div class="col-auto me-2">
                        <div class="text-muted" style="font-size: 0.85em; margin-bottom: 2px;">Kategori</div>
                        <div class="input-group me-2">
                            <select name="kategori_id" class="form-control">
                                <option value="">Pilih Kategori</option>
                                @foreach($kategoris as $kategori)
                                    <option value="{{ $kategori->id }}" {{ request('kategori_id') == $kategori->id ? 'selected' : '' }}>
                                        {{ $kategori->kategori }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-auto">
                        <div class="text-muted" style="font-size: 0.85em; margin-bottom: 2px;">Filter</div>
                        <button type="submit" class="btn btn-primary me-2">
                            <i class="fas fa-filter"></i>
                        </button>
                    </div>
                    <div class="col-auto">
                        <div class="text-muted" style="font-size: 0.85em; margin-bottom: 2px;">Unduh</div>
                        <a href="{{ route('pegawai.download-laporan-bhn', ['tanggal' => $tanggal,'kategori_id' => request('kategori_id') ]) }}" class="btn btn-success me-2" target="_blank">
                            <i class="fas fa-download"></i> 
                        </a>
                    </div>             
                    <div class="col-auto">
                        <div class="text-muted" style="font-size: 0.85em; margin-bottom: 2px;">Cetak</div>
                        <button onclick="window.print()" class="btn btn-secondary">
                            <i class="fas fa-print"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>
        <h5 class="mt-1 text-center small">Laporan Persediaan Bahan ({{ \Carbon\Carbon::parse($tanggal)->format('d-m-Y') }})</h5>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div class="table-responsive">
                <table id="datatablesSimple" class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>Nama Bahan</th>
                            <th>Kategori</th>
                            <th>Satuan</th>
                            <th>Stok Awal</th>
                            <th>Retur Baik</th>
                            <th>Retur Rusak</th>
                            <th>Pembelian</th>
                            <th>Produksi Baik</th>
                            <th>Produksi Paket</th>
                            <th>Produksi Rusak</th>
                            <th>Stok Siang</th>
                            <th>Cek Fisik</th>
                            <th>Selisih</th>
                            <th>Tambahan Sore</th>
                            <th>Stok Akhir</th>
                            <th>Keterangan</th>
                            <th>Aksi</th> <!-- Kolom untuk tombol modal -->
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($reportData as $key => $data)
                            <tr>
                                <td>{{ $key + 1 }}</td> 
                                <td>{{ $data['tanggal'] }}</td>
                                <td>{{ $data['nama_bhn'] }}</td>
                                <td>{{ $data['kategori'] }}</td>
                                <td>{{ $data['satuan'] }}</td>
                                <td>{{ $data['stok_awal'] }}</td>
                                <td>{{ $data['retur_baik'] }}</td>
                                <td>{{ $data['retur_rusak'] }}</td>
                                <td>{{ $data['pembelian'] }}</td>
                                <td>{{ $data['produksi_baik'] }}</td>
                                <td>{{ $data['produksi_paket'] }}</td>
                                <td>{{ $data['produksi_rusak'] }}</td>
                                <td>{{ $data['stok_siang'] }}</td>
                                <td>{{ $data['cek_fisik'] }}</td>
                                <td>{{ $data['selisih'] }}</td>
                                <td>{{ $data['tambahan_sore'] }}</td>
                                <td>{{ $data['stok_akhir'] }}</td>
                                <td>{{ $data['keterangan'] }}</td>
                                <td>
                                    <!-- Tombol untuk membuka modal -->
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#inputModal{{ $data['id'] }}">
                                        Cek Fisik
                                    </button>
                                </td>
                            </tr>

                            <!-- Modal untuk input cek fisik dan keterangan -->
                            <div class="modal fade" id="inputModal{{ $data['id'] }}" tabindex="-1" aria-labelledby="inputModalLabel{{ $data['id'] }}" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="inputModalLabel{{ $data['id'] }}">Input Cek Fisik dan Keterangan</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <form action="{{ route('pegawai.persediaan-store', $data['id']) }}" method="POST">
                                            @csrf
                                            @method('PUT') <!-- Menambahkan method PUT untuk update -->
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label for="cekFisik{{ $data['id'] }}" class="form-label">Cek Fisik</label>
                                                    <input type="number" step="0.01" class="form-control" id="cekFisik{{ $data['id'] }}" name="cek_fisik">
                                                </div>
                                                <div class="mb-3">
                                                    <label for="keterangan{{ $data['id'] }}" class="form-label">Keterangan</label>
                                                    <textarea class="form-control" id="keterangan{{ $data['id'] }}" name="keterangan" rows="3" ></textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-primary">Simpan</button>
                                            </div>
                                            @if (session('success'))
                                                <div class="alert alert-success">
                                                    {{ session('success') }}
                                                </div>
                                            @endif

                                            @if ($errors->any())
                                                <div class="alert alert-danger">
                                                    <ul>
                                                        @foreach ($errors->all() as $error)
                                                            <li>{{ $error }}</li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            @endif
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
