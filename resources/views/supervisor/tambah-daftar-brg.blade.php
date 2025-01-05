@section('title', 'Tambah Data Barang')
@extends('layout.sidebar')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Tambah Data Barang</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Tambah Data Barang</li>
    </ol>

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-database me-1"></i>
            Tambah Data
        </div>
        <div class="card-body">
            <form action="{{ route('supervisor.store-barang') }}" method="POST">
                @csrf
                <div class="mb-2">
                    <label for="nama-brg" class="form-label">Nama Bahan/Barang</label>
                    <input type="text" class="form-control" id="nama-brg" name="nama_brg" value="{{ old('nama', $nama) }}" placeholder="Masukkan Nama Barang" required>
                </div>
                <div class="mb-2">
                    <label for="kelompok" class="form-label">Kelompok</label>
                    <select class="form-control" id="kelompok" name="kelompok" required>
                        <option value="" disabled selected>Pilih Kelompok</option>
                        <option value="Bahan">Bahan</option>
                        <option value="Barang">Barang</option>
                    </select>
                </div>
                <div class="mb-2">
                    <label for="kategori-brg" class="form-label">Kategori</label>
                    <select class="form-control" id="kategori-brg" name="kategori_brg_id" required>
                        <option value="" disabled selected>Pilih Kategori Barang</option>
                        @foreach($kategori_brgs as $kategori_brg)
                        <option value="{{ $kategori_brg->id }}">{{ $kategori_brg->kategori_brg }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-2">
                    <label for="satuan-unit" class="form-label">Satuan</label>
                    <select class="form-control" id="satuan-unit" name="satuan_brg_id" required>
                        <option value="" disabled selected>Pilih Satuan Barang</option>
                        @foreach($satuans as $satuan)
                        <option value="{{ $satuan->id }}">{{ $satuan->satuan_brg }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-2">
                    <label for="stok-brg" class="form-label">Stok</label>
                    <input type="number" step="0.01" class="form-control" id="stok-brg" name="stok_brg" placeholder="Masukkan Stok Barang" required>
                </div>
                <button type="submit" class="btn btn-primary">Submit</button>
                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif
            </form>
        </div>
    </div>
</div>
@endsection
