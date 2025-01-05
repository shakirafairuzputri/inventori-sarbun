@section('title', 'Barang Masuk')
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
    <h1 class="mt-4">Data Barang Masuk</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Data Barang Masuk</li>
    </ol>
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-table me-1"></i>
            Tabel Data
        </div>
        <div class="d-flex justify-content-start mt-2 ms-3">
            <a class="btn btn-primary" href="{{ route('pegawai.tambah-persediaan-brgm') }}">Tambah Data</a>
        </div>
        <div class="d-flex align-items-left justify-content-end mt-2 mb-0 mx-3">
            <form action="{{ route('pegawai.persediaan-brgm') }}" method="GET" class="d-flex">
                <div class="row g-2 align-items-center">
                    <div class="col-auto">
                        <div class="text-muted" style="font-size: 0.85em; margin-bottom: 2px;">Tanggal</div>
                        <div class="input-group">
                            <!-- Ikon kalender dari Font Awesome -->
                            <span class="input-group-text">
                                <i class="fas fa-calendar-alt"></i> <!-- Ini adalah ikon kalender -->
                            </span>
                            <input type="date" class="form-control" name="tanggal" value="{{ $tanggal }}">
                        </div>
                    </div>
                    <div class="col-auto me-2">
                        <div class="text-muted ms-2" style="font-size: 0.85em; margin-bottom: 2px;">Kelompok</div>
                        <div class="input-group mx-2">
                            <select class="form-select" name="kelompok" id="kelompok">
                                <option value="" {{ $kelompok == null ? 'selected' : '' }}>Pilih Kelompok</option>
                                <option value="Bahan" {{ $kelompok == 'Bahan' ? 'selected' : '' }}>Bahan</option>
                                <option value="Barang" {{ $kelompok == 'Barang' ? 'selected' : '' }}>Barang</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-auto me-3">
                        <div class="text-muted ms-2" style="font-size: 0.85em; margin-bottom: 2px;">Kategori</div>
                        <div class="input-group mx-2">
                            <select class="form-select" name="kategori_id">
                                <option value="">Pilih Kategori</option>
                                @foreach($kategoris as $kategori_brg)
                                    <option value="{{ $kategori_brg->id }}" {{ $kategori_id == $kategori_brg->id ? 'selected' : '' }}>
                                        {{ $kategori_brg->kategori_brg }}
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
                        <th>Nama Barang/Bahan</th>
                        <th>Kelompok</th>
                        <th>Kategori</th>
                        <th>Satuan Unit</th>
                        <th>Tambah</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($persediaan_barangs as $key => $masuk)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $masuk->tanggal }}</td>
                            <td>{{ $masuk->barang->nama_brg }}</td>
                            <td>{{ $masuk->barang->kelompok }}</td>
                            <td>{{ $masuk->barang->kategori_brg->kategori_brg ?? 'Tidak Diketahui' }}</td>
                            <td>{{ $masuk->barang->satuan_brg->satuan_brg }}</td>
                            <td>{{ $masuk->tambah }}</td>
                            <td>
                                @if ($masuk->tanggal == $today)
                                    <a href="{{ route('pegawai.persediaan-brgm.edit', $masuk->id) }}" class="btn btn-primary">Edit</a>
                                    <form action="{{ route('pegawai.destroy-brgm', $masuk->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
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
@endsection