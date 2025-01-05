@section('title', 'Retur Bahan')
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
    <h1 class="mt-4">Retur Bahan</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Retur</li>
    </ol>
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-table me-1"></i>
            Tabel Data
        </div>
        <div class="card ms-3 mt-3 me-3"  style="height: auto; width: auto;">
            <div class="card-body d-flex flex-column">
                <a><b>Ketentuan Retur Bahan</b></a>
                <ul>
                    <li>Retur Baik: Retur untuk bahan dalam keadaan baik namun sudah tidak layak digunakan. Contoh: Bahan yang sudah tertimbun lama di dalam penyimpanan.</li>
                    <li>Retur Rusak: Retur untuk bahan dalam keadaan tidak baik (rusak).</li>
                </ul>                
            </div>
        </div>
        <div class="d-flex justify-content-start mt-3 ms-3">
            <a class="btn btn-primary" href="{{ route('pegawai.tambah-persediaan-retur') }}">Tambah Data</a>
        </div>
        <!-- Form untuk memilih tanggal -->
        <div class="d-flex align-items-left justify-content-end mt-2 mb-0 mx-3">
            <form action="{{ route('pegawai.persediaan-retur') }}" method="GET" class="d-flex">
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
                            <th>Retur Baik</th>
                            <th>Retur Rusak</th>
                            <th>Jenis Kerusakan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($returBahans as $key => $retur)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $retur->tanggal }}</td>
                                <td>{{ $retur->bahan->nama }}</td>
                                <td>{{ $retur->bahan->kategori->kategori ?? 'Tidak Diketahui' }}</td>
                                <td>{{ $retur->bahan->satuan }}</td>
                                <td>{{ $retur->retur_baik }}</td>
                                <td>{{ $retur->retur_rusak }}</td>
                                <td>{{ $retur->jenis_kerusakan }}</td>
                                <td>
                                    @if ($retur->tanggal == $today)
                                        <a href="{{ route('pegawai.persediaan-retur.edit', $retur->id) }}" class="btn btn-primary">Edit</a>
                                        <form action="{{ route('pegawai.persediaan-retur.destroy', $retur->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
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
