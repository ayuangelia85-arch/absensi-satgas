<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Absensi</title>
    <style>
        body { font-family: sans-serif; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #000; padding: 6px; text-align: left; }
    </style>
</head>
<body>
    <h2>Laporan Absensi</h2>
    <table>
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
</body>
</html>
