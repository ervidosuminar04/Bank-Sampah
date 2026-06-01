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
            --bg-dark: var(--color-canopy);
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
            transition: all 200ms cubic-bezier(0.4, 0, 0.2, 1);
            border: none;
        }

        .btn-primary {
            background-color: var(--accent-cta);
            color: var(--color-canopy);
        }

        .btn-primary:hover {
            background-color: var(--color-sunburst);
            transform: translateY(-4px);
            box-shadow: var(--shadow-glow);
        }

        .btn-secondary {
            background-color: transparent;
            border: 2px solid rgba(255,255,255,0.4);
            color: var(--color-white);
            backdrop-filter: blur(4px);
        }

        .btn-secondary:hover {
            background-color: rgba(255,255,255,0.1);
            border-color: var(--color-white);
            transform: translateY(-2px);
        }

        /* Navbar Glassmorphism */
        .navbar-wrapper {
            position: absolute;
            top: 20px;
            left: 0;
            right: 0;
            z-index: 100;
        }
        
        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 16px 32px;
            background: rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-radius: var(--radius-full);
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .logo img {
            max-width: 160px;
            width: 100%;
            height: auto;
            object-fit: contain;
            filter: brightness(0) invert(1);
        }

        .nav-links {
            display: flex;
            gap: 32px;
            align-items: center;
            font-weight: 600;
            color: rgba(255, 255, 255, 0.9);
        }

        .nav-links a:hover {
            color: var(--color-solar);
        }

        /* Hero Section */
        .hero {
            background: var(--bg-dark);
            color: var(--text-on-dark);
            position: relative;
            overflow: hidden;
            border-radius: 0 0 var(--radius-xl) var(--radius-xl);
            padding: 180px 0 140px 0;
        }

        .hero::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background: var(--gradient-brand);
            background-size: 200% 200%;
            animation: gradientSweep 10s ease infinite;
            opacity: 0.15;
            z-index: 1;
        }

        .hero::after {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background-image: radial-gradient(rgba(255, 255, 255, 0.1) 1px, transparent 1px);
            background-size: 30px 30px;
            opacity: 0.4;
            z-index: 2;
        }

        .hero-abstract {
            position: absolute;
            right: -10%;
            top: -10%;
            width: 60%;
            height: 120%;
            background: radial-gradient(circle, rgba(125,184,37,0.4) 0%, rgba(45,106,45,0) 70%);
            z-index: 2;
            pointer-events: none;
            filter: blur(60px);
        }

        .hero-content {
            position: relative;
            z-index: 10;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 48px;
        }
        
        .hero-text-content {
            max-width: 650px;
        }

        .hero-title {
            font-size: 4.5rem;
            font-weight: 900;
            margin-bottom: 24px;
            line-height: 1.1;
            animation: fadeSlideUp 0.6s ease-out forwards;
        }
        
        .hero-title span {
            background: var(--gradient-warm);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            display: inline-block;
        }

        .hero-subtitle {
            font-size: 1.25rem;
            color: rgba(255, 255, 255, 0.85);
            margin-bottom: 40px;
            opacity: 0;
            animation: fadeSlideUp 0.6s ease-out 0.2s forwards;
            font-weight: 400;
            line-height: 1.6;
        }

        .hero-actions {
            display: flex;
            gap: 16px;
            opacity: 0;
            animation: fadeSlideUp 0.6s ease-out 0.4s forwards;
        }

        .hero-visual {
            flex: 1;
            opacity: 0;
            animation: fadeSlideUp 0.8s ease-out 0.4s forwards;
            position: relative;
            z-index: 10;
        }
        
        .glass-card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: var(--radius-lg);
            padding: 32px;
            box-shadow: var(--shadow-lg);
        }

        /* Partners / Social Proof */
        .partners {
            padding: 40px 0;
            background: var(--bg-surface);
            border-bottom: 1px solid var(--color-smoke);
        }
        
        .partners-inner {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 48px;
            flex-wrap: wrap;
            opacity: 0.6;
        }
        
        .partner-logo {
            font-family: var(--font-display);
            font-weight: 900;
            font-size: 1.5rem;
            color: var(--text-muted);
            letter-spacing: -0.02em;
            transition: all 0.3s ease;
            cursor: pointer;
        }
        
        .partner-logo:hover {
            color: var(--color-canopy);
            opacity: 1;
        }
        
        .partners-title {
            font-size: 0.875rem;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: var(--text-muted);
            font-weight: 700;
            text-align: center;
            margin-bottom: 24px;
        }

        /* Section Intro */
        .intro-section {
            padding: 100px 0;
        }

        .intro-grid {
            display: grid;
            grid-template-columns: 1.2fr 1fr;
            gap: 64px;
            align-items: center;
        }

        .intro-quote {
            font-family: var(--font-display);
            font-size: 2.75rem;
            font-weight: 800;
            color: var(--color-canopy);
            line-height: 1.2;
            margin-bottom: 24px;
        }

        .intro-text {
            font-size: 1.125rem;
            color: var(--text-muted);
            line-height: 1.8;
            border-left: 4px solid var(--color-sprout);
            padding-left: 24px;
        }

        /* Features Section (3x1 Grid) */
        .features {
            padding: 80px 0 120px 0;
            background: var(--bg-surface);
        }

        .section-header {
            text-align: center;
            margin-bottom: 64px;
        }

        .section-title {
            font-size: 2.5rem;
            color: var(--text-primary);
            margin-bottom: 16px;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 32px;
        }

        .feature-card {
            background: var(--bg-page);
            border-radius: 24px 24px 16px 16px;
            padding: 40px 32px;
            transition: transform 0.3s ease, box-shadow 0.3s ease, border-color 0.3s ease;
            position: relative;
            overflow: hidden;
            border: 1px solid var(--color-smoke);
        }

        .feature-card:hover {
            transform: translateY(-8px);
            box-shadow: var(--shadow-lg);
            border-color: var(--color-sprout);
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
            width: 72px;
            height: 72px;
            border-radius: var(--radius-md);
            background: var(--color-white);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 24px;
            font-size: 32px;
            box-shadow: var(--shadow-sm);
        }

        .feature-title {
            font-size: 1.5rem;
            margin-bottom: 16px;
            color: var(--text-primary);
        }

        .feature-desc {
            color: var(--text-muted);
            font-size: 1rem;
            line-height: 1.6;
        }

        /* Waste Pricing Section */
        .pricing {
            background: var(--bg-page);
            padding: 100px 0;
        }

        .pricing-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 24px;
            margin-top: 48px;
        }

        .price-card {
            background: var(--bg-surface);
            border-radius: var(--radius-md);
            padding: 32px 24px;
            text-align: center;
            box-shadow: var(--shadow-sm);
            border: 2px solid transparent;
            transition: all 0.3s ease;
        }

        .price-card:hover {
            transform: translateY(-6px);
            box-shadow: var(--shadow-lg);
        }

        .price-card.kertas { border-bottom: 4px solid var(--color-sunburst); }
        .price-card.kaca { border-bottom: 4px solid var(--color-lime); }
        .price-card.logam { border-bottom: 4px solid var(--color-fog); }
        .price-card.gelas { border-bottom: 4px solid var(--color-solar); }
        
        .price-card.kertas:hover { border-color: var(--color-sunburst); }
        .price-card.kaca:hover { border-color: var(--color-lime); }
        .price-card.logam:hover { border-color: var(--color-fog); }
        .price-card.gelas:hover { border-color: var(--color-solar); }

        .price-category {
            font-weight: 800;
            font-size: 1.125rem;
            color: var(--text-secondary);
            margin-bottom: 12px;
        }

        .price-value {
            font-family: var(--font-mono);
            font-size: 1.75rem;
            color: var(--text-primary);
            font-weight: 700;
        }

        /* Testimonials */
        .testimonials {
            padding: 80px 0;
            background: var(--bg-surface);
        }

        .testi-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 32px;
            margin-top: 48px;
        }

        .testi-card {
            background: var(--bg-page);
            padding: 32px;
            border-radius: var(--radius-lg);
            border: 1px solid var(--color-smoke);
            position: relative;
        }
        
        .testi-card::before {
            content: '"';
            position: absolute;
            top: 20px;
            right: 24px;
            font-family: var(--font-display);
            font-size: 4rem;
            color: rgba(125, 184, 37, 0.2);
            line-height: 1;
        }

        .testi-text {
            font-size: 1rem;
            color: var(--text-primary);
            line-height: 1.6;
            margin-bottom: 24px;
            font-style: italic;
        }

        .testi-author {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .testi-avatar {
            width: 48px;
            height: 48px;
            border-radius: var(--radius-full);
            background: var(--gradient-brand);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
        }

        .testi-info h4 {
            font-size: 1rem;
            margin-bottom: 2px;
        }
        
        .testi-info p {
            font-size: 0.875rem;
            color: var(--text-muted);
        }

        /* Gamification Preview */
        .gamification {
            padding: 100px 0;
            background: var(--gradient-brand);
            color: var(--color-white);
            text-align: center;
            position: relative;
            overflow: hidden;
            border-radius: var(--radius-xl);
            margin: 40px 24px;
        }
        
        .gamification::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background-image: radial-gradient(rgba(255, 255, 255, 0.1) 2px, transparent 2px);
            background-size: 20px 20px;
        }

        .gamification h2 {
            font-size: 2.5rem;
            margin-bottom: 24px;
            position: relative;
            z-index: 2;
        }
        
        .gamification p {
            position: relative;
            z-index: 2;
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
            margin-top: 32px;
            position: relative;
            z-index: 2;
            animation: pointRise 1s cubic-bezier(0.34, 1.56, 0.64, 1) forwards;
        }

        /* Footer */
        .footer {
            background: var(--bg-dark);
            color: rgba(255, 255, 255, 0.8);
            padding: 80px 0 40px 0;
        }

        .footer-content {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            flex-wrap: wrap;
            gap: 48px;
            margin-bottom: 64px;
        }

        .footer-brand {
            max-width: 350px;
        }
        
        .footer-logo {
            margin-bottom: 24px;
            filter: brightness(0) invert(1);
        }
        
        .footer-logo img {
            max-width: 160px;
        }

        .footer-desc {
            font-size: 1rem;
            line-height: 1.6;
        }

        .footer-links h4 {
            color: var(--color-white);
            margin-bottom: 24px;
            font-size: 1.125rem;
        }

        .footer-links ul {
            list-style: none;
        }

        .footer-links li {
            margin-bottom: 16px;
        }

        .footer-links a {
            color: rgba(255, 255, 255, 0.7);
            transition: color 0.3s ease;
        }

        .footer-links a:hover {
            color: var(--color-solar);
        }

        .footer-bottom {
            text-align: center;
            padding-top: 32px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            font-size: 0.875rem;
            color: rgba(255, 255, 255, 0.5);
        }

        /* Responsive */
        @media (max-width: 1024px) {
            .features-grid, .testi-grid { grid-template-columns: repeat(2, 1fr); }
            .hero-title { font-size: 3.5rem; }
        }

        @media (max-width: 768px) {
            .navbar-wrapper { padding: 0 16px; top: 16px; }
            .hero-content { flex-direction: column; text-align: center; }
            .hero-title { font-size: 2.75rem; }
            .hero-actions { justify-content: center; }
            .nav-links { display: none; }
            .intro-grid { grid-template-columns: 1fr; gap: 32px; }
            .features-grid, .testi-grid { grid-template-columns: 1fr; }
            .gamification { margin: 24px 16px; }
        }
    </style>
