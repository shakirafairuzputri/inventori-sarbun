@section('title', 'Request Input Data Baru') 
@extends('layout.sidebarp')

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

        {{-- Button to Add New Request --}}
        <div class="d-flex align-items-left justify-content-between mt-2 mb-0 mx-3">
             <a class="btn btn-primary ml-4" href="{{ route('pegawai.request-input.tambah') }}">Tambah Data</a>
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
                                {{-- <td>
                                    @if($request->status !== 'Selesai')
                                        <a href="{{ route('supervisor.request.edit', $request->id) }}" class="btn btn-primary btn-action">Edit</a>
                                    @else
                                        <span class="badge bg-success">Data telah diubah</span>
                                    @endif
                                </td>                                  --}}
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>                    
        </div>
    </div>
</div>
@endsection
