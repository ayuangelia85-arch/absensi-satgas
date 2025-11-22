@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h2 class="mb-4">Dashboard User</h2>

    {{-- Sambutan --}}
    <div class="alert alert-info">
        Selamat datang, <strong>{{ $user->name }}</strong> 👋
    </div>

    {{-- Pesan sukses/error --}}
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @elseif (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    {{-- Kartu absensi hari ini --}}
    <div class="card mt-4 shadow-sm p-4">
        <h5>Status Absensi Hari Ini</h5>
        <hr>

        @if ($absensiHariIni)
            <p><strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($absensiHariIni->tanggal)->translatedFormat('d F Y') }}</p>
            <p><strong>Jam Masuk:</strong> {{ $absensiHariIni->jam_masuk ?? '-' }}</p>
            <p><strong>Jam Keluar:</strong> {{ $absensiHariIni->jam_keluar ?? '-' }}</p>
            <p><strong>Keterangan:</strong> {{ ucfirst($absensiHariIni->keterangan ?? '-') }}</p>
            <p><strong>Durasi:</strong> {{ $absensiHariIni->durasi_jam ? $absensiHariIni->durasi_jam . ' jam' : '-' }}</p>

            {{-- Jika belum check-out --}}
            @if (!$absensiHariIni->jam_keluar)
                <form action="{{ route('user.absensi.keluar') }}" method="POST" id="checkoutForm" style="display:inline;">
                    @csrf
                    <input type="hidden" name="latitude" id="latitude_out">
                    <input type="hidden" name="longitude" id="longitude_out">
                    <button type="submit" class="btn btn-warning">Check Out</button>
                </form>
            @else
                <div class="text-success mt-2"><strong>Sudah Check-Out</strong></div>
            @endif

        @else
            <p class="text-muted">Belum melakukan absensi hari ini.</p>

            {{-- Form Check-In --}}
            <form action="{{ route('user.absensi.masuk') }}" method="POST" id="checkinForm" style="display:inline;">
                @csrf
                <input type="hidden" name="latitude" id="latitude_in">
                <input type="hidden" name="longitude" id="longitude_in">
                <button type="submit" class="btn btn-primary">Check In</button>
            </form>
        @endif
    </div>
    @if ($absensiHariIni)
        <a href="{{ route('user.absensi.location', $absensiHariIni->id) }}" 
        class="btn btn-info mt-2">
            Lihat Lokasi Check-In
        </a>
    @endif


    {{-- Statistik Bulan Ini --}}
    <div class="card mt-4 shadow-sm p-4">
        <h5>Rekap Bulan Ini</h5>
        <hr>
        <p><strong>Total Jam Hadir:</strong> {{ $totalJam ?? 0 }} jam</p>
    </div>
</div>

{{-- Script ambil koordinat lokasi --}}
<script>
document.addEventListener("DOMContentLoaded", function () {
    if (navigator.geolocation) {

        navigator.geolocation.getCurrentPosition(function (position) {

            // FORM CHECK-IN
            if (document.getElementById("latitude_in")) {
                document.getElementById("latitude_in").value = position.coords.latitude;
                document.getElementById("longitude_in").value = position.coords.longitude;
            }

            // FORM CHECK-OUT
            if (document.getElementById("latitude_out")) {
                document.getElementById("latitude_out").value = position.coords.latitude;
                document.getElementById("longitude_out").value = position.coords.longitude;
            }

        }, function () {
            alert("Gagal mengambil lokasi. Pastikan GPS aktif!");
        });

    } else {
        alert("Browser tidak mendukung geolocation.");
    }
});
</script>
@endsection
