<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Absensi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex justify-content-center align-items-center vh-100">

<div class="card shadow p-4" style="width: 400px;">
    <h3 class="text-center mb-4">Login Sistem Absensi</h3>

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form action="{{ route('login.post') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="nim_nip" class="form-label">NIM / NIP</label>
            <input type="text" name="nim_nip" id="nim_nip" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Kata Sandi</label>
            <input type="password" name="password" id="password" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary w-100">Masuk</button>
    </form>
    <a href="{{ route('password.request') }}">Lupa Password?</a>
</div>

</body>
</html>
