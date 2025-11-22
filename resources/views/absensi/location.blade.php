@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h3>Lokasi Absensi {{ $absensi->user->name }}</h3>
    <p>Tanggal: {{ $absensi->tanggal }}</p>
    <p>Jam Masuk: {{ $absensi->jam_masuk }}</p>

    <div id="map" style="height: 400px;"></div>
</div>

<!-- Tambahin script Leaflet JS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css"/>
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

<script>
    var map = L.map('map').setView([{{ $absensi->latitude }}, {{ $absensi->longitude }}], 17);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
    }).addTo(map);

    L.marker([{{ $absensi->latitude }}, {{ $absensi->longitude }}])
        .addTo(map)
        .bindPopup("Lokasi Absen: {{ $absensi->user->name }}")
        .openPopup();
</script>
@endsection
