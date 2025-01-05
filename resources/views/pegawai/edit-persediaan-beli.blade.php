@section('title', 'Edit Pembelian Bahan')
@extends('layout.sidebarp')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Edit Data Pembelian Bahan</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Edit Data Pembelian Bahan</li>
    </ol>

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-database me-1"></i>
            Edit Data
        </div>
        <div class="card-body">
            <form action="{{ route('pegawai.persediaan-beli.update', $pembelianBahans->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-2">
                    <label for="tanggal" class="form-label">Tanggal</label>
                    <input type="date" class="form-control" id="tanggal" name="tanggal" value="{{ $pembelianBahans->tanggal }}" required>
                </div>
                
                <div class="mb-2">
                    <label for="bahan_id" class="form-label">Nama Bahan</label>
                    <select class="form-control" id="bahan_id" name="bahan_id_disabled" disabled>
                        <option value="" disabled>Pilih Nama Bahan</option>
                        @foreach ($bahans as $bahan)
                            <option value="{{ $bahan->id }}" 
                                data-kategori="{{ $bahan->kategori->kategori }}" 
                                data-satuan="{{ $bahan->satuan }}" 
                                {{ $bahan->id == $pembelianBahans->bahan_id ? 'selected' : '' }}>
                                {{ $bahan->nama }}
                            </option>
                        @endforeach
                    </select>
                    <!-- Field hidden untuk mengirimkan data -->
                    <input type="hidden" name="bahan_id" value="{{ $pembelianBahans->bahan_id }}">
                </div>                

                <div class="mb-2">
                    <label for="kategori" class="form-label">Kategori</label>
                    <input type="text" class="form-control" id="kategori" name="kategori" value="{{ $pembelianBahans->bahan->kategori->nama ?? '' }}" readonly>
                </div>

                <div class="mb-2">
                    <label for="satuan" class="form-label">Satuan</label>
                    <input type="text" class="form-control" id="satuan" name="satuan" value="{{ $pembelianBahans->bahan->satuan->nama ?? '' }}" readonly>
                </div>
                
                <div class="mb-2">
                    <label for="pembelian" class="form-label">Pembelian</label>
                    <input type="number" step="0.01" class="form-control" id="pembelian" name="pembelian" value="{{ $pembelianBahans->pembelian }}" required>
                </div>

                <div class="mb-2">
                    <label for="tambahan_sore" class="form-label">Tambahan Sore</label>
                    <input type="number" step="0.01" class="form-control" id="tambahan_sore" name="tambahan_sore" value="{{ $pembelianBahans->tambahan_sore }}" required>
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
    document.addEventListener('DOMContentLoaded', function() {
        const bahanSelect = document.getElementById('bahan_id');
        const kategoriInput = document.getElementById('kategori');
        const satuanInput = document.getElementById('satuan');

        bahanSelect.addEventListener('change', function() {
            const selectedOption = bahanSelect.options[bahanSelect.selectedIndex];
            kategoriInput.value = selectedOption.getAttribute('data-kategori');
            satuanInput.value = selectedOption.getAttribute('data-satuan');
        });
        
        // Trigger change event on load to autofill based on selected bahan
        bahanSelect.dispatchEvent(new Event('change'));
    });
</script>
@endsection
