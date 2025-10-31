@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h2>Daftar Absensi</h2>
    <table class="table table-striped">
        <tr>
            <th>Nama</th>
            <th>Tanggal</th>
            <th>Jam Masuk</th>
            <th>Jam Keluar</th>
            <th>Status</th>
        </tr>
        @foreach($absensis as $index => $absensi)
        <tr>
            <td>{{ $absensi->user->name }}</td>
            <td>{{ $absensi->tanggal }}</td>
            <td>{{ $absensi->jam_masuk }}</td>
            <td>{{ $absensi->jam_keluar }}</td>
            <td>{{ $absensi->status }}</td>
        </tr>
        @endforeach
    </table>
</div>
@endsection
