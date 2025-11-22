@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="card shadow-sm p-4">
        <h3 class="mb-4">Edit User</h3>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form action="{{ route('admin.user.update', $user->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="name" class="form-label">Nama Lengkap</label>
                <input type="text" name="name" value="{{ $user->name }}" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="nim_nip" class="form-label">NIM / NIP</label>
                <input type="text" name="nim_nip" value="{{ $user->nim_nip }}" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" name="email" value="{{ $user->email }}" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="status" class="form-label">Status</label>
                <select name="status" class="form-select" required>
                    <option value="">-- Pilih Status --</option>
                    <option value="mahasiswa" {{ $user->status == 'mahasiswa' ? 'selected' : '' }}>Mahasiswa</option>
                    <option value="dosen" {{ $user->status == 'dosen' ? 'selected' : '' }}>Dosen</option>
                    <option value="magang" {{ $user->status == 'magang' ? 'selected' : '' }}>Magang</option>
                    <option value="staff" {{ $user->status == 'staff' ? 'selected' : '' }}>Staff</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="role" class="form-label">Role</label>
                <select name="role" class="form-select">
                    <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="user" {{ $user->role == 'user' ? 'selected' : '' }}>User</option>
                </select>
            </div>

            <div class="d-flex justify-content-between mt-4">
                <a href="{{ route('admin.user.index') }}" class="btn btn-secondary">⬅ Kembali</a>
                <button type="submit" class="btn" style="background-color: #e04582; color: white;">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>
@endsection
