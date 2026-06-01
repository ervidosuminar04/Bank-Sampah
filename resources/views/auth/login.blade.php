<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - Realive</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Nunito:wght@700;800;900&family=Nunito+Sans:wght@400;600;700&display=swap');

        :root {
            /* Warm Spectrum (Energy / CTA) */
            --color-solar:      #FFD700;
            --color-sunburst:   #FFA500;
            --color-ember:      #F5511E;
            --color-flame:      #E63946;

            /* Green Spectrum (Brand / Growth) */
            --color-lime:       #C8E000;
            --color-sprout:     #7DB825;
            --color-forest:     #2D6A2D;
            --color-canopy:     #1A3A1A;

            /* Neutrals */
            --color-black:      #0A0A0A;
            --color-white:      #FFFFFF;
            --color-mist:       #F4F7F0;
            --color-fog:        #8A9E8A;
            --color-smoke:      #D4DDD4;

            /* Gradients */
            --gradient-brand: linear-gradient(135deg, #FFD700 0%, #7DB825 50%, #2D6A2D 100%);
            --gradient-warm: linear-gradient(90deg, #FFD700 0%, #F5511E 60%, #E63946 100%);

            /* Semantic Tokens */
            --bg-page:          var(--color-mist);
            --bg-surface:       var(--color-white);
            --bg-dark:          var(--color-black);
            --text-primary:     var(--color-canopy);
            --text-secondary:   var(--color-forest);
            --text-muted:       var(--color-fog);
            --text-on-dark:     var(--color-white);
            --accent-cta:       var(--color-solar);
            --accent-success:   var(--color-forest);
            --accent-alert:     var(--color-flame);
            --border-default:   var(--color-smoke);
            --border-focus:     var(--color-sprout);

            /* Shadows & Radius */
            --shadow-sm:    0 1px 4px rgba(26, 58, 26, 0.08);
            --shadow-md:    0 4px 16px rgba(26, 58, 26, 0.12);
            --shadow-lg:    0 8px 32px rgba(26, 58, 26, 0.18);
            --shadow-xl:    0 16px 56px rgba(26, 58, 26, 0.24);
            --shadow-glow:  0 0 24px rgba(255, 215, 0, 0.35);
            --shadow-focus: 0 0 0 3px rgba(125, 184, 37, 0.45);

            --radius-sm:   8px;
            --radius-md:   16px;
            --radius-lg:   24px;
            --radius-full: 9999px;
            
            /* Animation */
            --ease-spring: cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Nunito Sans', sans-serif;
            background-color: var(--color-white);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-primary);
            padding: 20px;
            position: relative;
            overflow-x: hidden;
        }

        body::before {

            content: "";
            position: absolute;
           top: 0px;
           left: 0px;
           width: 100%;
           height: 100%;
            z-index: -1;

            background-image: url('{{ asset('images/Pattern 1@3x.png') }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;

            filter: blur(1px);
            opacity: 0.5;

        }
        .card {
            background: var(--bg-surface);
            border-radius: 24px 24px 16px 16px;
            box-shadow: var(--shadow-xl);
            padding: 48px 36px;
            width: 100%;
            max-width: 420px;
            position: relative;
            animation: fadeSlideUp 0.6s var(--ease-spring) forwards;
        }

        @keyframes fadeSlideUp {
            from { opacity: 0; transform: translateY(20px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .logo {
            text-align: center;
            margin-bottom: 32px;
        }

        .logo img{
            max-width: 200px;
            width: 100%;
            height: auto;
            object-fit: contain;
        }

        h2 {
            text-align: center;
            font-family: 'Nunito', sans-serif;
            font-size: 24px;
            font-weight: 800;
            color: var(--text-primary);
            margin-bottom: 24px;
        }

        .alert-error {
            background: rgba(230, 57, 70, 0.1);
            border: 1px solid var(--color-flame);
            color: var(--color-flame);
            padding: 12px 16px;
            border-radius: var(--radius-sm);
            margin-bottom: 24px;
            font-size: 14px;
            font-weight: 600;
        }

        .alert-error ul { padding-left: 16px; margin-top: 4px; }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            font-size: 14px;
            color: var(--text-primary);
            margin-bottom: 8px;
            font-weight: 700;
        }

        input[type="text"],
        input[type="password"],
        input[type="email"] {
            width: 100%;
            height: 48px;
            padding: 0 16px;
            border: 2px solid var(--border-default);
            border-radius: var(--radius-sm);
            font-family: 'Nunito Sans', sans-serif;
            font-size: 16px;
            color: var(--text-primary);
            transition: all 0.2s ease;
            outline: none;
            background: var(--bg-surface);
        }

        input:focus {
            border-color: var(--border-focus);
            box-shadow: var(--shadow-focus);
        }
        
        input::placeholder {
            color: var(--text-muted);
        }

        .btn {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            min-height: 48px;
            padding: 0 24px;
            background: var(--accent-cta);
            color: var(--text-primary);
            font-family: 'Nunito Sans', sans-serif;
            font-size: 16px;
            font-weight: 700;
            border: none;
            border-radius: var(--radius-full);
            cursor: pointer;
            margin-top: 24px;
            transition: all 0.2s var(--ease-spring);
        }

        .btn:hover { 
            background: var(--color-sunburst);
            transform: scale(1.02);
            box-shadow: var(--shadow-glow);
        }

        .footer-link {
            text-align: center;
            margin-top: 24px;
            font-size: 14px;
            color: var(--text-muted);
            font-weight: 600;
        }

        .footer-link a {
            color: var(--color-forest);
            text-decoration: none;
            font-weight: 700;
            transition: color 0.2s;
        }

        .footer-link a:hover { 
            color: var(--color-sprout);
        }
    </style>
</head>
<body>
    <div class="card">
        <div class="logo">
            <img src="{{ asset('images/logo Realive@3x.png') }}" alt="logo Realive">
        </div>
        <h2>Masuk ke Akun Kamu</h2>

        @if(session('success'))
            <div style="background: rgba(125, 184, 37, 0.1); border: 1px solid var(--color-sprout); color: var(--color-forest); padding: 12px 16px; border-radius: var(--radius-sm); margin-bottom: 24px; font-size: 14px; font-weight: 600;">
                ✅ {{ session('success') }}
            </div>
        @endif

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
                       value="{{ old('username') }}" required autocomplete="username" placeholder="Masukkan username kamu">
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password" id="password"
                       required autocomplete="current-password" placeholder="Masukkan password kamu">
            </div>
            <button type="submit" class="btn">Masuk</button>
        </form>

        <p class="footer-link">
            Belum punya akun? <a href="{{ url('/register') }}">Daftar di sini</a>
        </p>
    </div>
</body>
</html>
