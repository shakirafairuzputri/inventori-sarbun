@section('title', 'Lapor Kesalahan') 
@extends('layout.sidebarp')
@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Lapor Kesalahan</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Lapor Kesalahan</li>
    </ol>
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-table me-1"></i>
            Tabel Data
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="datatablesSimple" class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>Kategori</th>
                            <th>Keterangan</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($laporans as $lapor)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ \Carbon\Carbon::parse($lapor->tanggal)->format('d/m/Y') }}</td>
                                <td>{{ $lapor->kategori }}</td>
                                <td>{{ $lapor->keterangan }}</td>
                                <td>{{ $lapor->status }}</td>
                                <td>
                                    @if($lapor->status == 'Diproses')
                                        <form action="{{ route('pegawai.lapor-kesalahan.update-status', $lapor->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menyelesaikan laporan ini?');">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="btn btn-success">Selesai</button>
                                        </form>
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
@endsection
