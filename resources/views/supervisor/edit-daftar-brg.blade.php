@section('title', 'Edit Data Barang')
@extends('layout.sidebar')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Edit Data Barang</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Edit Data Barang</li>
    </ol>

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-database me-1"></i>
            Edit Data
        </div>
        <div class="card-body">
            <form action="{{ route('supervisor.update-barang', $barangs->id) }}" method="POST">
                @csrf
                @method('PUT') <!-- For updating, use PUT method -->
            
                <!-- Nama Barang -->
                <div class="mb-3">
                    <label for="nama-brg" class="form-label">Nama Bahan/Barang</label>
                    <input type="text" class="form-control" id="nama-brg" name="nama_brg" value="{{ old('nama_brg', $barangs->nama_brg) }}" placeholder="Masukkan Nama Barang" required>
                </div>

                <div class="mb-3">
                    <label for="kelompok" class="form-label">Kelompok</label>
                    <select class="form-control" id="kelompok" name="kelompok" required>
                        <option value="" disabled>Pilih Kelompok</option>
                        <option value="Bahan" {{ $barangs->kelompok == 'Bahan' ? 'selected' : '' }}>Bahan</option>
                        <option value="Barang" {{ $barangs->kelompok == 'Barang' ? 'selected' : '' }}>Barang</option>
                    </select>
                </div>

                <!-- Kategori Barang -->
                <div class="mb-3">
                    <label for="kategori-brg" class="form-label">Kategori</label>
                    <select class="form-control" id="kategori-brg" name="kategori_brg_id" required>
                        <option value="" disabled>Pilih Kategori Barang</option>
                        @foreach($kategori_brgs as $kategori_brg)
                            <option value="{{ $kategori_brg->id }}" {{ $barangs->kategori_brg_id == $kategori_brg->id ? 'selected' : '' }}>
                                {{ $kategori_brg->kategori_brg }}
                            </option>
                        @endforeach
                    </select>
                </div>
            
                <!-- Satuan Barang -->
                <div class="mb-3">
                    <label for="satuan-unit" class="form-label">Satuan</label>
                    <select class="form-control" id="satuan-unit" name="satuan_brg_id" required>
                        <option value="" disabled>Pilih Satuan Barang</option>
                        @foreach($satuans as $satuan)
                            <option value="{{ $satuan->id }}" {{ $barangs->satuan_brg_id == $satuan->id ? 'selected' : '' }}>
                                {{ $satuan->satuan_brg }}
                            </option>
                        @endforeach
                    </select>
                </div>
            
                <!-- Stok Barang -->
                <div class="mb-3">
                    <label for="stok-brg" class="form-label">Stok</label>
                    <input type="number" step="0.01" class="form-control" id="stok-brg" name="stok_brg" value="{{ old('stok_brg', $barangs->stok_brg) }}" placeholder="Masukkan Stok Barang" required readonly>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn btn-primary">Update</button>
            </form>
        </div>
    </div>
</div>
@endsection
