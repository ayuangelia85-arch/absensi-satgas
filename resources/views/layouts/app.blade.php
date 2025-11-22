<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Absensi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f9fafb;
        }
        .navbar {
            background-color: #fa809d;
        }
        .navbar-brand, .nav-link, .navbar-text {
            color: white !important;
        }
        footer {
            text-align: center;
            padding: 15px;
            margin-top: 40px;
            background-color: #f1f1f1;
            color: #555;
        }
    </style>
</head>
<body>

{{-- ========== NAVBAR ========== --}}
<nav class="navbar navbar-expand-lg shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold" href="#">Sistem Absensi</a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            @auth
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    @if(Auth::user()->role === 'admin')
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.dashboard') }}">Dashboard</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.user.index') }}">Daftar User</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('laporan.index') }}">Laporan</a>
                        </li>
                    @elseif(Auth::user()->role === 'user')
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('user.absensi') }}">Absensi</a>
                        </li>
                    @endif
                </ul>

                <span class="navbar-text me-3">
                    {{ Auth::user()->name }}
                </span>

                {{-- 🔽 Logout pakai POST --}}
                <form action="{{ route('logout') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-outline-light btn-sm">Logout</button>
                </form>
            @endauth
        </div>
    </div>
</nav>


{{-- ========== MAIN CONTENT ========== --}}
<main>
    @yield('content')
</main>

{{-- ========== FOOTER ========== --}}
<footer>
    <p>&copy; {{ date('Y') }} Absensi SWYC | Built with ❤️ by Dae</p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
