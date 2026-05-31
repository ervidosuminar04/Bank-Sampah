<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Realive - Bank Sampah Platform</title>
    <meta name="description" content="Platform Realive: Waste comes in warm — it leaves green. Ubah sampahmu menjadi keuntungan finansial dan Eco Poin.">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@700;800;900&family=Nunito+Sans:wght@400;600;700&family=JetBrains+Mono:wght@400&display=swap" rel="stylesheet">
    <style>
        /* Design System CSS */
        :root {
            /* Warm Spectrum */
            --color-solar: #FFD700;
            --color-sunburst: #FFA500;
            --color-ember: #F5511E;
            --color-flame: #E63946;

            /* Green Spectrum */
            --color-lime: #C8E000;
            --color-sprout: #7DB825;
            --color-forest: #2D6A2D;
            --color-canopy: #1A3A1A;

            /* Neutrals */
            --color-black: #0A0A0A;
            --color-white: #FFFFFF;
            --color-mist: #F4F7F0;
            --color-fog: #8A9E8A;
            --color-smoke: #D4DDD4;

            /* Gradients */
            --gradient-brand: linear-gradient(135deg, var(--color-solar) 0%, var(--color-sprout) 50%, var(--color-forest) 100%);
            --gradient-warm: linear-gradient(90deg, var(--color-solar) 0%, var(--color-ember) 60%, var(--color-flame) 100%);
            
            /* Semantic Tokens */
            --bg-page: var(--color-mist);
            --bg-surface: var(--color-white);
            --bg-dark: var(--color-black);
            --text-primary: var(--color-canopy);
            --text-secondary: var(--color-forest);
            --text-muted: var(--color-fog);
            --text-on-dark: var(--color-white);
            --text-on-gradient: var(--color-white);
            --accent-cta: var(--color-solar);
            --accent-success: var(--color-forest);
            --accent-alert: var(--color-flame);
            --border-default: var(--color-smoke);
            --border-focus: var(--color-sprout);

            /* Typography */
            --font-display: 'Nunito', sans-serif;
            --font-body: 'Nunito Sans', sans-serif;
            --font-mono: 'JetBrains Mono', monospace;

            /* Border Radius */
            --radius-sm: 8px;
            --radius-md: 16px;
            --radius-lg: 24px;
            --radius-xl: 36px;
            --radius-full: 9999px;

            /* Shadows */
            --shadow-sm: 0 1px 4px rgba(26, 58, 26, 0.08);
            --shadow-md: 0 4px 16px rgba(26, 58, 26, 0.12);
            --shadow-lg: 0 8px 32px rgba(26, 58, 26, 0.18);
            --shadow-xl: 0 16px 56px rgba(26, 58, 26, 0.24);
            --shadow-glow: 0 0 24px rgba(255, 215, 0, 0.35);
            --shadow-focus: 0 0 0 3px rgba(125, 184, 37, 0.45);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: var(--font-body);
            background-color: var(--bg-page);
            color: var(--text-primary);
            line-height: 1.55;
            -webkit-font-smoothing: antialiased;
        }

        h1, h2, h3, h4, h5, h6 {
            font-family: var(--font-display);
            line-height: 1.15;
            letter-spacing: -0.01em;
        }

        a {
            text-decoration: none;
            color: inherit;
        }

        .container {
            width: 100%;
            max-width: 1280px;
            margin: 0 auto;
            padding: 0 24px;
        }

        /* Animations */
        @keyframes gradientSweep {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        @keyframes fadeSlideUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes pointRise {
            0% { transform: translateY(12px) scale(0.8); opacity: 0; }
            60% { transform: translateY(-6px) scale(1.1); opacity: 1; }
            100% { transform: translateY(0) scale(1); opacity: 1; }
        }

        /* Buttons */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-family: var(--font-body);
            font-weight: 700;
            min-height: 48px;
            padding: 0 24px;
            border-radius: var(--radius-full);
            cursor: pointer;
            transition: all 260ms cubic-bezier(0.4, 0, 0.2, 1);
            border: none;
        }

        .btn-primary {
            background-color: var(--accent-cta);
            color: var(--color-canopy);
        }

        .btn-primary:hover {
            background-color: var(--color-sunburst);
            transform: scale(1.02);
            box-shadow: var(--shadow-glow);
        }

        .btn-secondary {
            background-color: var(--bg-surface);
            border: 2px solid var(--color-forest);
            color: var(--color-forest);
        }

        .btn-secondary:hover {
            background-color: var(--color-mist);
            border-color: var(--color-sprout);
        }

        .btn-gradient {
            background: var(--gradient-brand);
            color: var(--color-white);
            box-shadow: var(--shadow-md);
        }

        /* Navbar */
        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 0;
            position: relative;
            z-index: 100;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .logo img{
            max-width: 200px;
            width: 100%;
            height: auto;
            object-fit: contain;
        }

        .nav-links {
            display: flex;
            gap: 32px;
            align-items: center;
            font-weight: 600;
            color: var(--text-secondary);
        }

        .nav-links a:hover {
            color: var(--color-sprout);
        }

        /* Hero Section */
        .hero {
            background: var(--bg-dark);
            color: var(--text-on-dark);
            position: relative;
            overflow: hidden;
            border-radius: 0 0 var(--radius-xl) var(--radius-xl);
            padding: 100px 0 120px 0;
            text-align: center;
        }

        .hero::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background: var(--gradient-brand);
            background-size: 200% 200%;
            animation: gradientSweep 6s ease infinite;
            opacity: 0.2;
            z-index: 1;
        }

        .hero-content {
            position: relative;
            z-index: 2;
            max-width: 800px;
            margin: 0 auto;
        }

        .hero-title {
            font-size: 4rem; /* text-6xl */
            font-weight: 900;
            margin-bottom: 24px;
            animation: fadeSlideUp 0.6s ease-out forwards;
        }
        
        .hero-title span {
            background: var(--gradient-brand);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            display: inline-block;
        }

        .hero-subtitle {
            font-size: 1.25rem;
            color: var(--color-mist);
            margin-bottom: 40px;
            opacity: 0;
            animation: fadeSlideUp 0.6s ease-out 0.2s forwards;
            font-weight: 400;
        }

        .hero-actions {
            display: flex;
            gap: 16px;
            justify-content: center;
            opacity: 0;
            animation: fadeSlideUp 0.6s ease-out 0.4s forwards;
        }

        /* Section Intro */
        .intro-section {
            padding: 80px 0;
            text-align: center;
            max-width: 700px;
            margin: 0 auto;
        }

        .intro-quote {
            font-family: var(--font-display);
            font-size: 2.25rem; /* text-4xl */
            font-weight: 800;
            color: var(--text-secondary);
            margin-bottom: 24px;
            line-height: 1.3;
        }

        .intro-text {
            font-size: 1.125rem;
            color: var(--text-muted);
        }

        /* Features Section */
        .features {
            padding: 80px 0;
        }

        .section-header {
            text-align: center;
            margin-bottom: 64px;
        }

        .section-title {
            font-size: 2.25rem;
            color: var(--text-primary);
            margin-bottom: 16px;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 32px;
        }

        .feature-card {
            background: var(--bg-surface);
            border-radius: 24px 24px 16px 16px; /* Echo logo shape */
            padding: 40px 32px;
            box-shadow: var(--shadow-md);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .feature-card:hover {
            transform: translateY(-8px);
            box-shadow: var(--shadow-lg);
        }
        
        .feature-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 6px;
            background: var(--gradient-brand);
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .feature-card:hover::before {
            opacity: 1;
        }

        .feature-icon {
            width: 64px;
            height: 64px;
            border-radius: var(--radius-md);
            background: var(--bg-page);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 24px;
            font-size: 32px;
        }

        .feature-title {
            font-size: 1.5rem;
            margin-bottom: 16px;
            color: var(--text-primary);
        }

        .feature-desc {
            color: var(--text-muted);
            font-size: 1rem;
        }

        /* Waste Pricing Section */
        .pricing {
            background: var(--bg-surface);
            padding: 100px 0;
            border-top: 1px solid var(--color-smoke);
            border-bottom: 1px solid var(--color-smoke);
        }

        .pricing-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 24px;
            margin-top: 48px;
        }

        .price-card {
            background: var(--bg-page);
            border-radius: var(--radius-md);
            padding: 24px;
            text-align: center;
            box-shadow: var(--shadow-sm);
            border-left: 4px solid var(--color-sprout);
        }

        .price-card.kertas { border-left-color: var(--color-sunburst); }
        .price-card.kaca { border-left-color: var(--color-lime); }
        .price-card.logam { border-left-color: var(--color-fog); }
        .price-card.gelas { border-left-color: var(--color-solar); }

        .price-category {
            font-weight: 700;
            color: var(--text-secondary);
            margin-bottom: 8px;
        }

        .price-value {
            font-family: var(--font-mono);
            font-size: 1.5rem;
            color: var(--text-primary);
            font-weight: 700;
        }

        /* Gamification Preview */
        .gamification {
            padding: 100px 0;
            background: var(--gradient-brand);
            color: var(--color-white);
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .gamification h2 {
            font-size: 2.25rem;
            margin-bottom: 24px;
            color: var(--color-white);
        }
        
        .reward-badge {
            display: inline-block;
            background: var(--color-solar);
            color: var(--color-canopy);
            font-family: var(--font-display);
            font-weight: 800;
            padding: 16px 32px;
            border-radius: var(--radius-full);
            font-size: 1.25rem;
            box-shadow: var(--shadow-glow);
            margin-top: 24px;
            animation: pointRise 1s cubic-bezier(0.34, 1.56, 0.64, 1) forwards;
        }

        /* Footer */
        .footer {
            background: var(--bg-dark);
            color: var(--color-mist);
            padding: 64px 0 32px 0;
        }

        .footer-content {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            flex-wrap: wrap;
            gap: 48px;
            margin-bottom: 48px;
        }

        .footer-brand {
            max-width: 300px;
        }
        
        .footer-logo {
            margin-bottom: 16px;
        }

        .footer-desc {
            color: var(--color-fog);
            font-size: 0.875rem;
        }

        .footer-links h4 {
            color: var(--color-white);
            margin-bottom: 24px;
            font-size: 1.125rem;
        }

        .footer-links ul {
            list-style: none;
            padding: 0;
        }

        .footer-links li {
            margin-bottom: 12px;
        }

        .footer-links a {
            color: var(--color-fog);
            transition: color 0.3s ease;
        }

        .footer-links a:hover {
            color: var(--color-sprout);
        }

        .footer-bottom {
            text-align: center;
            padding-top: 32px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            color: var(--color-fog);
            font-size: 0.875rem;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .hero-title { font-size: 2.25rem; }
            .hero { padding: 60px 0 80px 0; }
            .nav-links { display: none; } /* Simplified for landing page */
            .intro-quote { font-size: 1.5rem; }
            .features-grid { grid-template-columns: 1fr; }
            .footer-content { flex-direction: column; }
        }
    </style>
</head>
<body>

    <!-- Navbar -->
    <div class="container">
        <nav class="navbar">
            <div class="logo">
                <img src="{{ asset('images/logo Realive@3x.png') }}" alt="logo Realive" style="max-width: 200px; width: 100%; height: auto; object-fit: contain;">
            </div>
            <div class="nav-links">
                <a href="#fitur">Fitur</a>
                <a href="#harga">Harga Sampah</a>
                <a href="#eco-poin">Eco Poin</a>
            </div>
            <div style="display: flex; gap: 12px;">
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}" class="btn btn-primary">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-secondary">Masuk</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="btn btn-primary">Daftar</a>
                        @endif
                    @endauth
                @endif
            </div>
        </nav>
    </div>

    <!-- Hero Section -->
    <section class="hero">
        <div class="container hero-content">
            <h1 class="hero-title">Ubah Sampah Jadi <span>Berkah</span> & <span>Hadiah</span></h1>
            <p class="hero-subtitle">Realive membantu Anda menabung sampah dengan mudah. Tukarkan dengan uang tunai atau kumpulkan Eco Poin untuk hadiah menarik. Mari bersama selamatkan bumi!</p>
            <div class="hero-actions">
                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="btn btn-primary">Mulai Daur Ulang</a>
                @endif
                <a href="#fitur" class="btn btn-secondary" style="border-color: rgba(255,255,255,0.2); color: white; background: transparent;">Pelajari Lebih Lanjut</a>
            </div>
        </div>
    </section>

    <!-- Introduction -->
    <section class="intro-section container">
        <h2 class="intro-quote">"Waste comes in warm — it leaves green."</h2>
        <p class="intro-text">Realive bukan sekadar bank sampah biasa. Kami adalah ekosistem yang menghubungkan nasabah dengan mitra pengepul untuk mewujudkan lingkungan yang lebih hijau sambil memberikan manfaat finansial yang nyata.</p>
    </section>

    <!-- Features -->
    <section id="fitur" class="features container">
        <div class="section-header">
            <h2 class="section-title">Kenapa Memilih Realive?</h2>
            <p class="intro-text">Berbagai kemudahan dalam satu platform pintar.</p>
        </div>
        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon" style="color: var(--color-sprout);">🛵</div>
                <h3 class="feature-title">Setor via Pengepul</h3>
                <p class="feature-desc">Tidak sempat ke bank sampah utama? Mitra Pengepul kami siap mendatangi Anda di lapangan. Sistem otomatis menghitung bagi hasil yang adil.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon" style="color: var(--color-sunburst);">💸</div>
                <h3 class="feature-title">Tarik Saldo Instan</h3>
                <p class="feature-desc">Cairkan saldo tabungan Anda mulai dari Rp 100.000 langsung ke rekening Bank (BCA, Mandiri, BRI) atau E-Wallet (GoPay, OVO, Dana).</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon" style="color: var(--color-solar);">🎁</div>
                <h3 class="feature-title">Eco Poin Rewards</h3>
                <p class="feature-desc">Setiap 1 kg sampah = 10 Poin. Kumpulkan poin dan tukarkan dengan sembako atau merchandise eksklusif dari katalog hadiah kami.</p>
            </div>
        </div>
    </section>

    <!-- Pricing -->
    <section id="harga" class="pricing">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Harga Sampah Terkini</h2>
                <p class="intro-text">Taksiran harga beli untuk setiap jenis sampah yang disetorkan ke Realive.</p>
            </div>
            <div class="pricing-grid">
                @if(isset($sampahs) && $sampahs->count() > 0)
                    @foreach($sampahs as $index => $item)
                        @php
                            // Mengatur warna border kiri berdasarkan indeks agar bervariasi sesuai desain
                            $classes = ['', 'kertas', 'logam', 'kaca', 'gelas'];
                            $class = $classes[$index % count($classes)];
                        @endphp
                        <div class="price-card {{ $class }}">
                            <div class="price-category">{{ $item->sampah_name }}</div>
                            <div class="price-value">Rp {{ number_format($item->sampah_harga_kg, 0, ',', '.') }} /kg</div>
                        </div>
                    @endforeach
                @else
                    <div class="price-card">
                        <div class="price-category">Data Belum Tersedia</div>
                        <div class="price-value">-</div>
                    </div>
                @endif
            </div>
        </div>
    </section>

    <!-- Gamification -->
    <section id="eco-poin" class="gamification">
        <div class="container">
            <h2>Tingkatkan Level Penyelamat Bumi!</h2>
            <p style="font-size: 1.125rem; max-width: 600px; margin: 0 auto;">Dapatkan lencana eksklusif dari Pemula hingga Bintang. Makin aktif mendaur ulang, makin banyak hadiah yang menanti.</p>
            <div class="reward-badge">+150 Poin Diperoleh! 🌟</div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-brand">
                    <div class="footer-logo">Realive</div>
                    <p class="footer-desc">Platform Bank Sampah Digital yang menghubungkan Pejuang Ekologis dan Mitra Pengepul untuk bumi yang lebih hijau.</p>
                </div>
                <div class="footer-links">
                    <h4>Layanan</h4>
                    <ul>
                        <li><a href="#fitur">Setor Sampah</a></li>
                        <li><a href="#harga">Harga Sampah</a></li>
                        <li><a href="#eco-poin">Katalog Rewards</a></li>
                        <li><a href="#">Tarik Saldo</a></li>
                    </ul>
                </div>
                <div class="footer-links">
                    <h4>Perusahaan</h4>
                    <ul>
                        <li><a href="#">Tentang Kami</a></li>
                        <li><a href="#">Hubungi Kami</a></li>
                        <li><a href="#">Syarat & Ketentuan</a></li>
                        <li><a href="#">Kebijakan Privasi</a></li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">
                &copy; 2026 Realive Platform. Hak Cipta Dilindungi.
            </div>
        </div>
    </footer>

</body>
</html>
