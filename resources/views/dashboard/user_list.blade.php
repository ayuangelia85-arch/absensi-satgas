@extends('layouts.app')

@section('content')
<div class="container mt-4">

    {{-- HEADER --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
        <h2 class="mb-3 mb-md-0">Daftar User</h2>

        <a href="{{ route('admin.user.create') }}"
           class="btn"
           style="background-color: #e56d85; color: white">
            + Tambah User
        </a>
    </div>

    {{-- ALERT --}}
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @elseif (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    {{-- CARD --}}
    <div class="card shadow-sm mt-3">
        <div class="card-body">
            <h5 class="mb-3">Data User Terdaftar</h5>

            {{-- TABLE RESPONSIVE --}}
            <div class="table-responsive">
                <table class="table table-bordered table-striped align-middle mb-0">
                    <thead class="table-dark text-center">
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>NIM/NIP</th>
                            <th class="d-none d-md-table-cell">Email</th>
                            <th class="d-none d-md-table-cell">Status</th>
                            <th>Role</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($users as $index => $user)
                        <tr>
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->nim_nip }}</td>

                            {{-- DISEMBUNYIKAN DI MOBILE --}}
                            <td class="d-none d-md-table-cell">{{ $user->email }}</td>
                            <td class="d-none d-md-table-cell fw-semibold">
                                {{ ucfirst($user->status) }}
                            </td>

                            <td class="fw-semibold">
                                {{ ucfirst($user->role) }}
                            </td>

                            {{-- AKSI --}}
                            <td>
                                <div class="d-flex flex-column flex-md-row gap-1">
                                    <a href="{{ route('admin.user.edit', $user->id) }}"
                                       class="btn btn-sm btn-warning">
                                        Edit
                                    </a>

                                    <form action="{{ route('admin.user.destroy', $user->id) }}"
                                          method="POST"
                                          onsubmit="return confirm('Yakin hapus user ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger w-100">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted">
                                Belum ada user terdaftar.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>
@endsection
