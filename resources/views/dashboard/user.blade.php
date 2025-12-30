@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h2 class="mb-4">Dashboard User</h2>

    {{-- Sambutan --}}
    <div class="alert alert-info">
        Selamat datang, <strong>{{ $user->name }}</strong> 👋
    </div>

    {{-- Tombol lihat history --}}
    <div class="d-flex justify-content-end mb-3">
        <a href="{{ route('user.absensi.history') }}" class="btn btn-outline-secondary btn-sm">
            📄 Lihat Riwayat Absensi
        </a>
    </div>

    {{-- Pesan --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @elseif(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    {{-- Kartu absensi --}}
    <div class="card mt-4 shadow-sm p-4">
        <h5>Status Absensi Hari Ini</h5>
        <hr>

        {{-- BELUM CHECK-IN --}}
        @if(!$status['sudah_checkin'])
            <p class="text-muted">Kamu belum melakukan absensi hari ini.</p>

            <form action="{{ route('user.absensi.masuk') }}" method="POST">
                @csrf
                <input type="hidden" name="latitude" id="latitude_in">
                <input type="hidden" name="longitude" id="longitude_in">
                <button type="submit" class="btn btn-primary">Check In</button>
            </form>
        @endif

        {{-- SUDAH CHECK-IN --}}
        @if($status['sudah_checkin'])
            <p><strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($absensiHariIni->tanggal)->translatedFormat('d F Y') }}</p>
            <p><strong>Jam Masuk:</strong> {{ $absensiHariIni->jam_masuk }}</p>
            <p><strong>Jam Keluar:</strong> {{ $absensiHariIni->jam_keluar ?? '-' }}</p>
        @endif

        {{-- BELUM ISI KEGIATAN --}}
        @if($status['sudah_checkin'] && !$status['sudah_kegiatan'])
            <div class="alert alert-warning mt-3">
                ⚠️ Silakan isi kegiatan terlebih dahulu sebelum Check-Out.
            </div>

            <form action="{{ route('user.absensi.kegiatan', $absensiHariIni->id) }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label fw-semibold">Kegiatan Hari Ini</label>
                    <textarea name="kegiatan" class="form-control" rows="3" required></textarea>
                </div>
                <button type="submit" class="btn btn-success btn-sm">
                    Simpan Kegiatan
                </button>
            </form>
        @endif

        {{-- KEGIATAN SUDAH DISIMPAN → CHECK-OUT MUNCUL --}}
        @if($status['sudah_kegiatan'] && !$status['sudah_checkout'])
            <div class="mt-3">
                <strong>Kegiatan:</strong>
                <p>{{ $absensiHariIni->kegiatan }}</p>
            </div>

            <form action="{{ route('user.absensi.keluar') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-warning btn-sm">
                    Check Out
                </button>
            </form>
        @endif

        {{-- SUDAH CHECK-OUT --}}
        @if($status['sudah_checkout'])
            <div class="alert alert-success mt-3">
                ✅ Kamu sudah Check-Out hari ini
            </div>

            <p><strong>Kegiatan:</strong> {{ $absensiHariIni->kegiatan }}</p>
            <p><strong>Keterangan:</strong> {{ ucfirst($absensiHariIni->keterangan ?? '-') }}</p>
        @endif
    </div>

    {{-- Rekap --}}
    <div class="card mt-4 shadow-sm p-4">
        <h5>Rekap Bulan Ini</h5>
        <hr>
        <p><strong>Total Jam Hadir:</strong> {{ $totalJam ?? 0 }} jam</p>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function (position) {
            if (document.getElementById("latitude_in")) {
                latitude_in.value = position.coords.latitude;
                longitude_in.value = position.coords.longitude;
            }
        }, function () {
            alert("Gagal mengambil lokasi. Aktifkan GPS.");
        });
    }
});
</script>
@endsection
