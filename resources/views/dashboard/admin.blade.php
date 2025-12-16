@extends('layouts.app')

@section('content')
<div class="container my-5">

    {{-- JUDUL --}}
    <div class="mb-4">
        <h3 class="fw-bold">Dashboard Admin</h3>
        <small class="text-muted">Kelola data absensi pegawai & mahasiswa</small>
    </div>

    {{-- ALERT --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @elseif (session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            {{ session('error') }}
            <button class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- FILTER TANGGAL --}}
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <h6 class="fw-semibold mb-3">Filter Absensi</h6>

            <form method="GET" action="{{ route('admin.dashboard') }}" class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label class="form-label">Dari Tanggal</label>
                    <input type="date" name="start_date" value="{{ request('start_date') }}" class="form-control">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Sampai Tanggal</label>
                    <input type="date" name="end_date" value="{{ request('end_date') }}" class="form-control">
                </div>

                <div class="col-md-4">
                    <button class="btn btn-primary w-100 rounded-pill">
                        Filter
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- INPUT ABSENSI MANUAL --}}
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <h6 class="fw-semibold mb-3">Tambah Absensi Manual</h6>

            <form action="{{ route('admin.absensi.storeManual') }}" method="POST">
                @csrf
                <div class="row g-3">

                    <div class="col-md-3">
                        <label class="form-label">User</label>
                        <select name="user_id" class="form-select" required>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}">
                                    {{ $user->name }} - {{ $user->nim_nip }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Tanggal</label>
                        <input type="date" name="tanggal" class="form-control" required>
                    </div>

                    <div class="col-md-2">
                        <label class="form-label">Jam Masuk</label>
                        <input type="time" name="jam_masuk" class="form-control">
                    </div>

                    <div class="col-md-2">
                        <label class="form-label">Jam Keluar</label>
                        <input type="time" name="jam_keluar" class="form-control">
                    </div>

                    <div class="col-md-2">
                        <label class="form-label">Keterangan</label>
                        <select name="keterangan" class="form-select">
                            <option value="">- Pilih -</option>
                            <option value="hadir">Hadir</option>
                            <option value="izin">Izin</option>
                            <option value="sakit">Sakit</option>
                            <option value="alpa">Alpa</option>
                        </select>
                    </div>

                </div>

                <div class="mt-4 text-end">
                    <button class="btn btn-primary px-4 rounded-pill">
                        Simpan Absensi
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- TABEL DATA ABSENSI --}}
    <div class="card shadow-sm border-0">
        <div class="card-body">
            <h6 class="fw-semibold mb-3">Data Absensi Pegawai / Mahasiswa</h6>

            <div class="table-responsive">
                <table class="table align-middle table-hover">
                    <thead class="table-dark text-center">
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>NIM/NIP</th>
                            <th>Email</th>
                            <th>Tanggal</th>
                            <th>Masuk</th>
                            <th>Keluar</th>
                            <th>Total</th>
                            <th>Keterangan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="text-center">
                        @forelse ($absensi as $i => $item)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td class="text-start">{{ $item->user->name }}</td>
                            <td>{{ $item->user->nim_nip }}</td>
                            <td class="text-start">{{ $item->user->email }}</td>
                            <td>{{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d M Y') }}</td>
                            <td>{{ $item->jam_masuk ?? '-' }}</td>
                            <td>{{ $item->jam_keluar ?? '-' }}</td>
                            <td>-</td>
                            <td>
                                <form action="{{ route('admin.absensi.updateKeterangan', $item->id) }}" method="POST" class="d-flex gap-1">
                                    @csrf
                                    @method('PUT')
                                    <select name="keterangan" class="form-select form-select-sm">
                                        <option value="hadir" {{ $item->keterangan=='hadir'?'selected':'' }}>H</option>
                                        <option value="izin" {{ $item->keterangan=='izin'?'selected':'' }}>I</option>
                                        <option value="sakit" {{ $item->keterangan=='sakit'?'selected':'' }}>S</option>
                                        <option value="alpa" {{ $item->keterangan=='alpa'?'selected':'' }}>A</option>
                                    </select>
                                    <button class="btn btn-sm btn-primary">Ubah</button>
                                </form>
                            </td>
                            <td>
                                <div class="d-grid gap-1">
                                    <a href="{{ route('absensi.location', $item->id) }}" class="btn btn-sm btn-success">
                                        Lokasi
                                    </a>
                                    <form action="{{ route('admin.absensi.delete', $item->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger w-100"
                                            onclick="return confirm('Hapus data ini?')">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="10" class="text-muted">Belum ada data absensi</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>

</div>
@endsection
