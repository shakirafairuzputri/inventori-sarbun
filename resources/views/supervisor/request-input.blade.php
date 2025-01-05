@section('title', 'Request Input Data Baru') 
@extends('layout.sidebar')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Request Input Data Baru</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Request Input Data Baru</li>
    </ol>
    
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-table me-1"></i>
            Tabel Request Input Data Baru
        </div>

        <div class="card-body">
            {{-- Data Table --}}
            <div class="table-responsive">
                <table id="datatablesSimple" class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Pegawai</th>
                            <th>Kelompok</th>
                            <th>Nama Bahan/Barang</th>
                            <th>Deskripsi</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($request_input as $request)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $request->user->name ?? 'Tidak Diketahui' }}</td>
                                <td>{{ $request->kelompok }}</td>
                                <td>{{ $request->nama }}</td>
                                <td>{{ $request->deskripsi }}</td>
                                <td>{{ $request->status }}</td>
                                <td>
                                    @if($request->status !== 'Approved')
                                        @if($request->kelompok == 'Bahan Utama')
                                            <a href="{{ route('supervisor.tambah-daftar-bhn', ['nama' => $request->nama]) }}" class="btn btn-primary btn-sm">
                                                <i class="fas fa-plus-circle"></i> Tambah Bahan
                                            </a>                                  
                                        @elseif($request->kelompok == 'Bahan Lain' && 'Barang')
                                            <a href="{{ route('supervisor.tambah-daftar-brg', ['nama' => $request->nama]) }}" class="btn btn-primary btn-sm">
                                                <i class="fas fa-plus-circle"></i> Tambah Barang
                                            </a>
                                        @endif
                                        <form action="{{ route('supervisor.approve-request', ['request' => $request->id]) }}" method="POST" style="display:inline;" onsubmit="return confirm('Apakah Anda yakin telah menambahkan data?');">
                                            @csrf
                                            @method('POST')
                                            <button type="submit" class="btn btn-success btn-sm" title="Approved">
                                                <i class="fas fa-check-circle"></i>
                                            </button>
                                        </form>
                                    @else
                                        <span class="badge bg-success">Data Telah Ditambah</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>                    
        </div>
    </div>
</div>
@endsection
