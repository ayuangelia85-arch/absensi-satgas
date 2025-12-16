<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Akun Baru</title>
</head>
<body>
    <h2>Halo, {{ $user->name }}</h2>

    <p>Akun Anda telah dibuat oleh admin.</p>

    <p><strong>Data Login:</strong></p>
    <ul>
        <li>Username (NIM/NIP): {{ $user->nim_nip }}</li>
        <li>Password: {{ $password }}</li>
    </ul>

    <p>⚠️ Demi keamanan, silakan login dan segera ganti password Anda.</p>

    <br>
    <p>Terima kasih.</p>
</body>
</html>
