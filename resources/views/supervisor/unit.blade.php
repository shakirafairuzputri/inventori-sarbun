@section('title', 'Satuan Unit')
@extends('layout.sidebar')
@section('content')
@if (session('success'))
<div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="successModalLabel">Sukses</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                {{ session('success') }}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">OK</button>
            </div>
        </div>
    </div>
</div>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        var successModal = new bootstrap.Modal(document.getElementById('successModal'));
        successModal.show();
    });
</script>
@endif
<div class="container-fluid px-4">
    <h1 class="mt-4">Data Satuan Unit</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Data Satuan Unit</li>
    </ol>
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-table me-1"></i>
            Tabel Data
        </div>
        <div class="d-flex align-items-left justify-content-between mt-2 mb-0 mx-3">
            <a class="btn btn-primary ml-4" href="{{ route('supervisor.tambah-unit') }}">Tambah Data</a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="datatablesSimple" class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Unit</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                {{-- <tfoot>
                    <tr>
                        <th>No</th>
                        <th>Unit</th>
                        <th>Aksi</th>
                    </tr>
                </tfoot> --}}
                <tbody>
                    @foreach ($satuans as $key => $satuan)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $satuan->satuan_brg }}</td>
                            <td>
                                <form action="{{ route('supervisor.destroy-satuan', $satuan->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">Hapus</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection