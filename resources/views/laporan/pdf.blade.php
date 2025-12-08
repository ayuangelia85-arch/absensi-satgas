<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rekap Absensi Bulanan</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #000; padding: 6px; }
        th { background-color: #eee; }
    </style>
</head>
<body>

<h2 style="text-align:center">Rekap Absensi Bulanan</h2>

@php
    use Carbon\Carbon;
    $namaBulan = Carbon::createFromDate($tahun, $bulan, 1)->translatedFormat('F');
@endphp

<p><strong>Bulan:</strong> {{ $namaBulan }} {{ $tahun }}</p>
<p><strong>Target Jam:</strong> {{ $targetJam }} jam</p>

<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Nama User</th>
            <th>Total Jam</th>
            <th>Keterangan</th>
        </tr>
    </thead>

    <tbody>
        @foreach($rekap as $i => $r)
            @php
                $total = number_format($r->total_jam, 1);
                $status = $r->total_jam >= $targetJam ? 'Terpenuhi' : 'Tidak Terpenuhi';
            @endphp
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $r->user->name ?? '-' }}</td>
                <td>{{ $total }} jam</td>
                <td>{{ $status }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

<p style="margin-top:15px;">
    <em>Dicetak pada {{ now()->translatedFormat('d F Y, H:i') }} WIB</em>
</p>

</body>
</html>
