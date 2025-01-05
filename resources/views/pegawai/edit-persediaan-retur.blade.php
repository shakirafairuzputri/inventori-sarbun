@section('title', 'Edit Retur Bahan')
@extends('layout.sidebarp')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Edit Data Retur Bahan</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Edit Data Retur Bahan</li>
    </ol>

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-database me-1"></i>
            Edit Data
        </div>
        <div class="card-body">
            <form action="{{ route('pegawai.persediaan-retur.update', $returBahan->id) }}" method="POST">
                @csrf
                @method('PUT')
            
                <!-- Input Tanggal -->
                <div class="mb-2">
                    <label for="tanggal" class="form-label">Tanggal</label>
                    <input type="date" class="form-control" id="tanggal" name="tanggal" value="{{ $returBahan->tanggal }}" required>
                </div>                

                <!-- Input Nama Bahan (Dropdown) -->
                <div class="mb-2">
                    <label for="bahan_id" class="form-label">Bahan</label>
                    <select class="form-control" id="bahan_id" name="bahan_id_disabled" disabled>
                        <option value="" disabled>Pilih Bahan</option>
                        @foreach ($bahans as $bahan)
                            <option value="{{ $bahan->id }}" data-kategori="{{ $bahan->kategori->kategori }}" data-satuan="{{ $bahan->satuan }}" {{ $bahan->id == $returBahan->bahan_id ? 'selected' : '' }}>
                                {{ $bahan->nama }}
                            </option>
                        @endforeach
                    </select>
                    <!-- Field hidden untuk mengirimkan data -->
                    <input type="hidden" name="bahan_id" value="{{ $returBahan->bahan_id }}">
                </div>
                

                <!-- Input Kategori -->
                <div class="mb-2">
                    <label for="kategori" class="form-label">Kategori</label>
                    <input type="text" class="form-control" id="kategori" name="kategori" value="{{ $returBahan->bahan->kategori->nama ?? '' }}" readonly>
                </div>

                <!-- Input Satuan -->
                <div class="mb-2">
                    <label for="satuan" class="form-label">Satuan</label>
                    <input type="text" class="form-control" id="satuan" name="satuan" value="{{ $returBahan->bahan->satuan->nama ?? '' }}" readonly>
                </div>

                <!-- Input Retur Baik -->
                <div class="mb-2">
                    <label for="retur_baik" class="form-label">Retur Baik</label>
                    <input type="number" step="0.01" class="form-control" id="retur_baik" name="retur_baik" value="{{ $returBahan->retur_baik }}" required>
                </div>
            
                <!-- Input Retur Rusak -->
                <div class="mb-2">
                    <label for="retur_rusak" class="form-label">Retur Rusak</label>
                    <input type="number" step="0.01" class="form-control" id="retur_rusak" name="retur_rusak" value="{{ $returBahan->retur_rusak }}" required>
                </div>
                <div class="mb-2">
                    <label for="jenis_kerusakan" class="form-label">Jenis Kerusakan</label>
                    <input type="text" class="form-control" id="jenis_kerusakan" name="jenis_kerusakan" value="{{ $returBahan->jenis_kerusakan }}" readonly>
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
                <button type="submit" class="btn btn-primary">Update</button>
            </form>
        </div>
    </div>
</div>

<script>
    document.getElementById('bahan_id').addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const kategori = selectedOption.getAttribute('data-kategori');
        const satuan = selectedOption.getAttribute('data-satuan');
        
        document.getElementById('kategori').value = kategori;
        document.getElementById('satuan').value = satuan;
    });

    // Trigger autofill on page load if an option is already selected
    window.addEventListener('load', function() {
        const bahanSelect = document.getElementById('bahan_id');
        if (bahanSelect.value) {
            const selectedOption = bahanSelect.options[bahanSelect.selectedIndex];
            document.getElementById('kategori').value = selectedOption.getAttribute('data-kategori');
            document.getElementById('satuan').value = selectedOption.getAttribute('data-satuan');
        }
    });
</script>
@endsection
