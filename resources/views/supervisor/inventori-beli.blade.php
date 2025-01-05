@section('title', 'Pembelian Bahan')
@extends('layout.sidebar')
@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Pembelian Bahan</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Pembelian Bahan</li>
    </ol>
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-table me-1"></i>
            Tabel Data
        </div>
        <div class="d-flex align-items-left justify-content-between mt-2 mb-0 mx-3">
            <form action="{{ route('supervisor.inventori-beli') }}" method="GET" class="d-flex align-items-center ms-auto">
                <div class="row g-2 align-items-center">
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
                        <span class="mx-1">—</span> 
                    </div>        
                    
                    <div class="col-auto">
                        <div class="text-muted" style="font-size: 0.85em; margin-bottom: 2px;">Tanggal Selesai</div>
                        <div class="input-group">
                            <span class="input-group-text bg-primary text-white">
                                <i class="fas fa-calendar-alt"></i>
                            </span>
                            <input type="date" class="form-control" name="tanggal_selesai" value="{{ $tanggalSelesai }}" placeholder="Tanggal Selesai">
                        </div>
                    </div>
                            
                    <div class="col-auto">
                        <div class="text-muted" style="font-size: 0.85em; margin-bottom: 2px;">Kategori</div>
                        <select class="form-select" name="kategori_id">
                            <option value="">Pilih Kategori</option>
                            @foreach($kategoris as $kategori)
                                <option value="{{ $kategori->id }}" {{ $kategori_id == $kategori->id ? 'selected' : '' }}>
                                    {{ $kategori->kategori }}
                                </option>
                            @endforeach
                        </select>
                    </div>
        
                    <div class="col-auto">
                        <div class="text-muted" style="font-size: 0.85em; margin-bottom: 2px;">Filter</div>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-filter"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="datatablesSimple" class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>ID Bahan</th>
                            <th>Nama</th>
                            <th>Kategori</th>
                            <th>Satuan</th>
                            <th>Pembelian</th>
                            <th>Tambahan Sore</th>
                            <th>Pegawai (ID)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pembelianBahans as $key => $beli)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $beli->tanggal }}</td>
                                <td>{{ $beli->bahan->id }}</td>
                                <td>{{ $beli->bahan->nama }}</td> <!-- Ambil nama bahan dari relasi bahan -->
                                <td>{{ $beli->bahan->kategori->kategori ?? 'Tidak Diketahui' }}</td> <!-- Ambil kategori dari bahan -->
                                <td>{{ $beli->bahan->satuan }}</td>
                                <td>{{ $beli->pembelian }}</td>
                                <td>{{ $beli->tambahan_sore }}</td>
                                <td>{{ $beli->user->name . ' (' . $beli->user->id . ')' ?? 'Tidak Diketahui' }}</td> <!-- Menampilkan nama user yang menginput -->
                            </tr>
                        @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
