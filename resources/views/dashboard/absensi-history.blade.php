@extends('layouts.app')

@section('title', 'History Absensi')

@section('content')
<div class="container my-4">

    {{-- JUDUL --}}
    <div class="mb-4">
        <h4 class="fw-bold mb-1">History Absensi Saya</h4>
        <div style="width: 60px; height: 4px; background: #ff8fa3; border-radius: 10px;"></div>
    </div>

    {{-- FILTER --}}
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body d-flex flex-wrap gap-2 align-items-center">

            <form method="GET" action="{{ route('user.absensi.history') }}" class="d-flex gap-2">

                <select name="bulan" class="form-select">
                    @for ($i = 1; $i <= 12; $i++)
                        <option value="{{ $i }}" {{ $bulan == $i ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::create()->month($i)->translatedFormat('F') }}
                        </option>
                    @endfor
                </select>

                <select name="tahun" class="form-select">
                    @for ($y = now()->year; $y >= now()->year - 5; $y--)
                        <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>
                            {{ $y }}
                        </option>
                    @endfor
                </select>

                <button class="btn text-white px-4"
                        style="background-color:#ff8fa3;">
                    Filter
                </button>

            </form>

        </div>
    </div>

    {{-- TABEL --}}
    <div class="card shadow-sm border-0">
        <div class="card-body table-responsive">

            <table class="table align-middle mb-0">
                <thead style="background-color:#ffe4ea;">
                    <tr class="text-center">
                        <th style="width:60px;">No</th>
                        <th>Tanggal</th>
                        <th>Jam Masuk</th>
                        <th>Jam Keluar</th>
                        <th>Status</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($absensis as $i => $absen)
                        <tr class="text-center table-hover">
                            <td>{{ $i + 1 }}</td>
                            <td>
                                {{ \Carbon\Carbon::parse($absen->tanggal)->translatedFormat('d F Y') }}
                            </td>
                            <td>{{ $absen->jam_masuk ?? '-' }}</td>
                            <td>{{ $absen->jam_keluar ?? 'Belum Check-Out' }}</td>
                            <td>
                                <span class="badge px-3 py-2"
                                      style="background-color:#ff8fa3;">
                                    {{ ucfirst($absen->keterangan) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">
                                Belum ada data absensi
                            </td>
                        </tr>
                    @endforelse
                </tbody>

            </table>

        </div>
    </div>

</div>
@endsection
