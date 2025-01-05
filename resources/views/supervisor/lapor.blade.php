@section('title', 'Lapor Kesalahan') 
@extends('layout.sidebar')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Lapor Kesalahan</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Lapor Kesalahan</li>
    </ol>
    
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-table me-1"></i>
            Tabel Lapor Kesalahan
        </div>

        {{-- Button to Add New Lapor Kesalahan --}}
        <div class="d-flex align-items-left justify-content-between mt-2 mb-0 mx-3">
            <a class="btn btn-primary ml-4" href="{{ route('supervisor.tambah-lapor') }}">Tambah Data</a>
        </div>

        <div class="card-body">
            {{-- Data Table --}}
            <div class="table-responsive">
                <table id="datatablesSimple" class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Pegawai</th>
                            <th>Tanggal</th>
                            <th>Kategori</th>
                            <th>Keterangan</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($lapor_kesalahans as $lapor)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $lapor->user->name ?? 'Tidak Diketahui' }}</td>
                                <td>{{ $lapor->tanggal }}</td>
                                <td>{{ $lapor->kategori }}</td>
                                <td>{{ $lapor->keterangan }}</td>
                                <td>{{ $lapor->status }}</td>
                                <td>
                                    @if($lapor->status !== 'Selesai')
                                        <a href="{{ route('supervisor.lapor.edit', $lapor->id) }}" class="btn btn-primary btn-action">Edit</a>
                                    @else
                                        <span class="badge bg-success">Data telah diubah</span>
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
