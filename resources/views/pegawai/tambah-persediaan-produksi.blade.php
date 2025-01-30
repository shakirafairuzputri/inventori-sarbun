@extends('layout.sidebarp')

@section('title', 'Tambah Produksi Bahan')

@section('content')
<div class="container mt-4">
    <h1 class="mb-4">Tambah Data Produksi Bahan</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Tambah Data Produksi Bahan</li>
    </ol>

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-database me-1"></i>
            Tambah Data
        </div>
        <div class="card-body">
            <form action="{{ route('pegawai.store-persediaan-produksi') }}" method="POST">
                @csrf <!-- Token CSRF untuk keamanan -->
            
                <!-- Input Tanggal -->
                <div class="mb-2">
                    <label for="tanggal">Tanggal</label>
                    <input type="date" name="tanggal" id="tanggal" class="form-control" value="{{ date('Y-m-d') }}"
                        min="{{ date('Y-m-d', strtotime('-1 days')) }}" max="{{ date('Y-m-d', strtotime('+1 days')) }}" required>
                </div>
                
                
                <!-- Input Nama Bahan (Dropdown) -->
                <div class="mb-2">
                    <label for="bahan_id" class="form-label">Nama Bahan</label>
                    <select class="form-control" id="bahan_id" name="bahan_id" required>
                        <option></option>
                        @foreach ($bahans as $bahan)
                            <option value="{{ $bahan->id }}" data-kategori="{{ $bahan->kategori->kategori }}" data-satuan="{{ $bahan->satuan }}">
                                {{ $bahan->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Input Kategori -->
                <div class="mb-2">
                    <label for="kategori" class="form-label">Kategori</label>
                    <input type="text" class="form-control" id="kategori" name="kategori" readonly>
                </div>

                <!-- Input Satuan -->
                <div class="mb-2">
                    <label for="satuan" class="form-label">Satuan</label>
                    <input type="text" class="form-control" id="satuan" name="satuan" readonly>
                </div>
                
                <!-- Input Produksi Baik -->
                <div class="mb-2">
                    <label for="produksi_baik" class="form-label">Produksi Baik</label>
                    <input type="number" step="0.01" class="form-control" id="produksi_baik" name="produksi_baik" placeholder="Masukkan Jumlah Produksi Baik">
                </div>

                <!-- Input Produksi Paket -->
                <div class="mb-2">
                    <label for="produksi_paket" class="form-label">Produksi Paket</label>
                    <input type="number" step="0.01" class="form-control" id="produksi_paket" name="produksi_paket" placeholder="Masukkan Jumlah Produksi Paket">
                </div>

                <!-- Input Produksi Rusak -->
                <div class="mb-2">
                    <label for="produksi_rusak" class="form-label">Produksi Rusak</label>
                    <input type="number" step="0.01" class="form-control" id="produksi_rusak" name="produksi_rusak" placeholder="Masukkan Jumlah Produksi Rusak">
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

<style>
    .select2-container--default .select2-selection--single {
        height: calc(2.25rem + 2px);
        border: 1px solid #ced4da;
        border-radius: 0.25rem;
        display: flex; 
        align-items: center; 
    }
    
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        width: 100%;
    }
</style>

<script>
    $(document).ready(function() {
        $('#bahan_id').change(function() {
            var selectedOption = $(this).find('option:selected');
            var kategori = selectedOption.data('kategori');
            var satuan = selectedOption.data('satuan');

            $('#kategori').val(kategori);
            $('#satuan').val(satuan);
        });
    });
</script>
<script>
    $(document).ready(function() {
        $('#bahan_id').select2({
            placeholder: 'Cari Nama Bahan',
            allowClear: true
        });
    });

    document.getElementById('bahan_id').addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const kategori = selectedOption.getAttribute('data-kategori');
        const satuan = selectedOption.getAttribute('data-satuan');
        
        document.getElementById('kategori').value = kategori;
        document.getElementById('satuan').value = satuan;
    });
</script>
@endsection
