@section('title', 'Retur Bahan')
@extends('layout.sidebar')
@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Retur Bahan</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Retur</li>
    </ol>
    <div class="card mb-4">

        <div class="card-header">
            <i class="fas fa-table me-1"></i>
            Tabel Data
        </div>
        <div class="d-flex align-items-left justify-content-between mt-2 mb-0 mx-2">
            <!-- Form -->
            <form action="{{ route('supervisor.inventori-retur') }}" method="GET" class="d-flex align-items-center ms-auto">
                <div class="row g-2 align-items-center">
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
                        <span class="mx-1">—</span> 
                    </div>        
                    
                    <div class="col-auto">
                        <div class="text-muted" style="font-size: 0.85em; margin-bottom: 2px;">Tanggal Selesai</div>
                        <div class="input-group">
                            <span class="input-group-text bg-primary text-white">
                                <i class="fas fa-calendar-alt"></i>
                            </span>
                            <input type="date" class="form-control" name="tanggal_selesai" value="{{ $tanggalSelesai }}" placeholder="Tanggal Selesai">
                        </div>
                    </div>
                            
                    <div class="col-auto">
                        <div class="text-muted" style="font-size: 0.85em; margin-bottom: 2px;">Kategori</div>
                        <select class="form-select" name="kategori_id">
                            <option value="">Pilih Kategori</option>
                            @foreach($kategoris as $kategori)
                                <option value="{{ $kategori->id }}" {{ $kategori_id == $kategori->id ? 'selected' : '' }}>
                                    {{ $kategori->kategori }}
                                </option>
                            @endforeach
                        </select>
                    </div>
        
                    <div class="col-auto">
                        <div class="text-muted" style="font-size: 0.85em; margin-bottom: 2px;">Filter</div>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-filter"></i>
                        </button>
                    </div>
                </div>
            </form>            
        </div>
        
        <div class="card-body">
            <div class="table-responsive">
                <table id="datatablesSimple" class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>ID Bahan</th>
                        <th>Nama</th>
                        <th>Kategori</th>
                        <th>Satuan</th>
                        <th>Retur Baik</th>
                        <th>Retur Rusak</th>
                        <th>Pegawai (ID)</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($returBahans as $key => $retur)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $retur->tanggal }}</td>
                            <td>{{ $retur->bahan->id }}</td>
                            <td>{{ $retur->bahan->nama }}</td>
                            <td>{{ $retur->bahan->kategori->kategori ?? 'Tidak Diketahui' }}</td>
                            <td>{{ $retur->bahan->satuan }}</td>
                            <td>{{ $retur->retur_baik }}</td>
                            <td>{{ $retur->retur_rusak }}</td>
                            <td>{{ $retur->user->name . ' (' . $retur->user->id . ')' ?? 'Tidak Diketahui' }}</td> <!-- Menampilkan nama user yang menginput -->
                            <td>{{ $retur->status }}</td>
                            <td>
                                @if($retur->status !== 'Sudah Diganti')
                                    <form action="{{ route('supervisor.retur-kembalikan', $retur->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Apakah Anda yakin bahwa bahan telah diganti?');">
                                        @csrf
                                        <button type="submit" class="btn btn-success">Diganti</button>
                                    </form>
                                @else
                                    <span class="badge bg-secondary">Sudah Diganti</span>
                                @endif
                            </td>                                                      
                        </tr>
                    @endforeach
                </tbody>                
            </table>
        </div>
    </div>
</div>
@endsection