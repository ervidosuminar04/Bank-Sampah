<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pilih Profil Pengepul - Realive</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Nunito:wght@700;800;900&family=Nunito+Sans:wght@400;600;700&display=swap');

        :root {
            --color-solar:      #FFD700;
            --color-sunburst:   #FFA500;
            --color-ember:      #F5511E;
            --color-flame:      #E63946;

            --color-lime:       #C8E000;
            --color-sprout:     #7DB825;
            --color-forest:     #2D6A2D;
            --color-canopy:     #1A3A1A;

            --color-black:      #0A0A0A;
            --color-white:      #FFFFFF;
            --color-mist:       #F4F7F0;
            --color-fog:        #8A9E8A;
            --color-smoke:      #D4DDD4;

            --gradient-brand: linear-gradient(135deg, #FFD700 0%, #7DB825 50%, #2D6A2D 100%);

            --bg-page:          var(--color-mist);
            --bg-surface:       var(--color-white);
            --text-primary:     var(--color-canopy);
            --text-secondary:   var(--color-forest);
            --text-muted:       var(--color-fog);

            --shadow-md:    0 4px 16px rgba(26, 58, 26, 0.12);
            --shadow-xl:    0 16px 56px rgba(26, 58, 26, 0.24);
            --radius-md:   16px;
            --radius-full: 9999px;
            
            --ease-spring: cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Nunito Sans', sans-serif;
            background-color: var(--color-mist);
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
            top: 0; left: 0; width: 100%; height: 100%;
            z-index: -1;
            background-image: url('{{ asset('images/Pattern 1@3x.png') }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            opacity: 0.3;
        }

        .card {
            background: var(--bg-surface);
            border-radius: 24px;
            box-shadow: var(--shadow-xl);
            padding: 40px 32px;
            width: 100%;
            max-width: 500px;
            animation: fadeSlideUp 0.6s var(--ease-spring) forwards;
        }

        @keyframes fadeSlideUp {
            from { opacity: 0; transform: translateY(20px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .logo {
            text-align: center;
            margin-bottom: 24px;
        }

        .logo img {
            max-width: 160px;
            width: 100%;
            height: auto;
        }

        h2 {
            text-align: center;
            font-family: 'Nunito', sans-serif;
            font-size: 24px;
            font-weight: 800;
            color: var(--text-primary);
            margin-bottom: 12px;
        }

        .subtitle {
            text-align: center;
            font-size: 14px;
            color: var(--text-secondary);
            margin-bottom: 30px;
            font-weight: 600;
        }

        .profile-list {
            display: flex;
            flex-direction: column;
            gap: 16px;
            max-height: 360px;
            overflow-y: auto;
            padding-right: 4px;
            margin-bottom: 24px;
        }

        /* Custom Scrollbar */
        .profile-list::-webkit-scrollbar {
            width: 6px;
        }
        .profile-list::-webkit-scrollbar-track {
            background: var(--color-mist);
            border-radius: var(--radius-full);
        }
        .profile-list::-webkit-scrollbar-thumb {
            background: var(--color-fog);
            border-radius: var(--radius-full);
        }

        .profile-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: var(--color-mist);
            border: 2px solid var(--color-smoke);
            border-radius: var(--radius-md);
            padding: 16px 20px;
            transition: all 0.2s ease;
            text-decoration: none;
            color: inherit;
        }

        .profile-item:hover {
            border-color: var(--color-sprout);
            background: var(--color-white);
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        .profile-details {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .avatar {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            background: var(--gradient-brand);
            color: var(--color-white);
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Nunito', sans-serif;
            font-size: 18px;
            font-weight: 900;
        }

        .info {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .name {
            font-family: 'Nunito', sans-serif;
            font-size: 16px;
            font-weight: 800;
            color: var(--color-canopy);
        }

        .address {
            font-size: 12.5px;
            color: var(--color-forest);
        }

        .arrow {
            font-size: 18px;
            color: var(--color-forest);
            transition: transform 0.2s;
        }

        .profile-item:hover .arrow {
            transform: translateX(4px);
            color: var(--color-sprout);
        }

        .empty-state {
            text-align: center;
            padding: 24px;
            color: var(--text-muted);
            font-weight: 600;
        }

        .back-link {
            display: block;
            text-align: center;
            margin-top: 16px;
            font-size: 14px;
            color: var(--color-forest);
            text-decoration: none;
            font-weight: 700;
            transition: color 0.2s;
        }

        .back-link:hover {
            color: var(--color-sprout);
        }
    </style>
</head>
<body>
    <div class="card">
        <div class="logo">
            <img src="{{ asset('images/logo Realive@3x.png') }}" alt="logo Realive">
        </div>
        <h2>Pilih Profil Pengepul</h2>
        <p class="subtitle">Pilih profil stasiun pengepul Anda untuk masuk ke Dashboard</p>

        @if(session('error'))
            <div style="background: rgba(230, 57, 70, 0.1); border: 1px solid var(--color-flame); color: var(--color-flame); padding: 12px 16px; border-radius: 8px; margin-bottom: 24px; font-size: 14px; font-weight: 600; text-align: center;">
                ⚠️ {{ session('error') }}
            </div>
        @endif

        <div class="profile-list">
            @forelse($pengepuls as $p)
                <a href="{{ route('pengepul.select', $p->id_pengepul) }}" class="profile-item">
                    <div class="profile-details">
                        <div class="avatar">{{ substr($p->pengepul_nama, 0, 1) }}</div>
                        <div class="info">
                            <span class="name">{{ $p->pengepul_nama }}</span>
                            <span class="address">📍 {{ Str::limit($p->pengepul_alamat, 40) }}</span>
                        </div>
                    </div>
                    <span class="arrow">➔</span>
                </a>
            @empty
                <div class="empty-state">
                    📭 Belum ada profil stasiun pengepul yang terdaftar atau aktif.
                </div>
            @endif
        </div>

        <hr style="border: none; border-top: 2px solid var(--color-smoke); margin-bottom: 20px;">

        <a href="{{ url('/login') }}" class="back-link">⬅️ Kembali ke Halaman Login</a>
    </div>
</body>
</html>
