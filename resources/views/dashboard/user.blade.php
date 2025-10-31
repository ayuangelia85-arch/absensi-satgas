@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h2 class="mb-4">Dashboard User</h2>

    <div class="alert alert-info">
        Selamat datang, <strong>{{ $user->name }}</strong> 👋
    </div>

    {{-- Alert pesan sukses atau error --}}
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @elseif (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="card mt-4 shadow-sm p-4">
        <h5>Status Absensi Hari Ini</h5>
        <hr>

        @if ($absensiHariIni)
            <p><strong>Tanggal:</strong> {{ $absensiHariIni->tanggal }}</p>
            <p><strong>Jam Masuk:</strong> {{ $absensiHariIni->jam_masuk ?? '-' }}</p>
            <p><strong>Jam Keluar:</strong> {{ $absensiHariIni->jam_keluar ?? '-' }}</p>
            <p><strong>Status:</strong> {{ ucfirst($absensiHariIni->status ?? '-') }}</p>

            {{-- Jika belum check-out --}}
            @if (!$absensiHariIni->jam_keluar)
                <form action="{{ route('user.absensi.keluar') }}" method="POST" style="display:inline;">
                    @csrf
                    <button type="submit" class="btn btn-warning">Check Out</button>
                </form>
            @else
                <div class="text-success mt-2"><strong>Sudah Check-Out</strong></div>
            @endif
        @else
            <p class="text-muted">Belum melakukan absensi hari ini.</p>

            <form action="{{ route('user.absensi.masuk') }}" method="POST" style="display:inline;">
                @csrf
                <button type="submit" class="btn btn-primary">Check In</button>
            </form>
        @endif
    </div>
</div>
@endsection
