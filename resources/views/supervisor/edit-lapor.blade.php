@section('title', 'Edit Data Laporan Kesalahan')
@extends('layout.sidebar')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Edit Laporan Kesalahan</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Edit Laporan Kesalahan</li>
    </ol>

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-database me-1"></i>
            Edit Data
        </div>
        <div class="card-body">
            {{-- Form to edit the report --}}
            <form action="{{ route('supervisor.lapor.update', $lapor->id) }}" method="POST">
                @csrf <!-- Laravel CSRF token for form security -->
                @method('PUT') <!-- Specify the method as PUT for updating -->

                <div class="mb-3">
                    <label for="user_id" class="form-label">Nama Pegawai</label>
                    <select class="form-control" id="user_id" name="user_id" required>
                        <option value="" disabled>Pilih Nama Pegawai</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ $user->id == $lapor->user_id ? 'selected' : '' }}>{{ $user->name }}</option>
                        @endforeach
                    </select>
                    @error('user_id')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="tanggal_lapor" class="form-label">Tanggal</label>
                    <input type="date" class="form-control" id="tanggal_lapor" name="tanggal" value="{{ old('tanggal', $lapor->tanggal) }}" required>
                    @error('tanggal')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="kategori_lapor" class="form-label">Kategori</label>
                    <select class="form-control" id="kategori_lapor" name="kategori" required>
                        <option value="" disabled>Pilih Kategori Laporan</option>
                        <option value="Retur Bahan" {{ $lapor->kategori == 'Retur Bahan' ? 'selected' : '' }}>Retur Bahan</option>
                        <option value="Pembelian Bahan" {{ $lapor->kategori == 'Pembelian Bahan' ? 'selected' : '' }}>Pembelian Bahan</option>
                        <option value="Produksi Bahan" {{ $lapor->kategori == 'Produksi Bahan' ? 'selected' : '' }}>Produksi Bahan</option>
                        <option value="Barang Masuk" {{ $lapor->kategori == 'Barang Masuk' ? 'selected' : '' }}>Barang Masuk</option>
                        <option value="Barang Keluar" {{ $lapor->kategori == 'Barang Keluar' ? 'selected' : '' }}>Barang Keluar</option>
                    </select>
                    @error('kategori')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="keterangan_lapor" class="form-label">Keterangan</label>
                    <textarea class="form-control" id="keterangan_lapor" name="keterangan" rows="3" placeholder="Masukkan Keterangan Laporan" required>{{ old('keterangan', $lapor->keterangan) }}</textarea>
                    @error('keterangan')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">Update</button>
            </form>
        </div>
    </div>
</div>
@endsection
