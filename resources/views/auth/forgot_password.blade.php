<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: #fdf3ff;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .card {
            background: #ffffff;
            width: 350px;
            padding: 25px;
            border-radius: 20px;
            box-shadow: 0px 8px 20px rgba(0,0,0,0.1);
            text-align: center;
            border: 2px solid #ffd8ff;
        }

        h2 {
            color: #b400c8;
            margin-bottom: 5px;
        }

        p.subtitle {
            font-size: 14px;
            color: #666;
            margin-bottom: 20px;
        }

        input[type="email"] {
            width: 90%;
            padding: 12px;
            border-radius: 10px;
            border: 1px solid #d8aee7;
            outline: none;
            margin-bottom: 10px;
            background: #fff7ff;
        }

        button {
            background: #d46aff;
            color: white;
            border: none;
            padding: 12px 0;
            width: 100%;
            border-radius: 12px;
            font-size: 16px;
            cursor: pointer;
            transition: 0.2s;
            margin-top: 10px;
        }

        button:hover {
            background: #b746e0;
        }

        .success {
            color: #2bb700;
            margin-bottom: 10px;
        }

        .error {
            color: red;
            font-size: 13px;
        }

        .cute-icon {
            width: 70px;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>

    <div class="card">
        <img src="https://cdn-icons-png.flaticon.com/512/742/742751.png" class="cute-icon">
        <h2>Lupa Password?</h2>
        <p class="subtitle">Masukkan email kamu, nanti aku kirimin link reset ya 💌</p>

        @if (session('status'))
            <p class="success">{{ session('status') }}</p>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <input type="email" name="email" placeholder="Email kamu..." required>

            @error('email')
                <p class="error">{{ $message }}</p>
            @enderror

            <button type="submit">Kirim Link Reset ✨</button>
        </form>
    </div>

</body>
</html>
