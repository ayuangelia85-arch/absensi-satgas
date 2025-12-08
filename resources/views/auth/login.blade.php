<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Absensi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        .btn-pink {
            background-color: #ff4f9a;
            color: white;
            border: none;
        }
        .btn-pink:hover {
            background-color: #e0468a;
            color: white;
        }
    </style>
</head>
<body class="bg-light d-flex justify-content-center align-items-center vh-100">

<div class="card shadow p-4" style="width: 400px;">

    <!-- LOGO DI SINI -->
    <div class="text-center mb-3">
        <div class="text-center mb-3">
    <div class="d-flex justify-content-center align-items-center gap-3">

        <img src="{{ asset('logo/COBA_LOGO.png') }}" 
             alt="Logo 1" width="80">

        <img src="{{ asset('logo/logo satgas ppkpt.png') }}" 
             alt="Logo 2" width="80">

    </div>
</div>

    </div>

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

        <button type="submit" class="btn btn-pink w-100">Masuk</button>
    </form>

    <a href="{{ route('password.request') }}" class="text-dark">Lupa Password?</a>

</div>

</body>
</html>
