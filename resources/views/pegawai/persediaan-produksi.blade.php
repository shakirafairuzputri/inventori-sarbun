@section('title', 'Produksi Bahan')
@extends('layout.sidebarp')
@section('content')
@php
    use Carbon\Carbon;
    $today = $today = Carbon::today()->toDateString();
@endphp

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
    <h1 class="mt-4">Produksi Bahan</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Produksi Bahan</li>
    </ol>
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-table me-1"></i>
            Tabel Data
        </div>
        <div class="d-flex justify-content-start mt-2 ms-3">
            <a class="btn btn-primary" href="{{ route('pegawai.tambah-persediaan-produksi') }}">Tambah Data</a>
        </div>
        <!-- Form untuk memilih tanggal -->
        <div class="d-flex align-items-left justify-content-end mt-2 mb-0 mx-3">
            <form action="{{ route('pegawai.persediaan-produksi') }}" method="GET" class="d-flex">
                <div class="row g-2 align-items-center">
                    <div class="col-auto">
                        <div class="text-muted" style="font-size: 0.85em; margin-bottom: 2px;">Tanggal</div>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-calendar-alt"></i>
                            </span>
                            <input type="date" class="form-control" name="tanggal" value="{{ $tanggal }}">
                        </div>
                    </div>
                    <div class="col-auto me-3">
                        <div class="text-muted ms-2" style="font-size: 0.85em; margin-bottom: 2px;">Kategori</div>
                        <div class="input-group mx-2">
                            <select class="form-select" name="kategori_id">
                                <option value="">Pilih Kategori</option>
                                @foreach($kategoris as $kategori)
                                    <option value="{{ $kategori->id }}" {{ $kategori_id == $kategori->id ? 'selected' : '' }}>
                                        {{ $kategori->kategori }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-auto">
                        <div class="text-muted" style="font-size: 0.85em; margin-bottom: 2px;">Filter</div>
                        <button type="submit" class="btn btn-primary ml-2">
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
                            <th>Nama</th>
                            <th>Kategori</th>
                            <th>Satuan</th>
                            <th>Produksi Baik</th>
                            <th>Produksi Paket</th>
                            <th>Produksi Rusak</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($produksiBahans as $key => $produksi)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $produksi->tanggal }}</td>
                                <td>{{ $produksi->pembelian->bahan->nama }}</td>
                                <td>{{ $produksi->pembelian->bahan->kategori->kategori ?? 'Tidak Diketahui' }}</td> 
                                <td>{{ $produksi->pembelian->bahan->satuan }}</td>
                                <td>{{ $produksi->produksi_baik }}</td>
                                <td>{{ $produksi->produksi_paket }}</td>
                                <td>{{ $produksi->produksi_rusak }}</td>
                                <td>
                                    @if ($produksi->tanggal == $today)
                                        <a href="{{ route('pegawai.persediaan-produksi.edit', $produksi->id) }}" class="btn btn-primary">Edit</a>
                                        <form action="{{ route('pegawai.persediaan-produksi.destroy', $produksi->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger">Hapus</button>
                                        </form>
                                    @else
                                        <span class="text-muted">Tidak ada aksi</span>
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
