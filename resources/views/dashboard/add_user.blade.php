@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h3>Tambah User Baru</h3>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('admin.user.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="name" class="form-label">Nama Lengkap</label>
            <input type="text" name="name" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="nim_nip" class="form-label">NIM / NIP</label>
            <input type="text" name="nim_nip" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" name="email" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="status" class="form-label">Status</label>
            <input type="text" name="status" class="form-control">
        </div>

        <div class="mb-3">
            <label for="role" class="form-label">Role</label>
            <select name="role" class="form-select" required>
                <option value="user">User</option>
                <option value="admin">Admin</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" name="password" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary"> + Tambah User</button>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">⬅ Kembali</a>
    </form>
</div>
@endsection