</head>
<body>

    <!-- Navbar Glassmorphism -->
    <div class="container navbar-wrapper">
        <nav class="navbar">
            <div class="logo">
                <img src="{{ asset('images/white logo@4x.png') }}" alt="logo Realive">
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

    <!-- Hero Section (Halo Effect) -->
    <section class="hero">
        <div class="hero-abstract"></div>
        <div class="container hero-content">
            <div class="hero-text-content">
                <h1 class="hero-title">Ubah Sampah Jadi <span>Berkah</span> & <span>Hadiah</span></h1>
                <p class="hero-subtitle">Realive membantu Anda menabung sampah dengan mudah. Tukarkan dengan uang tunai atau kumpulkan Eco Poin untuk hadiah menarik. Mari bersama selamatkan bumi!</p>
                <div class="hero-actions">
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="btn btn-primary">Mulai Daur Ulang</a>
                    @endif
                    <a href="#fitur" class="btn btn-secondary">Pelajari Lebih Lanjut</a>
                </div>
            </div>
            <div class="hero-visual">
                <div class="glass-card">
                    <div style="display:flex; align-items:center; gap: 24px; margin-bottom: 24px;">
                        <div style="width:56px; height:56px; background:var(--color-solar); border-radius:16px; display:flex; align-items:center; justify-content:center; font-size:24px;">🌟</div>
                        <div>
                            <div style="font-weight:800; font-size:1.25rem;">2.400 kg+</div>
                            <div style="color:rgba(255,255,255,0.7); font-size:0.875rem;">Sampah Didaur Ulang</div>
                        </div>
                    </div>
                    <div style="height:4px; background:rgba(255,255,255,0.1); border-radius:4px; overflow:hidden; margin-bottom:16px;">
                        <div style="width:75%; height:100%; background:var(--gradient-warm);"></div>
                    </div>
                    <div style="font-size:0.875rem; color:rgba(255,255,255,0.7);">Target Penyelamatan Pohon Bulan Ini</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Mitra / Social Proof -->
    <section class="partners">
        <div class="container">
            <div class="partners-title">Mitra Pembayaran & Pengolahan Tersedia</div>
            <div class="partners-inner">
                <div class="partner-logo">BCA</div>
                <div class="partner-logo">Bank Mandiri</div>
                <div class="partner-logo">BRI</div>
                <div class="partner-logo">GoPay</div>
                <div class="partner-logo">OVO</div>
                <div class="partner-logo">DANA</div>
            </div>
        </div>
    </section>

    <!-- Introduction (Reduced Cognitive Load) -->
    <section class="intro-section container">
        <div class="intro-grid">
            <div>
                <h2 class="intro-quote">"Waste comes in warm — it leaves green."</h2>
            </div>
            <div>
                <p class="intro-text">Realive bukan sekadar bank sampah biasa. Kami adalah ekosistem yang menghubungkan nasabah dengan mitra pengepul untuk mewujudkan lingkungan yang lebih hijau sambil memberikan manfaat finansial yang nyata untuk semua.</p>
            </div>
        </div>
    </section>

    <!-- Features (3x1 Grid) -->
    <section id="fitur" class="features">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Kenapa Memilih Realive?</h2>
                <p style="font-size: 1.125rem; color: var(--text-muted);">Berbagai kemudahan dalam satu platform pintar.</p>
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
        </div>
    </section>

    <!-- Pricing (Interactive Hover) -->
    <section id="harga" class="pricing">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Harga Sampah Terkini</h2>
                <p style="font-size: 1.125rem; color: var(--text-muted);">Taksiran harga beli untuk setiap jenis sampah yang disetorkan ke Realive.</p>
            </div>
            <div class="pricing-grid">
                @if(isset($sampahs) && $sampahs->count() > 0)
                    @foreach($sampahs as $index => $item)
                        @php
                            $classes = ['', 'kertas', 'logam', 'kaca', 'gelas'];
                            $class = $classes[$index % count($classes)];
                        @endphp
                        <div class="price-card {{ $class }}">
                            <div class="price-category">{{ $item->sampah_name }}</div>
                            <div class="price-value">Rp {{ number_format($item->sampah_harga_kg, 0, ',', '.') }} <span style="font-size:1rem;font-weight:400;color:var(--text-muted);">/kg</span></div>
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
    
    <!-- Testimonials (Trust Validation) -->
    <section class="testimonials">
        <div class="container">
            <div class="section-header" style="margin-bottom: 32px;">
                <h2 class="section-title">Kata Mereka Tentang Realive</h2>
            </div>
            <div class="testi-grid">
                <div class="testi-card">
                    <p class="testi-text">"Semenjak pakai Realive, kumpulin sampah plastik jadi lebih semangat karena langsung bisa cair ke dompet digital buat jajan!"</p>
                    <div class="testi-author">
                        <div class="testi-avatar">A</div>
                        <div class="testi-info">
                            <h4>Andini Putri</h4>
                            <p>Nasabah Aktif</p>
                        </div>
                    </div>
                </div>
                <div class="testi-card">
                    <p class="testi-text">"Sistem tarik saldonya cepat dan harga sampah transparan. Fitur Eco Poin juga seru buat ditukar sembako bulanan."</p>
                    <div class="testi-author">
                        <div class="testi-avatar" style="background:var(--color-sunburst);">B</div>
                        <div class="testi-info">
                            <h4>Budi Santoso</h4>
                            <p>Penyetor Rutin</p>
                        </div>
                    </div>
                </div>
                <div class="testi-card">
                    <p class="testi-text">"Sebagai pengepul, aplikasi ini mempermudah pencatatan timbangan di lapangan. Tidak perlu pusing bawa nota kertas lagi."</p>
                    <div class="testi-author">
                        <div class="testi-avatar" style="background:var(--color-canopy);">K</div>
                        <div class="testi-info">
                            <h4>Kang Mamat</h4>
                            <p>Mitra Pengepul</p>
                        </div>
                    </div>
                </div>
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
                    <div class="footer-logo">
                        <img src="{{ asset('images/white logo@4x.png') }}" alt="logo Realive">
                    </div>
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
                &copy; {{ date('Y') }} Realive Platform. Hak Cipta Dilindungi.
            </div>
        </div>
    </footer>

</body>
</html>
