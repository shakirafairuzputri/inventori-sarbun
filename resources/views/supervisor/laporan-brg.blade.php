@section('title', 'Laporan Persediaan Barang')
@extends('layout.sidebar')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Laporan Persediaan Barang</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Laporan Persediaan Barang</li>
    </ol>

    <style>
        @media print {
            @page {
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
                font-size: 0.5rem; 
                padding: 0; 
                word-wrap: break-word;
            }

            .breadcrumb, .card-header, .btn, form .input-group, form .col-auto, form .text-muted {
                display: none;
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
        
        <!-- Form untuk filter dengan dua baris -->
        <div class="d-flex justify-content-end mt-2 mb-0 mx-3">
            <form action="{{ route('supervisor.laporan-brg') }}" method="GET" class="w-100">
                
                <!-- Baris 1: Tanggal Mulai, Tanggal Selesai -->
                <div class="row g-2 justify-content-end">
                    <!-- Tanggal Mulai -->
                    <div class="col-auto">
                        <div class="text-muted ms-2" style="font-size: 0.85em; margin-bottom: 2px;">Tanggal Mulai</div>
                        <div class="input-group">
                            <span class="input-group-text bg-primary text-white">
                                <i class="fas fa-calendar-alt"></i>
                            </span>
                            <input type="date" class="form-control" name="tanggal_mulai" value="{{ $tanggalMulai }}" placeholder="Tanggal Mulai">
                        </div>
                    </div>

                    <!-- Tanda Pisah -->
                    <div class="col-auto align-self-center">
                        <span class="mx-2">—</span>
                    </div>
                    
                    <!-- Tanggal Selesai -->
                    <div class="col-auto">
                        <div class="text-muted ms-2" style="font-size: 0.85em; margin-bottom: 2px;">Tanggal Selesai</div>
                        <div class="input-group">
                            <span class="input-group-text bg-primary text-white">
                                <i class="fas fa-calendar-alt"></i>
                            </span>
                            <input type="date" class="form-control" name="tanggal_selesai" value="{{ $tanggalSelesai }}" placeholder="Tanggal Selesai">
                        </div>
                    </div>
                </div>
                
                <!-- Baris 2: Kategori, Kelompok, Filter, Unduh, Cetak -->
                <div class="d-flex justify-content-end mt-2">
                    <!-- Pilih Kelompok -->
                    <div class="col-3 me-2">
                        <div class="text-muted ms-2" style="font-size: 0.85em; margin-bottom: 2px;">Kelompok</div>
                        <div class="input-group mx-2">
                            <select class="form-select" name="kelompok" id="kelompok">
                                <option value="" {{ $kelompok == null ? 'selected' : '' }}>Pilih Kelompok</option>
                                <option value="Bahan" {{ $kelompok == 'Bahan' ? 'selected' : '' }}>Bahan</option>
                                <option value="Barang" {{ $kelompok == 'Barang' ? 'selected' : '' }}>Barang</option>
                            </select>
                        </div>
                    </div>
                    
                    <!-- Pilih Kategori Barang -->
                    <div class="col-3 me-2">
                        <div class="text-muted ms-2" style="font-size: 0.85em; margin-bottom: 2px;">Kategori</div>
                        <div class="input-group mx-2">
                            <select name="kategori_brg" class="form-select">
                                <option value="">Pilih Kategori Barang</option>
                                @foreach($kategorisBarang as $kategori)
                                    <option value="{{ $kategori->kategori_brg }}" {{ $kategori_brg == $kategori->kategori_brg ? 'selected' : '' }}>
                                        {{ $kategori->kategori_brg }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Tombol Filter -->
                    <div class="col-auto me-2">
                        <div class="text-muted ms-2" style="font-size: 0.85em; margin-bottom: 2px;">Filter</div>
                        <button type="submit" class="btn btn-primary ms-2">
                            <i class="fas fa-filter"></i>
                        </button>
                    </div>

                    <!-- Tombol Unduh -->
                    <div class="col-auto me-2">
                        <div class="text-muted" style="font-size: 0.85em; margin-bottom: 2px;">Unduh</div>
                        <a href="{{ route('supervisor.download-laporan', ['tanggal_mulai' => $tanggalMulai, 'tanggal_selesai' => $tanggalSelesai, 'kelompok' => $kelompok, 'kategori_brg' => $kategori_brg]) }}" class="btn btn-success" target="_blank">
                            <i class="fas fa-download"></i>
                        </a>
                    </div>

                    <!-- Tombol Cetak -->
                    <div class="col-auto">
                        <div class="text-muted" style="font-size: 0.85em; margin-bottom: 2px;">Cetak</div>
                        <button onclick="window.print()" class="btn btn-secondary">
                            <i class="fas fa-print"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Judul Laporan -->
        <h5 class="mt-4 text-center small">
            Laporan Persediaan Barang 
            (
                {{ \Carbon\Carbon::parse($tanggalMulai)->format('d-m-Y') }} 
                @if($tanggalMulai !== $tanggalSelesai)
                    s.d. {{ \Carbon\Carbon::parse($tanggalSelesai)->format('d-m-Y') }}
                @endif
            )
        </h5>   

        <!-- Tabel Data -->
        <div class="card-body">
            <div class="table-responsive">
                <table id="datatablesSimple" class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Nama Bahan/Barang</th>
                            <th>Kelompok</th>
                            <th>Kategori</th>
                            <th>Satuan</th>
                            <th>Stok Awal</th>
                            <th>Tambah</th>
                            <th>Kurang</th>
                            <th>Sisa</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($reportData as $data)
                            <tr>
                                <td>{{ $data['tanggal'] }}</td>
                                <td>{{ $data['nama_barang'] }}</td>
                                <td>{{ $data['kelompok'] }}</td>
                                <td>{{ $data['kategori_brg'] }}</td>
                                <td>{{ $data['satuan_brg'] }}</td>
                                <td>{{ $data['stok_awal'] }}</td>
                                <td>{{ $data['tambah'] }}</td>
                                <td>{{ $data['kurang'] }}</td>
                                <td>{{ $data['sisa'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
