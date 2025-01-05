@section('title', 'Edit Data Bahan')
@extends('layout.sidebar')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Edit Data Bahan</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Edit Data Bahan</li>
    </ol>

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-database me-1"></i>
            Edit Data
        </div>
        <div class="card-body">
            <form action="{{ route('supervisor.update-bahan', ['id'=> $bahans->id]) }}" method="POST"> <!-- Ganti $bahan->$id menjadi $bahan->id -->
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label for="nama-bhn" class="form-label">Nama Bahan</label>
                    <input type="text" class="form-control" id="nama-bhn" name="nama" placeholder="Masukkan Nama Bahan" value="{{ old('nama', $bahans->nama) }}" required>
                </div>
                <div class="mb-3">
                    <label for="kategori-bhn" class="form-label">Kategori</label>
                    <select class="form-control" id="kategori-bhn" name="kategori_id" required>
                        @foreach($kategori_bhns as $kategori_bhn)
                        <option value="{{ $kategori_bhn->id }}" 
                            {{ $bahans->kategori_id == $kategori_bhn->id ? 'selected' : '' }}>
                            {{ $kategori_bhn->kategori }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label for="satuan-bhn" class="form-label">Satuan</label>
                    <select class="form-control" id="satuan-bhn" name="satuan" required>
                        <option value="" disabled>Pilih Satuan Bahan</option>
                        <option value="KG" {{ $bahans->satuan == 'KG' ? 'selected' : '' }}>KG</option>
                        <option value="POT" {{ $bahans->satuan == 'POT' ? 'selected' : '' }}>POT</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="stok-bhn" class="form-label">Stok</label>
                    <input type="number" step="0.01" class="form-control" id="stok-bhn" name="stok" placeholder="Masukkan Stok Bahan" value="{{ old('stok', $bahans->stok) }}" required readonly>
                </div>
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
        </div>
    </div>
</div>
@endsection