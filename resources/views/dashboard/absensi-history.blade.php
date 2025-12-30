@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h3>Riwayat Absensi</h3>

    <form method="GET" class="row g-2 mb-4">
        <div class="col-md-3">
            <select name="bulan" class="form-select">
                @for($i=1; $i<=12; $i++)
                    <option value="{{ $i }}" {{ $bulan==$i ? 'selected':'' }}>
                        {{ \Carbon\Carbon::create()->month($i)->translatedFormat('F') }}
                    </option>
                @endfor
            </select>
        </div>

        <div class="col-md-3">
            <select name="tahun" class="form-select">
                @for($y=now()->year; $y>=now()->year-3; $y--)
                    <option value="{{ $y }}" {{ $tahun==$y ? 'selected':'' }}>{{ $y }}</option>
                @endfor
            </select>
        </div>

        <div class="col-md-2">
            <button class="btn btn-primary">Filter</button>
        </div>
    </form>

    <div class="card shadow-sm">
        <table class="table table-bordered mb-0">
            <thead class="table-light">
                <tr>
                    <th>Tanggal</th>
                    <th>Masuk</th>
                    <th>Keluar</th>
                    <th>Kegiatan</th>
                </tr>
            </thead>
            <tbody>
                @forelse($absensis as $a)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($a->tanggal)->format('d/m/Y') }}</td>
                        <td>{{ $a->jam_masuk ?? '-' }}</td>
                        <td>{{ $a->jam_keluar ?? '-' }}</td>
                        <td>{{ $a->kegiatan ?? '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center text-muted">Tidak ada data absensi</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-3">
        <strong>Total Jam:</strong> {{ round($totalJam,1) }} jam
    </div>
</div>
@endsection
