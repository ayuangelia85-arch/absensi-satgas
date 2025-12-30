<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Absensi</title>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        :root {
            --secondary: #fceff4ff;
            --accent: #5dade2;
            --bg-soft: #f9fafb;
        }

        body {
            font-family: 'Open Sans', sans-serif;
            background-color: var(--bg-soft);
            font-size: 16px;
        }

        /* NAVBAR */
        .navbar {
            background-color: var(--secondary);
        }

        .navbar-brand,
        .nav-link {
            color: #e9257aff !important;
            font-weight: 500;
        }

        .nav-link:hover {
            opacity: 0.85;
        }

        .navbar-toggler {
            border-color: rgba(255,255,255,0.6);
        }

        .navbar-toggler-icon {
            filter: invert(1);
        }

        /* USER SECTION */
        .user-name {
            color: #e9257aff;
            font-weight: 600;
            text-decoration: none;
            white-space: nowrap;
        }

        .btn-logout {
            color: #d61f6e;
            background-color: #e9257a;   /* pink langsung */
            border-color: #e9257a;
            font-weight: 600;
        }

        .btn-logout:hover {
            background-color: #d61f6e;   /* pink lebih gelap */
            border-color: #d61f6e;
        }
        /* MAIN CONTENT */
        main {
            padding: 16px 0;
        }

        /* FOOTER */
        footer {
            text-align: center;
            padding: 12px;
            font-size: 13px;
            background-color: #fceff4ff;
            color: #a70a4eff;
            margin-top: 32px;
        }

        /* MOBILE OPTIMIZATION */
        @media (max-width: 576px) {
            body {
                font-size: 12px;
            }

            .navbar-brand {
                font-size: 16px;
            }

            footer {
                font-size: 12px;
            }

            .navbar-nav .nav-link {
                padding: 10px 0;
            }
        }
    </style>
</head>
<body>

{{-- ========== NAVBAR ========== --}}
<nav class="navbar navbar-expand-lg shadow-sm">
    <div class="container">

        <a class="navbar-brand fw-bold" href="#">
            Sistem Absensi
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
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
                @else
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('user.absensi') }}">Absensi</a>
                    </li>
                @endif

            </ul>

            {{-- USER INFO --}}
            <div class="d-flex flex-column flex-lg-row align-items-lg-center gap-2 mt-3 mt-lg-0">

                <a href="{{ route('profil') }}" class="user-name text-center text-lg-start">
                    {{ Auth::user()->name }}
                </a>

                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button class="btn btn-outline-danger btn-sm w-100">
                        Logout
                    </button>
                </form>

            </div>
            @endauth

        </div>
    </div>
</nav>

{{-- ========== MAIN CONTENT ========== --}}
<main class="container">
    @yield('content')
</main>

{{-- ========== FOOTER ========== --}}
<footer>
    <p class="mb-0">
        &copy; {{ date('Y') }} SWYC & SATGAS PPKPT Universitas Budi Luhur
    </p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
