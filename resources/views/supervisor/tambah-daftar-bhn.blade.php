@section('title', 'Tambah Data Bahan')
@extends('layout.sidebar')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Tambah Data Bahan</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Tambah Data Bahan</li>
    </ol>

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-database me-1"></i>
            Tambah Data
        </div>
        <div class="card-body">
            <form action="{{ route('supervisor.store-bahan') }}" method="POST">
                @csrf
                <div class="mb-2">
                    <label for="nama-bhn" class="form-label">Nama Bahan</label>
                    <input type="text" class="form-control" id="nama-bhn" name="nama" value="{{ old('nama', $nama) }}" placeholder="Masukkan Nama Bahan" required>
                </div>
                <div class="mb-2">
                    <label for="kategori-bhn" class="form-label">Kategori</label>
                    <select class="form-control" id="kategori-bhn" name="kategori_id" required>
                        <option value="" disabled selected>Pilih Kategori Bahan</option>
                        @foreach($kategori_bhns as $kategori_bhn)
                        <option value="{{ $kategori_bhn->id }}">{{ $kategori_bhn->kategori }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-2">
                    <label for="satuan-bhn" class="form-label">Satuan</label>
                    <select class="form-control" id="satuan-bhn" name="satuan" required>
                        <option value="" disabled selected>Pilih Satuan Bahan</option>
                        <option value="KG">KG</option>
                        <option value="POT">POT</option>
                    </select>
                </div>
                <div class="mb-2">
                    <label for="stok-bhn" class="form-label">Stok</label>
                    <input type="number" step="0.01" class="form-control" id="stok-bhn" name="stok" placeholder="Masukkan Stok Bahan" required>
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
