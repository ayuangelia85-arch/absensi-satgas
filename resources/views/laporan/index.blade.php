@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h2>Laporan Absensi</h2>
    <div class="mb-3">
        <a href="{{ route('laporan.export.pdf') }}" class="btn btn-danger">Export PDF</a>
        <a href="{{ route('laporan.export.excel') }}" class="btn btn-success">Export Excel</a>
    </div>

    <table class="table table-striped">
        <thead>
            <tr>
                <th>Nama</th>
                <th>Tanggal</th>
                <th>Status</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($laporan as $l)
            <tr>
                <td>{{ $l->user->name }}</td>
                <td>{{ $l->tanggal }}</td>
                <td>{{ $l->status }}</td>
                <td>{{ $l->keterangan }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
