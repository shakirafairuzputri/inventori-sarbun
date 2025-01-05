@section('title', 'Tambah Data Laporan Kesalahan')
@extends('layout.sidebar')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Tambah Laporan Kesalahan</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Tambah Laporan Kesalahan</li>
    </ol>

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-database me-1"></i>
            Tambah Data
        </div>
        <div class="card-body">
            {{-- Form to submit the report --}}
            <form action="{{ route('supervisor.lapor-kesalahan.store') }}" method="POST">
                @csrf <!-- Laravel CSRF token for form security -->
                
                <div class="mb-3">
                    <label for="user_id" class="form-label">Nama Pegawai</label>
                    <select class="form-control" id="user_id" name="user_id" required>
                        <option value="" disabled selected>Pilih Nama Pegawai</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                    @error('user_id')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="tanggal_lapor" class="form-label">Tanggal</label>
                    <input type="date" class="form-control" id="tanggal_lapor" name="tanggal" required>
                    @error('tanggal')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="kategori_lapor" class="form-label">Kategori</label>
                    <select class="form-control" id="kategori_lapor" name="kategori" required>
                        <option value="" disabled selected>Pilih Kategori Laporan</option>
                        <option value="Retur Bahan">Retur Bahan</option>
                        <option value="Pembelian Bahan">Pembelian Bahan</option>
                        <option value="Produksi Bahan">Produksi Bahan</option>
                        <option value="Barang Masuk">Barang Masuk</option>
                        <option value="Barang Keluar">Barang Keluar</option>
                    </select>
                    @error('kategori')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="keterangan_lapor" class="form-label">Keterangan</label>
                    <textarea class="form-control" id="keterangan_lapor" name="keterangan" rows="3" placeholder="Masukkan Keterangan Laporan" required></textarea>
                    @error('keterangan')
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
