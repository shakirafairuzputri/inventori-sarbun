@section('title', 'Tambah Kategori Barang')
@extends('layout.sidebar')
@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Tambah Kategori Barang</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Tambah Kategori Barang</li>
    </ol>

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-database me-1"></i>
            Tambah Data
        </div>
        <div class="card-body">
            <form action="{{ route('supervisor.store-kategoribrg') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="kategori-brg" class="form-label">Kategori</label>
                    <input type="text" class="form-control" id="kategori-brg" name="kategori_brg" placeholder="Masukkan Kategori Barang" required>
                </div>
                <button type="submit" class="btn btn-primary">Submit</button>
                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </form>
        </div>
    </div>
</div>
@endsection
