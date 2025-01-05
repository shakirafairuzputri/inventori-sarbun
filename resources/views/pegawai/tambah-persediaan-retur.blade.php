@section('title', 'Tambah Retur Bahan')
@extends('layout.sidebarp')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Tambah Data Retur Bahan</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Tambah Data Retur Bahan</li>
    </ol>

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-database me-1"></i>
            Tambah Data
        </div>
        <div class="card-body">
            <form action="{{ route('pegawai.persediaan-retur.store') }}" method="POST">
                @csrf
                <div class="mb-2">
                    <label for="tanggal">Tanggal</label>
                    <input type="date" name="tanggal" class="form-control" value="{{ old('tanggal', date('Y-m-d')) }}" required>
                </div>
                <div class="mb-2">
                    <label for="bahan_id">Bahan</label>
                    <select name="bahan_id" class="form-control" id="bahan_id" required>
                        <option></option>
                        @foreach($bahans as $bahan)
                            <option value="{{ $bahan->id }}" data-kategori="{{ $bahan->kategori->kategori }}" data-satuan="{{ $bahan->satuan }}">
                                {{ $bahan->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="mb-2">
                    <label for="kategori">Kategori</label>
                    <input type="text" name="kategori" id="kategori" class="form-control" readonly>
                </div>
                <div class="mb-2">
                    <label for="satuan">Satuan</label>
                    <input type="text" name="satuan" id="satuan" class="form-control" readonly>
                </div>
                <div class="mb-2">
                    <label for="retur_baik">Retur Baik</label>
                    <input type="number" step="0.01" name="retur_baik" class="form-control">
                </div>
                <div class="mb-2">
                    <label for="retur_rusak">Retur Rusak</label>
                    <input type="number" step="0.01" name="retur_rusak" class="form-control">
                </div>

                <div class="mb-2">
                    <label for="jenis_kerusakan">Jenis Kerusakan</label>
                    <select name="jenis_kerusakan" class="form-control" id="jenis_kerusakan" required>
                        <option value="" disabled selected>Pilih Jenis Kerusakan</option>
                        <option value="Kadaluarsa">Kadaluarsa</option>
                        <option value="Rusak">Rusak</option>
                    </select>
                </div>

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            
                <button type="submit" class="btn btn-primary">Simpan</button>
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
