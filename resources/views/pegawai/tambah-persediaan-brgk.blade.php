@section('title', 'Tambah Barang Keluar')
@extends('layout.sidebarp')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Tambah Data Barang Keluar</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Tambah Data Barang Keluar</li>
    </ol>

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-database me-1"></i>
            Tambah Data
        </div>
        <div class="card-body">
            <form action="{{ route('pegawai.store-persediaan-brgk') }}" method="POST">
                @csrf
                <div class="mb-2">
                    <label for="tanggal">Tanggal</label>
                    <input type="date" name="tanggal" id="tanggal" class="form-control" 
                           value="{{ date('Y-m-d') }}" 
                           min="{{ date('Y-m-d') }}" 
                           max="{{ date('Y-m-d', strtotime('+2 days')) }}" 
                           required>
                </div>
                                                           
                <div class="mb-2">
                    <label for="barang_id" class="form-label">Nama Barang</label>
                    <select class="form-control" id="barang_id" name="barang_id">
                        <option></option>
                        @foreach ($barangs as $barang)
                            <option value="{{ $barang->id }}" data-kelompok="{{ $barang->kelompok }}" data-kategori="{{ $barang->kategori_brg->kategori_brg ?? 'Tidak Diketahui' }}" data-satuan="{{ $barang->satuan_brg->satuan_brg }}">{{ $barang->nama_brg }}</option>
                        @endforeach
                    </select>
                </div>
                
                <!-- Autofill Fields -->
                <div class="mb-2">
                    <label for="kelompok" class="form-label">Kelompok</label>
                    <input type="text" class="form-control" id="kelompok" name="kelompok" readonly>
                </div>
                <div class="mb-2">
                    <label for="kategori" class="form-label">Kategori</label>
                    <input type="text" class="form-control" id="kategori" name="kategori" readonly>
                </div>
                <div class="mb-2">
                    <label for="satuan" class="form-label">Satuan Unit</label>
                    <input type="text" class="form-control" id="satuan" name="satuan" readonly>
                </div>
                <div class="mb-2">
                    <label for="barang-keluar" class="form-label">Barang Keluar</label>
                    <input type="number" step="0.01" class="form-control" id="barang-keluar" name="kurang" placeholder="Masukkan Jumlah Barang Keluar" required>
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
        $('#barang_id').change(function() {
            var selectedOption = $(this).find('option:selected');
            var kelompok = selectedOption.data('kelompok');
            var kategori = selectedOption.data('kategori');
            var satuan = selectedOption.data('satuan');

            $('#kelompok').val(kelompok);
            $('#kategori').val(kategori);
            $('#satuan').val(satuan);
        });
    });
</script>
<script>
    $(document).ready(function() {
        $('#barang_id').select2({
            placeholder: 'Cari Nama Barang',
            allowClear: true
        });
    });

    document.getElementById('barang_id').addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const kelompok = selectedOption.getAttribute('data-kelompok');
        const kategori = selectedOption.getAttribute('data-kategori');
        const satuan = selectedOption.getAttribute('data-satuan');
        
        document.getElementById('kelompok').value = kelompok;
        document.getElementById('kategori').value = kategori;
        document.getElementById('satuan').value = satuan;
    });
</script>
@endsection
