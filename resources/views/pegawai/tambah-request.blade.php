{{-- resources/views/request/create.blade.php --}}
@extends('layout.sidebarp')

@section('title', 'Tambah Request Input Data')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Tambah Request Input Data</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Tambah Request Input Data</li>
    </ol>

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-database me-1"></i>
            Tambah Data Request Input
        </div>
        <div class="card-body">
            {{-- Form to submit the data --}}
            <form action="{{ route('pegawai.request-input.store') }}" method="POST">
                @csrf <!-- Laravel CSRF token for form security -->
                
                {{-- Kelompok --}}
                <div class="mb-3">
                    <label for="kelompok" class="form-label">Kelompok</label>
                    <select class="form-control" id="kelompok" name="kelompok" required>
                        <option value="" disabled selected>Pilih Kelompok</option>
                        <option value="Bahan Utama">Bahan Utama</option>
                        <option value="Bahan Lain">Bahan Lain</option>
                        <option value="Barang">Barang</option>
                    </select>
                    @error('kelompok')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Nama --}}
                <div class="mb-3">
                    <label for="nama" class="form-label">Nama Bahan/Barang</label>
                    <input type="text" class="form-control" id="nama" name="nama" required>
                    @error('nama')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Stok
                <div class="mb-3">
                    <label for="stok" class="form-label">Stok</label>
                    <input type="number" class="form-control" id="stok" name="stok" required>
                    @error('stok')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div> --}}

                {{-- Deskripsi --}}
                <div class="mb-3">
                    <label for="deskripsi" class="form-label">Deskripsi</label>
                    <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3"></textarea>
                    @error('deskripsi')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <!-- Flash Message for Success -->
                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                <!-- Error Messages -->
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
        </div>
    </div>
</div>
@endsection
