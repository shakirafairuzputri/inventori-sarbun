@section('title', 'Dashboard')
@extends('layout.sidebarp')
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
        <li class="breadcrumb-item active">Dashboard Pegawai</li>
    </ol>
    <div class="d-flex flex-row justify-content-between">
        <div class="col-xl-3 col-md-6 col-sm-12 mb-4 d-flex justify-content-center">
            <div class="card custom-card" style="height: 150px; width: 100%;">
                <div class="card-body d-flex flex-column align-items-center justify-content-center">
                    <i class="fas fa-file-alt fa-2x mb-2"></i>
                    <span class="custom-card-title">Laporan Kesalahan</span>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a class="small text-white stretched-link no-underline" href="{{ route('pegawai.laporan') }}">
                        {{ $totalLaporan }}
                    </a>
                </div>
            </div>
        </div>
        {{-- <div class="col-xl-2 col-md-4">
        </div> --}}
        <div class="col-xl-2 col-md-2">
            <div class="card custom-card d-flex flex-column align-items-start" style="height: 208px; width: 100%;">
                <h5 class="mb-1 ms-3 mt-1 text-center">Navigasi</h5>
                <div class="ms-3 d-flex flex-row justify-content-between">
                    <!-- First list with custom link color and spacing -->
                    <ul class="list-unstyled small-font">
                        <li><a href="{{ route('pegawai.persediaan-retur') }}" class="custom-link">Retur Bahan</a></li>
                        <li><a href="{{ route('pegawai.persediaan-beli') }}" class="custom-link">Pembelian Bahan</a></li>
                        <li><a href="{{ route('pegawai.persediaan-produksi') }}" class="custom-link">Produksi Bahan</a></li>
                        <li><a href="{{ route('pegawai.persediaan-brgm') }}" class="custom-link">Barang Masuk</a></li>
                        <li><a href="{{ route('pegawai.persediaan-brgk') }}" class="custom-link">Barang Keluar</a></li>
                        <li><a href="{{ route('pegawai.laporan-bhn') }}" class="custom-link">Laporan Bahan</a></li>
                        <li><a href="{{ route('pegawai.laporan-brg') }}" class="custom-link">Laporan Barang</a></li>
                        <li><a href="{{ route('pegawai.request-input') }}" class="custom-link">Request Input Data</a></li>
                    </ul>
                
                    <!-- Second list with custom link color -->
                    {{-- <ul class="list-unstyled small-font">
                        

                    </ul> --}}
                </div>                
            </div>
        </div>
    </div>
</div>
@endsection