@section('title', 'Barang Keluar')
@extends('layout.sidebar')
@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Data Barang Keluar</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Data Barang Keluar</li>
    </ol>
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-table me-1"></i>
            Tabel Data
        </div>
        <div class="d-flex justify-content-end mt-2 mb-0 mx-3">
            <form action="{{ route('supervisor.inventori-brgk') }}" method="GET" class="w-100">
                <div class="row justify-content-end">
                    <!-- Baris Tanggal -->
                    <div class="col-auto">
                        <div class="text-muted" style="font-size: 0.85em; margin-bottom: 2px;">Tanggal Mulai</div>
                        <div class="input-group">
                            <span class="input-group-text bg-primary text-white">
                                <i class="fas fa-calendar-alt"></i>
                            </span>
                            <input type="date" class="form-control" name="tanggal_mulai" value="{{ $tanggalMulai }}" placeholder="Tanggal Mulai">
                        </div>
                    </div>
                    <div class="col-auto align-self-center">
                        <span class="mx-2">—</span>
                    </div>
                    <div class="col-auto me-2">
                        <div class="text-muted" style="font-size: 0.85em; margin-bottom: 2px;">Tanggal Selesai</div>
                        <div class="input-group">
                            <span class="input-group-text bg-primary text-white">
                                <i class="fas fa-calendar-alt"></i>
                            </span>
                            <input type="date" class="form-control" name="tanggal_selesai" value="{{ $tanggalSelesai }}" placeholder="Tanggal Selesai">
                        </div>
                    </div>
                </div>
                
                <div class="row justify-content-end mt-1">
                    <!-- Baris Kelompok dan Kategori -->
                    <div class="col-auto">
                        <div class="text-muted" style="font-size: 0.85em; margin-bottom: 2px;">Kelompok</div>
                        <div class="input-group">
                            <select class="form-select" name="kelompok" id="kelompok">
                                <option value="" {{ $kelompok == null ? 'selected' : '' }}>Pilih Kelompok</option>
                                <option value="Bahan" {{ $kelompok == 'Bahan' ? 'selected' : '' }}>Bahan</option>
                                <option value="Barang" {{ $kelompok == 'Barang' ? 'selected' : '' }}>Barang</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-auto me-2">
                        <div class="text-muted" style="font-size: 0.85em; margin-bottom: 2px;">Kategori</div>
                        <div class="input-group">
                            <select class="form-select" name="kategori_id">
                                <option value="">Pilih Kategori</option>
                                @foreach($kategoris as $kategori_brg)
                                    <option value="{{ $kategori_brg->id }}" {{ $kategori_id == $kategori_brg->id ? 'selected' : '' }}>
                                        {{ $kategori_brg->kategori_brg }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-auto align-self-end">
                        <button type="submit" class="btn btn-primary ml-2">
                            <i class="fas fa-filter"></i> Filter
                        </button>
                    </div>
                </div>
            </form>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="datatablesSimple" class="table table-striped table-bordered">
                {{-- <tfoot>
                    <tr>
                        <th>No</th>
                        <th>Nama Barang</th>
                        <th>Kategori</th>
                        <th>Satuan Unit</th>
                        <th>Stok</th>
                        <th>Kurang</th>
                        <th>Sisa</th>
                    </tr>
                </tfoot> --}}
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>ID</th>
                        <th>Nama Bahan/Barang</th>
                        <th>Kelompok</th>
                        <th>Kategori</th>
                        <th>Satuan Unit</th>
                        <th>Kurang</th>
                        <th>Pegawai (ID)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($persediaan_barangs as $key => $keluar)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $keluar->tanggal }}</td>
                            <td>{{ $keluar->barang->id }}</td>
                            <td>{{ $keluar->barang->nama_brg }}</td>
                            <td>{{ $keluar->barang->kelompok }}</td>
                            <td>{{ $keluar->barang->kategori_brg->kategori_brg ?? 'Tidak Diketahui' }}</td>
                            <td>{{ $keluar->barang->satuan_brg->satuan_brg }}</td>
                            <td>{{ $keluar->kurang ?? 0 }}</td>
                            <td>{{ $keluar->userKeluar ? $keluar->userKeluar->name . ' (' . $keluar->userKeluar->id . ')' : 'Tidak Diketahui' }}</td> <!-- Barang Keluar -->
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
