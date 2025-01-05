@section('title', 'Dashboard')
@extends('layout.sidebar')
@section('content')
@if (session('success'))
<div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="successModalLabel">Sukses</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                {{ session('success') }}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">OK</button>
            </div>
        </div>
    </div>
</div>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        var successModal = new bootstrap.Modal(document.getElementById('successModal'));
        successModal.show();
    });
</script>
@endif
<div class="container-fluid px-4">
    <h1 class="mt-4">Dashboard</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Dashboard Supervisor</li>
    </ol>
    <div class="row">
        <div class="col-xl-3 col-md-6 col-sm-12 mb-4 d-flex justify-content-center">
            <div class="card custom-card" style="height: 150px; width: 100%;">
                <div class="card-body d-flex flex-column align-items-center justify-content-center">
                    <i class="fas fa-fish fa-2x mb-2"></i>
                    <span class="custom-card-title">Bahan</span>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a class="small text-white stretched-link no-underline" href="{{ route('supervisor.daftar-bhn') }}">
                        {{ $totalBahans }}
                    </a>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 col-sm-12 mb-4 d-flex justify-content-center">
            <div class="card custom-card" style="height: 150px; width: 100%;">
                <div class="card-body d-flex flex-column align-items-center justify-content-center">
                    <i class="fas fa-box fa-2x mb-2"></i>
                    <span class="custom-card-title">Barang</span>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a class="small text-white stretched-link no-underline" href="{{ route('supervisor.daftar-brg') }}">
                        {{ $totalBarangs }}
                    </a>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 col-sm-12 mb-4 d-flex justify-content-center">
            <div class="card custom-card" style="height: 150px; width: 100%;">
                <div class="card-body d-flex flex-column align-items-center justify-content-center">
                    <i class="fas fa-database fa-2x mb-2"></i>
                    <span class="custom-card-title">Request Input Data</span>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a class="small text-white stretched-link no-underline" href="{{ route('supervisor.request-input') }}">
                        {{ $totalRequest }}
                    </a>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-3">
            <div class="card custom-card d-flex flex-column align-items-start">
                <h5 class="mb-1 ms-2 mt-1">Navigasi</h5>
                <div class="ms-2 me-2 d-flex flex-row justify-content-between">
                    <!-- First list with custom link color and spacing -->
                    <ul class="list-unstyled small-font me-3">
                        <li><a href="{{ route('supervisor.kategori-bhn') }}" class="custom-link">Kategori Bahan</a></li>
                        <li><a href="{{ route('supervisor.kategori-brg') }}" class="custom-link">Kategori Barang</a></li>
                        <li><a href="{{ route('supervisor.unit') }}" class="custom-link">Satuan Unit</a></li>
                        <li><a href="{{ route('supervisor.inventori-retur') }}" class="custom-link">Retur Bahan</a></li>
                        <li><a href="{{ route('supervisor.inventori-beli') }}" class="custom-link">Pembelian Bahan</a></li>
                    </ul>
                
                    <!-- Second list with custom link color -->
                    <ul class="list-unstyled small-font">
                        <li><a href="{{ route('supervisor.inventori-produksi') }}" class="custom-link">Produksi Bahan</a></li>
                        <li><a href="{{ route('supervisor.inventori-brgm') }}" class="custom-link">Barang Masuk</a></li>
                        <li><a href="{{ route('supervisor.inventori-brgk') }}" class="custom-link">Barang Keluar</a></li>
                        <li><a href="{{ route('supervisor.laporan-bhn') }}" class="custom-link">Laporan Bahan</a></li>
                        <li><a href="{{ route('supervisor.laporan-brg') }}" class="custom-link">Laporan Barang</a></li>
                    </ul>
                </div>                
            </div>
        </div>
    </div>
   
    <div class="card mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <h4 class="mb-3">Daftar Bahan Menipis</h4>
                <h5 class="text-danger">Stok Bahan Menipis:</h5>
                <table id="datatablesSimple" class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Bahan</th>
                            <th>Kategori</th>
                            <th>Satuan</th>
                            <th>Stok</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($bahansMenipis as $key => $bahan)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $bahan->nama }}</td>
                                <td>{{ $bahan->kategori->kategori ?? 'Tidak Diketahui' }}</td>
                                <td>{{ $bahan->satuan }}</td>
                                <td>
                                    {{ $bahan->stok }}
                                    @if (($bahan->satuan == 'KG' && $bahan->stok <= 2) || ($bahan->satuan == 'POT' && $bahan->stok <= 5))
                                        <span class="text-danger"> (Mohon Lakukan Pembelian)</span>
                                        <span class="dot"></span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>        
    </div>
    <div class="card mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <h4 class="mb-3">Daftar Barang Menipis</h4>
                <h5 class="text-danger">Stok Barang Menipis:</h5>
                <table id="datatablesSimpleBarangs" class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Barang</th>
                            <th>Kategori</th>
                            <th>Satuan</th>
                            <th>Stok</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($barangs as $key => $barang)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $barang->nama_brg }}</td>
                            <td>{{ $barang->kategori_brg->kategori_brg ?? 'Tidak diketahui' }}</td>
                            <td>{{ $barang->satuan_brg->satuan_brg ?? 'Tidak diketahui'}}</td>
                            <td>
                                {{ $barang->stok_brg }}
                                @if ($barang->stok_brg <= 3)
                                    <span class="text-danger"> (Mohon Lakukan Pembelian)</span>
                                    <span class="dot"></span>
                                @endif
                            </td>                            
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>        
    </div>
</div>
@endsection
