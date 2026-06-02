<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun - Realive</title>
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
            max-width: 480px;
            position: relative;
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

        /* Role Selector Tabs */
        .role-selector {
            display: flex;
            gap: 0;
            margin-bottom: 28px;
            border-radius: var(--radius-full);
            overflow: hidden;
            border: 2px solid var(--border-default);
            background: var(--color-mist);
        }

        .role-tab {
            flex: 1;
            padding: 14px 16px;
            text-align: center;
            font-size: 14px;
            font-weight: 700;
            font-family: 'Nunito Sans', sans-serif;
            color: var(--text-muted);
            cursor: pointer;
            transition: all 0.3s ease;
            border: none;
            background: transparent;
            position: relative;
        }

        .role-tab.active {
            background: var(--gradient-brand);
            color: var(--color-white);
            box-shadow: var(--shadow-md);
        }

        .role-tab:not(.active):hover {
            color: var(--text-primary);
            background: rgba(125, 184, 37, 0.08);
        }

        .role-tab .role-icon {
            display: block;
            font-size: 20px;
            margin-bottom: 4px;
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

        .section-title {
            font-family: 'Nunito', sans-serif;
            font-size: 13px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: var(--color-forest);
            margin-bottom: 16px;
            margin-top: 4px;
        }

        .divider {
            border: none;
            border-top: 2px solid var(--color-mist);
            margin: 20px 0;
        }

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
        input[type="email"],
        textarea {
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

        textarea {
            height: auto;
            padding: 12px 16px;
            resize: vertical;
        }

        input:focus, textarea:focus {
            border-color: var(--border-focus);
            box-shadow: var(--shadow-focus);
        }
        
        input::placeholder, textarea::placeholder {
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

        .info-box {
            background: rgba(125, 184, 37, 0.08);
            border: 1px solid rgba(125, 184, 37, 0.25);
            border-radius: var(--radius-sm);
            padding: 14px 16px;
            margin-bottom: 20px;
            font-size: 13px;
            color: var(--color-forest);
            font-weight: 600;
            line-height: 1.5;
        }

        .info-box .info-icon {
            font-size: 16px;
            margin-right: 6px;
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

        /* Form panel transitions */
        .form-panel {
            display: none;
            animation: fadeSlideUp 0.35s var(--ease-spring);
        }
        .form-panel.active {
            display: block;
        }
    </style>
</head>
<body>
    <div class="card">
        <div class="logo">
            <img src="{{ asset('images/logo Realive@3x.png') }}" alt="logo">
        </div>
        <h2>Buat Akun Baru</h2>

        <!-- Role Selector Tabs -->
        <div class="role-selector">
            <button type="button" class="role-tab active" onclick="switchRole('nasabah')" id="tab-nasabah">
                <span class="role-icon">👤</span>
                Nasabah
            </button>
            <button type="button" class="role-tab" onclick="switchRole('pengepul')" id="tab-pengepul">
                <span class="role-icon">🚛</span>
                Pengepul
            </button>
        </div>

        @if ($errors->any())
            <div class="alert-error">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- ============ FORM NASABAH ============ -->
        <div id="form-nasabah" class="form-panel active">
            <form method="POST" action="{{ url('/register') }}">
                @csrf

                <p class="section-title">Data Pribadi</p>

                <div class="form-group">
                    <label for="nasabah_nama">Nama Lengkap</label>
                    <input type="text" name="nasabah_nama" id="nasabah_nama"
                           value="{{ old('nasabah_nama') }}" required>
                </div>

                <div class="form-group">
                    <label for="nasabah_nik">NIK (Nomor Induk Kependudukan)</label>
                    <input type="text" name="nasabah_nik" id="nasabah_nik"
                           value="{{ old('nasabah_nik') }}" maxlength="20" required>
                </div>

                <div class="form-group">
                    <label for="nasabah_alamat">Alamat</label>
                    <textarea name="nasabah_alamat" id="nasabah_alamat" rows="3" required>{{ old('nasabah_alamat') }}</textarea>
                </div>

                <div class="form-group">
                    <label for="nasabah_telepon">Nomor Telepon</label>
                    <input type="text" name="nasabah_telepon" id="nasabah_telepon"
                           value="{{ old('nasabah_telepon') }}" maxlength="20" required>
                </div>

                <div class="form-group">
                    <label for="nasabah_email">Email</label>
                    <input type="email" name="nasabah_email" id="nasabah_email"
                           value="{{ old('nasabah_email') }}" required>
                </div>

                <hr class="divider">
                <p class="section-title">Akun & Keamanan</p>

                <div class="form-group">
                    <label for="nasabah_username">Username</label>
                    <input type="text" name="nasabah_username" id="nasabah_username"
                           value="{{ old('nasabah_username') }}" maxlength="50" required>
                </div>

                <div class="form-group">
                    <label for="nasabah_password">Password</label>
                    <input type="password" name="nasabah_password" id="nasabah_password"
                           minlength="6" required>
                </div>

                <div class="form-group">
                    <label for="nasabah_password_confirmation">Konfirmasi Password</label>
                    <input type="password" name="nasabah_password_confirmation"
                           id="nasabah_password_confirmation" required>
                </div>

                <button type="submit" class="btn">Daftar Sebagai Nasabah</button>
            </form>
        </div>

        <!-- ============ FORM PENGEPUL ============ -->
        <div id="form-pengepul" class="form-panel">
            <div class="info-box">
                <span class="info-icon">ℹ️</span>
                Akun pengepul yang baru mendaftar akan berstatus <strong>menunggu verifikasi</strong> oleh admin. 
                Anda dapat login setelah akun diverifikasi.
            </div>

            <form method="POST" action="{{ url('/register/pengepul') }}">
                @csrf

                <p class="section-title">Data Pengepul</p>

                <div class="form-group">
                    <label for="pengepul_nama">Nama Lengkap / Nama Usaha</label>
                    <input type="text" name="nama" id="pengepul_nama"
                           value="{{ old('nama') }}" placeholder="Contoh: Budi Santoso / CV Maju Jaya" required>
                </div>

                <div class="form-group">
                    <label for="pengepul_alamat">Alamat Lengkap</label>
                    <textarea name="alamat" id="pengepul_alamat" rows="3" placeholder="Alamat tempat usaha atau domisili" required>{{ old('alamat') }}</textarea>
                </div>

                <div class="form-group">
                    <label for="pengepul_gmaps_link">Link Google Maps (Pilihan)</label>
                    <input type="text" name="gmaps_link" id="pengepul_gmaps_link"
                           value="{{ old('gmaps_link') }}" placeholder="https://maps.app.goo.gl/...">
                    <p style="font-size: 11px; color: var(--text-muted); margin-top: 4px;">*Masukkan link Google Maps alamat Anda agar otomatis dikonversi menjadi titik kordinat (Latitude & Longitude) pada peta.</p>
                </div>

                <div class="form-group">
                    <label for="pengepul_telepon">Nomor Telepon</label>
                    <input type="text" name="telepon" id="pengepul_telepon"
                           value="{{ old('telepon') }}" maxlength="20" placeholder="08xxxxxxxxxx" required>
                </div>

                <hr class="divider">
                <p class="section-title">Akun & Keamanan</p>

                <div class="form-group">
                    <label for="pengepul_username">Username (untuk login)</label>
                    <input type="text" name="username" id="pengepul_username"
                           value="{{ old('username') }}" maxlength="50" placeholder="Contoh: pengepul_budi" required>
                </div>

                <div class="form-group">
                    <label for="pengepul_password">Password</label>
                    <input type="password" name="password" id="pengepul_password"
                           minlength="6" required>
                </div>

                <div class="form-group">
                    <label for="pengepul_password_confirmation">Konfirmasi Password</label>
                    <input type="password" name="password_confirmation"
                           id="pengepul_password_confirmation" required>
                </div>

                <button type="submit" class="btn">Daftar Sebagai Pengepul</button>
            </form>
        </div>

        <p class="footer-link">
            Sudah punya akun? <a href="{{ url('/login') }}">Masuk di sini</a>
        </p>
    </div>

    <script>
        function switchRole(role) {
            // Toggle tabs
            document.getElementById('tab-nasabah').classList.toggle('active', role === 'nasabah');
            document.getElementById('tab-pengepul').classList.toggle('active', role === 'pengepul');
            
            // Toggle form panels
            document.getElementById('form-nasabah').classList.toggle('active', role === 'nasabah');
            document.getElementById('form-pengepul').classList.toggle('active', role === 'pengepul');
        }

        // If there are old pengepul fields, auto-switch to pengepul tab
        @if(old('username') || old('nama'))
            switchRole('pengepul');
        @endif
    </script>
</body>
</html>
