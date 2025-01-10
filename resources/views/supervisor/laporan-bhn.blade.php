@section('title', 'Laporan Persediaan Bahan')
@extends('layout.sidebar')
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

            .breadcrumb, .card-header, .btn, form .input-group, form .col-auto, .dataTables_info, .dataTables_length, .dataTables_filter {
                display: none !important;
            }
        }
        @media (max-width: 768px) {
            .row {
                display: flex;
                flex-direction: column;
                margin-top: 0%;
                margin-bottom: 0%;
                justify-content: end;
            }
        }

    </style>

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-table me-1"></i>
            Tabel Data
        </div>
        <div class="d-flex justify-content-between mt-2 mb-0 mx-3">
            <form action="{{ route('supervisor.laporan-bhn') }}" method="GET" class="w-100">
                <!-- Baris 1: Tanggal Mulai dan Tanggal Selesai -->
                <div class="row g-2 justify-content-end">
                    <div class="col-auto">
                        <div class="text-muted" style="font-size: 0.85em; margin-bottom: 2px;">Tanggal Mulai</div>
                        <div class="input-group">
                            <span class="input-group-text bg-primary text-white">
                                <i class="fas fa-calendar-alt"></i>
                            </span>
                            <input type="date" class="form-control" name="tanggal_mulai" value="{{ $tanggalMulai }}" placeholder="Tanggal Mulai">
                        </div>
                    </div>
        
                    <div class="col-auto align-self-center">
                        <span class="mx-2">—</span>
                    </div>
        
                    <!-- Tanggal Selesai -->
                    <div class="col-auto">
                        <div class="text-muted" style="font-size: 0.85em; margin-bottom: 2px;">Tanggal Selesai</div>
                        <div class="input-group">
                            <span class="input-group-text bg-primary text-white">
                                <i class="fas fa-calendar-alt"></i>
                            </span>
                            <input type="date" class="form-control" name="tanggal_selesai" value="{{ $tanggalSelesai }}" placeholder="Tanggal Selesai">
                        </div>
                    </div>
                </div>
        
                <!-- Baris 2: Kategori, Filter, Unduh, Cetak -->
                <div class="d-flex justify-content-end mt-0">
                    <!-- Filter Kategori -->
                    <div class="col-auto">
                        <div class="text-muted" style="font-size: 0.85em; margin-bottom: 2px;">Kategori</div>
                        <div class="input-group">
                            <select name="kategori_id" class="form-control">
                                <option value="">Pilih Kategori</option>
                                @foreach($kategoriOptions as $kategori)
                                    <option value="{{ $kategori->id }}" {{ $kategori->id == $kategoriId ? 'selected' : '' }}>
                                        {{ $kategori->kategori }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    
                    <!-- Filter Button -->
                    <div class="col-auto me-2">
                        <div class="text-muted ms-2" style="font-size: 0.85em; margin-bottom: 2px;">Filter</div>
                        <button type="submit" class="btn btn-primary ms-2">
                            <i class="fas fa-filter"></i>
                        </button>
                    </div>
                    
                    <!-- Unduh Button -->
                    <div class="col-auto me-2">
                        <div class="text-muted" style="font-size: 0.85em; margin-bottom: 2px;">Unduh</div>
                        <a href="{{ route('supervisor.download-laporan-bhn', ['tanggalMulai' => $tanggalMulai, 'tanggalSelesai' => $tanggalSelesai, 'kategori_id' => request('kategori_id') ]) }}" class="btn btn-success" target="_blank">
                            <i class="fas fa-download"></i>
                        </a>
                    </div>
                    
                    <!-- Cetak Button -->
                    <div class="col-auto">
                        <div class="text-muted ms-1" style="font-size: 0.85em; margin-bottom: 2px;">Cetak</div>
                        <button onclick="window.print()" class="btn btn-secondary">
                            <i class="fas fa-print"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>
        
        
        
        <h5 class="mt-4 text-center small">
            Laporan Persediaan Bahan 
            (
                {{ \Carbon\Carbon::parse($tanggalMulai)->format('d-m-Y') }} 
                @if($tanggalMulai !== $tanggalSelesai)
                    s.d. {{ \Carbon\Carbon::parse($tanggalSelesai)->format('d-m-Y') }}
                @endif
            )
        </h5>        
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
                                <td>
                                    {{ $data['selisih'] == 0 ? '0' : ($data['selisih'] < 0 ? '-' . abs($data['selisih']) : $data['selisih']) }}
                                </td>
                                <td>{{ $data['tambahan_sore'] }}</td>
                                <td>{{ $data['stok_akhir'] }}</td>
                                <td>{{ $data['keterangan'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection
