@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h2 class="mb-4">Laporan Rekap Bulanan</h2>

    {{-- FILTER BULAN & TAHUN --}}
    <div class="card p-3 mb-3">
        <form method="GET" action="{{ route('laporan.index') }}" class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Bulan</label>
                <select name="bulan" class="form-control">
                    @foreach(range(1,12) as $b)
                        <option value="{{ $b }}" {{ request('bulan', $selectedBulan) == $b ? 'selected' : '' }}>
                            {{ DateTime::createFromFormat('!m', $b)->format('F') }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6">
                <label class="form-label">Tahun</label>
                <select name="tahun" class="form-control">
                    @foreach($tahunList as $t)
                        <option value="{{ $t }}" {{ request('tahun', $selectedTahun) == $t ? 'selected' : '' }}>
                            {{ $t }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-12 d-flex align-items-end">
                <button class="btn btn-primary w-100">Filter</button>
            </div>
        </form>
    </div>

    {{-- TABEL REKAP BULANAN --}}
    <div class="card shadow-sm p-4">
        <table class="table table-bordered table-striped align-middle">
            <thead class="table-dark">
                <tr>
                    <th>No</th>
                    <th>Nama User</th>
                    <th>Bulan</th>
                    <th>Total Waktu (Jam)</th>
                    <th>Keterangan</th>
                </tr>
            </thead>

            <tbody>
                @forelse($rekap as $index => $item)
                    @php
                        // ambil bulan & tahun valid
                        $bulan = (int) request('bulan', $selectedBulan);
                        $tahun = (int) request('tahun', $selectedTahun);

                        $namaBulan = \Carbon\Carbon::createFromDate($tahun, $bulan, 1)
                                    ->translatedFormat('F');

                        $totalJam = number_format($item->total_jam ?? 0, 1);
                        $status = ($item->total_jam ?? 0) >= $targetJam ? 'Terpenuhi' : 'Tidak Terpenuhi';
                    @endphp

                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $item->user->name ?? '-' }}</td>
                        <td>{{ $namaBulan }} {{ $tahun }}</td>
                        <td>{{ $totalJam }} jam</td>
                        <td>{{ $status }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center">Tidak ada data.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- EXPORT --}}
    <div class="mb-3">
        <a href="{{ route('laporan.export.pdf', request()->only('bulan','tahun')) }}" class="btn btn-danger">
            Export PDF
        </a>

        <a href="{{ route('laporan.export.excel', request()->only('bulan','tahun')) }}" class="btn btn-success">
            Export Excel
        </a>
    </div>
</div>
@endsection
