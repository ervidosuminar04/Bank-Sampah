<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - Bank Sampah</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f0f4f8;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .card {
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            padding: 40px 36px;
            width: 100%;
            max-width: 420px;
        }

        .logo {
            text-align: center;
            margin-bottom: 24px;
        }

        .logo span {
            display: inline-block;
            background: #2d7a4f;
            color: #fff;
            font-size: 22px;
            font-weight: 700;
            padding: 10px 20px;
            border-radius: 8px;
            letter-spacing: 1px;
        }

        h2 {
            text-align: center;
            font-size: 20px;
            color: #333;
            margin-bottom: 24px;
        }

        .alert-error {
            background: #fee2e2;
            border: 1px solid #fca5a5;
            color: #b91c1c;
            padding: 10px 14px;
            border-radius: 6px;
            margin-bottom: 16px;
            font-size: 14px;
        }

        .alert-error ul { padding-left: 16px; }

        .form-group {
            margin-bottom: 16px;
        }

        label {
            display: block;
            font-size: 14px;
            color: #555;
            margin-bottom: 6px;
            font-weight: 500;
        }

        input[type="text"],
        input[type="password"],
        input[type="email"] {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            font-size: 14px;
            color: #333;
            transition: border-color 0.2s;
            outline: none;
        }

        input:focus {
            border-color: #2d7a4f;
            box-shadow: 0 0 0 3px rgba(45,122,79,0.12);
        }

        .btn {
            display: block;
            width: 100%;
            padding: 11px;
            background: #2d7a4f;
            color: #fff;
            font-size: 15px;
            font-weight: 600;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            margin-top: 8px;
            transition: background 0.2s;
        }

        .btn:hover { background: #245f3e; }

        .footer-link {
            text-align: center;
            margin-top: 16px;
            font-size: 13px;
            color: #666;
        }

        .footer-link a {
            color: #2d7a4f;
            text-decoration: none;
            font-weight: 600;
        }

        .footer-link a:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <div class="card">
        <div class="logo">
            <span>🌿 Bank Sampah</span>
        </div>
        <h2>Masuk ke Akun Anda</h2>

        @if ($errors->any())
            <div class="alert-error">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ url('/login') }}">
            @csrf
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" name="username" id="username"
                       value="{{ old('username') }}" required autocomplete="username">
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password" id="password"
                       required autocomplete="current-password">
            </div>
            <button type="submit" class="btn">Masuk</button>
        </form>

        <p class="footer-link">
            Belum punya akun? <a href="{{ url('/register') }}">Daftar di sini</a>
        </p>
    </div>
</body>
</html>
