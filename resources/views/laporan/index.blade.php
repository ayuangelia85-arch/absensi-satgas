@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h2 class="mb-4">Laporan Absensi</h2>

    {{-- FILTER --}}
    <div class="card p-3 mb-3">
        <form method="GET" action="{{ route('laporan.index') }}" class="row g-3">
            <div class="col-md-4">
                <label class="form-label">Dari Tanggal</label>
                <input type="date" name="start_date" value="{{ request('start_date') }}" class="form-control">
            </div>

            <div class="col-md-4">
                <label class="form-label">Sampai Tanggal</label>
                <input type="date" name="end_date" value="{{ request('end_date') }}" class="form-control">
            </div>

            <div class="col-md-4 d-flex align-items-end">
                <button class="btn btn-primary w-100">Filter</button>
            </div>
        </form>
    </div>

    {{-- TABEL --}}
    <div class="card shadow-sm p-4">
        <table class="table table-bordered table-striped align-middle">
            <thead class="table-dark">
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Tanggal</th>
                    <th>Jam Masuk</th>
                    <th>Jam Keluar</th>
                    <th>Total Waktu</th>
                    <th>Keterangan</th>
                </tr>
            </thead>
            <tbody>
                @forelse($laporan as $index => $l)
                    @php
                        $total = '-';
                        if ($l->jam_masuk && $l->jam_keluar) {
                            $m = \Carbon\Carbon::parse($l->jam_masuk);
                            $k = \Carbon\Carbon::parse($l->jam_keluar);
                            $d = $k->diff($m);
                            $total = $d->format('%H:%I:%S');
                        }
                    @endphp

                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $l->user->name ?? '-' }}</td>
                        <td>{{ \Carbon\Carbon::parse($l->tanggal)->translatedFormat('d F Y') }}</td>
                        <td>{{ $l->jam_masuk ?? '-' }}</td>
                        <td>{{ $l->jam_keluar ?? '-' }}</td>
                        <td>{{ $total }}</td>
                        <td>{{ ucfirst($l->keterangan ?? '-') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center">Belum ada data.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{-- EXPORT --}}
    <div class="mb-3">
        <a href="{{ route('laporan.export.pdf', request()->only('start_date','end_date')) }}" class="btn btn-danger">Export PDF</a>
        <a href="{{ route('laporan.export.excel', request()->only('start_date','end_date')) }}" class="btn btn-success">Export Excel</a>
    </div>
</div>
@endsection
