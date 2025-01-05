@section('title', 'Edit Barang Keluar')
@extends('layout.sidebarp')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Edit Data Barang Keluar</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Edit Data Barang Keluar</li>
    </ol>

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-database me-1"></i>
            Edit Data
        </div>
        <div class="card-body">
            <form action="{{ route('pegawai.persediaan-brgk.update', $persediaan_barangs->id) }}" method="POST">
                @csrf <!-- CSRF protection -->
                @method('PUT') 
                
                <div class="mb-2">
                    <label for="tanggal" class="form-label">Tanggal</label>
                    <input type="date" class="form-control" id="tanggal" name="tanggal" value="{{ old('tanggal', $persediaan_barangs->tanggal) }}" required>
                </div>
            
                <div class="mb-2">
                    <label for="barang_id" class="form-label">Nama Barang</label>
                    <select class="form-control" id="barang_id" name="barang_id" required onchange="autofillDetails()">
                        <option value="" disabled>Pilih Barang</option>
                        @foreach($barangs as $barang)
                            <option value="{{ $barang->id }}" data-kelompok="{{ $barang->kelompok }}" data-kategori="{{ $barang->kategori_brg->kategori_brg ?? 'Tidak Diketahui' }}" data-satuan="{{ $barang->satuan_brg->satuan_brg }}" {{ $barang->id == $persediaan_barangs->barang_id ? 'selected' : '' }}>
                                {{ $barang->nama_brg }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <!-- Autofill Fields -->
                <div class="mb-2">
                    <label for="kelompok" class="form-label">Kelompok</label>
                    <input type="text" class="form-control" id="kelompok" name="kelompok" value="{{ old('kelompok', $persediaan_barangs->kelompok ?? '') }}" readonly>
                </div>
                <div class="mb-2">
                    <label for="kategori" class="form-label">Kategori</label>
                    <input type="text" class="form-control" id="kategori" name="kategori" value="{{ old('kategori', $persediaan_barangs->kategori ?? '') }}" readonly>
                </div>
                <div class="mb-2">
                    <label for="satuan" class="form-label">Satuan Unit</label>
                    <input type="text" class="form-control" id="satuan" name="satuan" value="{{ old('satuan', $persediaan_barangs->satuan ?? '') }}" readonly>
                </div>
                
                <div class="mb-2">
                    <label for="barang-keluar" class="form-label">Barang Keluar</label>
                    <input type="number" step="0.01" class="form-control" id="barang-keluar" name="kurang" value="{{ old('kurang', $persediaan_barangs->kurang) }}" placeholder="Masukkan Jumlah Barang Keluar" required>
                </div>
                

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
            
                <button type="submit" class="btn btn-primary">Update</button>
            </form>            
        </div>
    </div>
</div>

<script>
    function autofillDetails() {
        const barangSelect = document.getElementById('barang_id');
        const selectedOption = barangSelect.options[barangSelect.selectedIndex];
        
        document.getElementById('kelompok').value = selectedOption.getAttribute('data-kelompok') || '';
        document.getElementById('kategori').value = selectedOption.getAttribute('data-kategori') || '';
        document.getElementById('satuan').value = selectedOption.getAttribute('data-satuan') || '';
    }

    // Autofill details on page load if there's already a selected barang
    window.onload = function() {
        autofillDetails();
    }
</script>
@endsection
