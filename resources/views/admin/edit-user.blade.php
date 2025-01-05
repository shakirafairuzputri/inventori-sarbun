@section('title', 'Edit Data Akun')
@extends('layout.sidebaro')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Edit Akun</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Edit Akun</li>
    </ol>

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-database me-1"></i>
            Edit Data
        </div>
        <div class="card-body">
            <form action="{{ route('admin.update-user', $user->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-2">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" class="form-control" id="name" name="name" value="{{ $user->name }}" required>
                </div>

                <div class="mb-2">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" value="{{ $user->email }}" required>
                </div>

                <div class="mb-2">
                    <label for="role" class="form-label">Role</label>
                    <select class="form-control" id="role" name="role" required>
                        <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="supervisor" {{ $user->role == 'supervisor' ? 'selected' : '' }}>Supervisor</option>
                        <option value="pegawai" {{ $user->role == 'pegawai' ? 'selected' : '' }}>Pegawai</option>
                    </select>
                </div>

                <div class="mb-2">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-control" id="status" name="status" required>
                        <option value="Aktif" {{ $user->status == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="Tidak Aktif" {{ $user->status == 'Tidak Aktif' ? 'selected' : '' }}>Tidak Aktif</option>
                    </select>
                </div>

                <div class="mb-2">
                    <label for="password" class="form-label">Password (Leave blank if unchanged)</label>
                    <input 
                        type="password" 
                        class="form-control" 
                        id="password" 
                        name="password"
                    >
                </div>
                <div class="form-check mt-2">
                    <input 
                        type="checkbox" 
                        class="form-check-input" 
                        id="showPassword" 
                        onclick="togglePassword()"
                    >
                    <label class="form-check-label" for="showPassword">Show Password</label>
                </div>
                
                <div class="mb-2">
                    <label for="password_confirmation" class="form-label">Confirm Password</label>
                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                </div>

                <button type="submit" class="btn btn-primary">Update User</button>
            </form>
        </div>
    </div>
    <script>
        function togglePassword() {
            const passwordField = document.getElementById('password');
            passwordField.type = passwordField.type === 'password' ? 'text' : 'password';
        }
    </script>
@endsection