@section('title', 'Edit Produksi Bahan')
@extends('layout.sidebarp')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Edit Data Produksi Bahan</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Edit Data Produksi Bahan</li>
    </ol>

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-database me-1"></i>
            Edit Data
        </div>
        <div class="card-body">
            <form action="{{ route('pegawai.persediaan-produksi.update', $produksiBahan->id) }}" method="POST">
                @csrf
                @method('PUT')
            
                <!-- Tanggal -->
                <div class="mb-2">
                    <label for="tanggal" class="form-label">Tanggal</label>
                    <input type="date" class="form-control" id="tanggal" name="tanggal" value="{{ old('tanggal', $produksiBahan->tanggal) }}" required>
                </div>
                
                <!-- Nama Bahan -->
                <div class="mb-2">
                    <label for="bahan_id" class="form-label">Nama Bahan</label>
                    <select class="form-control" id="bahan_id" name="bahan_id" disabled>
                        <option value="" disabled>Pilih Nama Bahan</option>
                        @foreach ($bahans as $bahan)
                            <option value="{{ $bahan->id }}" 
                                    data-kategori="{{ $bahan->kategori->kategori ?? '' }}" 
                                    data-satuan="{{ $bahan->satuan ?? '' }}" 
                                    {{ $bahan->id == $bahanId ? 'selected' : '' }}>
                                {{ $bahan->nama }}
                            </option>
                        @endforeach
                    </select>
                    <!-- Field hidden untuk mengirimkan bahan_id -->
                    <input type="hidden" name="bahan_id" value="{{ $bahanId }}">                    
                </div>                

                <!-- Kategori (Autofill) -->
                <div class="mb-2">
                    <label for="kategori" class="form-label">Kategori</label>
                    <input type="text" class="form-control" id="kategori" name="kategori" value="{{ $produksiBahan->bahan->kategori->kategori ?? '' }}" readonly>
                </div>

                <!-- Satuan (Autofill) -->
                <div class="mb-2">
                    <label for="satuan" class="form-label">Satuan</label>
                    <input type="text" class="form-control" id="satuan" name="satuan" value="{{ $produksiBahan->bahan->satuan ?? '' }}" readonly>
                </div>

                <!-- Produksi Baik -->
                <div class="mb-2">
                    <label for="produksi_baik" class="form-label">Produksi Baik</label>
                    <input type="number" step="0.01" class="form-control" id="produksi_baik" name="produksi_baik" value="{{ $produksiBahan->produksi_baik }}" required>
                </div>
                
                <!-- Produksi Paket -->
                <div class="mb-2">
                    <label for="produksi_paket" class="form-label">Produksi Paket</label>
                    <input type="number" step="0.01" class="form-control" id="produksi_paket" name="produksi_paket" value="{{ $produksiBahan->produksi_paket }}" required>
                </div>
                
                <!-- Produksi Rusak -->
                <div class="mb-2">
                    <label for="produksi_rusak" class="form-label">Produksi Rusak</label>
                    <input type="number" step="0.01" class="form-control" id="produksi_rusak" name="produksi_rusak" value="{{ $produksiBahan->produksi_rusak }}" required>
                </div>

                <!-- Success & Error Messages -->
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
