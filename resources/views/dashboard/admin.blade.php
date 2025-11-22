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
