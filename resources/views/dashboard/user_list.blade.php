@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h2 class="mb-4">Daftar User</h2>

    {{-- Pesan sukses/error --}}
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @elseif (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <a href="{{ route('admin.user.create') }}" class="btn mb-3" style="background-color: #e56d85; color: white">+ Tambah User</a>

    {{-- Daftar User --}}
    <div class="card shadow-sm p-4 mt-3">
        <h5>Data User Terdaftar</h5>
        <hr>

        <table class="table table-bordered table-striped align-middle">
            <thead class="table-dark">
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>NIM/NIP</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th>Role</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($users as $index => $user)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->nim_nip }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ ucfirst($user->status) }}</td>
                        <td>{{ ucfirst($user->role) }}</td>
                        <td>
                            <a href="{{ route('admin.user.edit', $user->id) }}" class="btn btn-sm btn-warning">Edit</a>
                            <form action="{{ route('admin.user.destroy', $user->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus user ini?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted">Belum ada user terdaftar.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
