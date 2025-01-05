@section('title', 'Dashboard')
@extends('layout.sidebaro')
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
        <li class="breadcrumb-item active">Dashboard Admin</li>
    </ol>
    <div class="row"> <!-- Menambahkan justify-content-center untuk menggeser ke tengah -->
        <div class="col-xl-3 col-md-6 col-sm-12 mb-4 d-flex justify-content-center"> <!-- Menggunakan d-flex untuk jarak antar kartu -->
            <div class="card custom-card" style="height: 150px; width: 100%;"> <!-- Atur tinggi kartu -->
                <div class="card-body d-flex flex-column align-items-center justify-content-center">
                    <i class="fas fa-users fa-2x mb-2"></i> <!-- Ikon user lebih besar dan diberi margin bawah -->
                    <span class="custom-card-title">Users</span>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a class="small text-white stretched-link no-underline" href="{{ route('admin.kelola-user') }}">
                        {{ $totalUsers }}
                    </a>
                </div>
            </div>
        </div>         
        <div class="col-xl-3 col-md-6 col-sm-12 mb-4 d-flex justify-content-center">
            <div class="card custom-card" style="height: 150px; width: 100%;"> <!-- Menambahkan lebar penuh -->
                <div class="card-body d-flex flex-column align-items-center justify-content-center position-relative">
                    <i class="fas fa-user fa-2x"></i> <!-- Ikon pengguna -->
                    <!-- Badge hijau yang menandakan user aktif -->
                    <span class="position-absolute top-0 start-100 translate-middle p-1 bg-success border border-light rounded-circle">
                        <span class="visually-hidden">Active</span>
                    </span>
                    <span class="custom-card-title mt-2">Pegawai Aktif</span>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a class="small text-white stretched-link no-underline">
                        {{ $totalPegawaiAktif }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
