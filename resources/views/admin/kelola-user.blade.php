@section('title', 'Kelola Akun')
@extends('layout.sidebaro')
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
    <h1 class="mt-4">Kelola Akun</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Kelola Akun</li>
    </ol>
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-table me-1"></i>
            Tabel Data
        </div>
        <div class="d-flex align-items-left justify-content-between mt-2 mb-0 mx-3">
            <a class="btn btn-primary ml-4" href="{{ route('admin.tambah-user') }}">Tambah Data</a>
        </div>
        <form action="{{ route('admin.kelola-user') }}" method="GET" class="d-flex">
            <div class="input-group mx-2 ms-auto" style="width: 215px;">
                <select class="form-select" name="status">
                    <option value="" {{ $status == null ? 'selected' : '' }}>Pilih Status</option>
                    <option value="Aktif" {{ $status == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="Tidak Aktif" {{ $status == 'Tidak Aktif' ? 'selected' : '' }}>Tidak Aktif</option>
                </select>
                <button type="submit" class="btn btn-primary me-2">
                    <i class="fas fa-filter"></i>
                </button> 
            </div> 
        </form>
        <div class="card-body">
            <div class="table-responsive">
                <table id="datatablesSimple" class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Status</th>
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
                    @forelse($users as $user)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ ucfirst($user->role) }}</td> <!-- ucfirst capitalizes the first letter -->
                            <td>{{ $user->status }}</td>
                            <td>
                                <a href="{{ route('admin.edit-user', $user->id) }}" class="btn btn-primary btn-action">Edit</a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center">Tidak ada data</td>
                        </tr>
                    @endforelse
            </table>
        </div>
    </div>
</div>
@endsection
