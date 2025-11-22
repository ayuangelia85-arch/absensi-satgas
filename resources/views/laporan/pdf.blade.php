<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Absensi</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #000; padding: 6px; }
        th { background-color: #eee; }
    </style>
</head>
<body>

<h2 style="text-align:center">Laporan Absensi</h2>

@if(request('$start_date') || request('$end_date'))
        <p><strong>Periode:</strong>
            {{ $request('$start_date') ? \Carbon\Carbon::parse(request('$start_date'))->translatedFormat('d F Y') : '–' }}
            s/d
            {{ request('end_date') ? \Carbon\Carbon::parse(request('end_date'))->translatedFormat('d F Y') : '–' }}
        </p>
    @endif

    <p class="timestamp">
        Dicetak pada: {{ \Carbon\Carbon::now()->setTimezone('Asia/Jakarta')->translatedFormat('d F Y, H:i') }} WIB
    </p>

<table>
    <thead>
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
        @foreach($laporan as $i => $l)
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
                <td>{{ $i + 1 }}</td>
                <td>{{ $l->user->name }}</td>
                <td>{{ \Carbon\Carbon::parse($l->tanggal)->translatedFormat('d F Y') }}</td>
                <td>{{ $l->jam_masuk }}</td>
                <td>{{ $l->jam_keluar }}</td>
                <td>{{ $total }}</td>
                <td>{{ ucfirst($l->keterangan) }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

</body>
</html>
