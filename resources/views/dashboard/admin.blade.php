@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h2 class="mb-4">Dashboard Admin</h2>

    {{-- Pesan sukses/error --}}
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @elseif (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    {{-- FILTER RANGE TANGGAL --}}
    <div class="card p-3 mb-3">
        <form method="GET" action="{{ route('admin.dashboard') }}" class="row g-3">

            <div class="col-md-4">
                <label class="form-label">Dari Tanggal</label>
                <input type="date" 
                    name="start_date" 
                    value="{{ request('start_date') }}" 
                    class="form-control">
            </div>

            <div class="col-md-4">
                <label class="form-label">Sampai Tanggal</label>
                <input type="date" 
                    name="end_date" 
                    value="{{ request('end_date') }}" 
                    class="form-control">
            </div>

            <div class="col-md-4 d-flex align-items-end">
                <button class="btn btn-primary w-100">
                    Filter
                </button>
            </div>

        </form>
    </div>

        {{-- FORM INPUT ABSENSI MANUAL --}}
    <div class="card p-4 mb-4 shadow-sm">
        <h5 class="mb-3">Tambah Absensi Manual</h5>

        <form action="{{ route('admin.absensi.storeManual') }}" method="POST">
            @csrf

            <div class="row">

                <div class="col-md-3 mb-3">
                    <label>User</label>
                    <select name="user_id" class="form-control" required>
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }} - {{ $user->nim_nip }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3 mb-3">
                    <label>Tanggal</label>
                    <input type="date" name="tanggal" class="form-control" required>
                </div>

                <div class="col-md-3 mb-3">
                    <label>Jam Masuk</label>
                    <input type="time" name="jam_masuk" class="form-control">
                </div>

                <div class="col-md-3 mb-3">
                    <label>Jam Keluar</label>
                    <input type="time" name="jam_keluar" class="form-control">
                </div>

                <div class="col-md-3 mb-3">
                    <label>Keterangan</label>
                    <select name="keterangan" class="form-control">
                        <option value="">-- Pilih --</option>
                        <option value="hadir">Hadir</option>
                        <option value="izin">Izin</option>
                        <option value="sakit">Sakit</option>
                        <option value="alpa">Alpa</option>
                    </select>
                </div>

            </div>

            <button class="btn btn-primary mt-2">Simpan Absensi</button>
        </form>
    </div>


    {{-- Tabel Data Absensi --}}
    <div class="card shadow-sm p-4 mt-3">
        <h5>Data Absensi Pegawai / Mahasiswa</h5>

        <table class="table table-bordered table-striped align-middle">
            <thead class="table-dark">
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>NIM/NIP</th>
                    <th>Email</th>
                    <th>Tanggal</th>
                    <th>Jam Masuk</th>
                    <th>Jam Keluar</th>
                    <th>Total Waktu</th>
                    <th>Keterangan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($absensi as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $item->user->name }}</td>
                        <td>{{ $item->user->nim_nip }}</td>
                        <td>{{ $item->user->email }}</td>
                        <td>{{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d F Y') }}</td>
                        <td>{{ $item->jam_masuk ?? '-' }}</td>
                        <td>{{ $item->jam_keluar ?? '-' }}</td>
                        <td>
                            @if ($item->jam_masuk && $item->jam_keluar)
                                @php
                                    $masuk = \Carbon\Carbon::parse($item->jam_masuk);
                                    $keluar = \Carbon\Carbon::parse($item->jam_keluar);
                                    $durasi = $keluar->diff($masuk);
                                @endphp

                                {{ $durasi->h }} jam {{ $durasi->i }} menit
                            @else
                                -
                            @endif
                        </td>
                        <td>
                            <form action="{{ route('admin.absensi.updateKeterangan', $item->id) }}" method="POST" class="d-flex">
                                @csrf
                                @method('PUT')
                                <select name="keterangan" class="form-select form-select-sm me-2">
                                    <option value="">-</option>
                                    <option value="hadir" {{ $item->keterangan == 'hadir' ? 'selected' : '' }}>Hadir</option>
                                    <option value="izin" {{ $item->keterangan == 'izin' ? 'selected' : '' }}>Izin</option>
                                    <option value="sakit" {{ $item->keterangan == 'sakit' ? 'selected' : '' }}>Sakit</option>
                                    <option value="alpa" {{ $item->keterangan == 'alpa' ? 'selected' : '' }}>Alpa</option>
                                </select>
                                <button type="submit" class="btn btn-sm btn-primary">Ubah</button>
                            </form>
                        </td>
                        <td>
                            <form action="{{ route('admin.absensi.delete', $item->id) }}" method="POST" onsubmit="return confirm('Yakin hapus data ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                            </form>
                            <a href="{{ route('absensi.location', $item->id) }}" 
                                class="btn btn-sm btn-success">
                                Lihat Lokasi
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center text-muted">Belum ada data absensi.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
