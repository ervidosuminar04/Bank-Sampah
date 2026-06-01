<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengaturan - Realive</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Nunito:wght@700;800;900&family=Nunito+Sans:wght@400;600;700&family=JetBrains+Mono:wght@400&display=swap');

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
            background-color: var(--bg-page);
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
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 1;
            background-image: url('{{ asset('images/Pattern 1@3x.png') }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            opacity: 0.12;
            pointer-events: none;
        }

        .card {
            background: var(--bg-surface);
            border-radius: 24px 24px 16px 16px;
            box-shadow: var(--shadow-xl);
            padding: 40px 32px;
            width: 100%;
            max-width: 480px;
            position: relative;
            z-index: 2;
            animation: fadeSlideUp 0.6s var(--ease-spring) forwards;
            border: 1px solid rgba(125, 184, 37, 0.15);
        }

        @keyframes fadeSlideUp {
            from { opacity: 0; transform: translateY(20px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .role-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: var(--color-canopy);
            color: var(--color-white);
            font-size: 11px;
            font-weight: 700;
            padding: 6px 16px;
            border-radius: var(--radius-full);
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 24px;
        }

        h1 {
            font-family: 'Nunito', sans-serif;
            font-size: 26px;
            font-weight: 900;
            color: var(--text-primary);
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        p.description {
            font-size: 14px;
            line-height: 1.5;
            color: var(--text-secondary);
            margin-bottom: 24px;
        }

        p.description code {
            font-family: 'JetBrains Mono', monospace;
            background: rgba(26, 58, 26, 0.06);
            padding: 2px 4px;
            border-radius: 4px;
            font-size: 12.5px;
            color: var(--color-flame);
        }

        .info-box {
            background: var(--color-mist);
            border-radius: var(--radius-md);
            padding: 20px;
            margin-bottom: 28px;
            border: 1px solid rgba(125, 184, 37, 0.12);
        }

        .info-title {
            font-weight: 700;
            font-size: 13px;
            color: var(--color-canopy);
            margin-bottom: 16px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            border-bottom: 1px solid rgba(45, 106, 45, 0.1);
            padding-bottom: 8px;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 0;
            border-bottom: 1px dashed rgba(138, 158, 138, 0.3);
        }

        .info-row:last-child {
            border-bottom: none;
            padding-bottom: 0;
        }

        .info-row:first-of-type {
            padding-top: 0;
        }

        .info-label {
            font-size: 13.5px;
            color: var(--text-secondary);
            font-weight: 600;
        }

        .info-value {
            font-size: 14px;
            color: var(--text-primary);
            font-weight: 700;
            text-align: right;
        }

        .info-value code {
            font-family: 'JetBrains Mono', monospace;
            background: rgba(26, 58, 26, 0.06);
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 12.5px;
            font-weight: 600;
        }

        .status-badge {
            display: inline-block;
            background: var(--color-sprout);
            color: var(--color-white);
            padding: 2px 10px;
            border-radius: var(--radius-full);
            font-weight: 800;
            font-size: 11.5px;
        }

        .btn-back {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            min-height: 48px;
            padding: 0 24px;
            background: var(--color-solar);
            color: var(--color-canopy);
            font-family: 'Nunito Sans', sans-serif;
            font-size: 16px;
            font-weight: 700;
            border: none;
            border-radius: var(--radius-full);
            cursor: pointer;
            text-decoration: none;
            transition: all 0.2s var(--ease-spring);
            box-shadow: var(--shadow-md);
        }

        .btn-back:hover {
            background: var(--color-sunburst);
            transform: scale(1.02);
            box-shadow: var(--shadow-glow);
        }
    </style>
</head>
<body>

    <div class="card">
        <span class="role-badge">Halaman Khusus Admin</span>
        <h1><span>⚙️</span> Pengaturan Admin</h1>
        <p class="description">
            Halaman ini terproteksi oleh <code>role:admin</code> middleware. Kamu berhasil mengakses halaman ini karena masuk sebagai pengguna dengan tipe <strong>Admin</strong>.
        </p>

        <div class="info-box">
            <div class="info-title">Detail Akun Admin</div>
            
            <div class="info-row">
                <span class="info-label">Nama Lengkap</span>
                <span class="info-value">{{ $admin->admin_nama }}</span>
            </div>
            
            <div class="info-row">
                <span class="info-label">Username</span>
                <span class="info-value"><code>{{ $admin->admin_username }}</code></span>
            </div>

            <div class="info-row">
                <span class="info-label">Level Jabatan</span>
                <span class="info-value"><code>{{ $admin->admin_level }}</code></span>
            </div>

            <div class="info-row">
                <span class="info-label">Status Akun</span>
                <span class="info-value">
                    <span class="status-badge">{{ ucfirst($admin->admin_status) }}</span>
                </span>
            </div>
        </div>

        <a href="{{ url('/dashboard') }}" class="btn-back">Kembali ke Dashboard</a>
    </div>

</body>
</html>
