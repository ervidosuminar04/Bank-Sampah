<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Bank Sampah Digital</title>
    <!-- Leaflet CSS & JS for Interactive Map -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        /* Modern Premium Style System */
        :root {
            --primary: #1b5e20;
            --primary-light: #e8f5e9;
            --primary-medium: #81c784;
            --secondary: #2e7d32;
            --accent: #ffb300;
            --danger: #d32f2f;
            --danger-light: #ffebee;
            --dark: #212121;
            --gray-light: #f5f5f5;
            --gray-medium: #e0e0e0;
            --white: #ffffff;
            --shadow: 0 4px 20px rgba(0,0,0,0.06);
            --shadow-hover: 0 8px 30px rgba(27,94,32,0.12);
            --radius: 12px;
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Outfit', sans-serif;
            background-color: #f4f6f5;
            color: var(--dark);
            min-height: 100vh;
            display: flex;
            overflow: hidden; /* Prevent body scroll, scroll elements instead */
        }

        /* App Layout Grid */
        .app-layout {
            display: flex;
            width: 100vw;
            height: 100vh;
        }

        /* Sidebar Style */
        .sidebar {
            width: 280px;
            background: linear-gradient(180deg, var(--primary) 0%, #0c3610 100%);
            color: var(--white);
            display: flex;
            flex-direction: column;
            height: 100%;
            z-index: 100;
            box-shadow: 4px 0 15px rgba(0,0,0,0.1);
            transition: var(--transition);
        }

        .sidebar-brand {
            padding: 24px;
            font-size: 22px;
            font-weight: 800;
            letter-spacing: 0.5px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            display: flex;
            align-items: center;
        }

        .sidebar-brand span {
            margin-right: 10px;
            font-size: 26px;
        }

        /* Profile Card inside Sidebar */
        .sidebar-profile {
            padding: 20px 24px;
            display: flex;
            align-items: center;
            gap: 12px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            background-color: rgba(255, 255, 255, 0.03);
        }

        .profile-avatar {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            background-color: var(--white);
            color: var(--primary);
            font-size: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        }

        .profile-info {
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        .profile-name {
            font-size: 14px;
            font-weight: 700;
            white-space: nowrap;
            text-overflow: ellipsis;
            overflow: hidden;
        }

        .profile-role-badge {
            font-size: 10px;
            background-color: var(--accent);
            color: var(--dark);
            padding: 1px 8px;
            border-radius: 10px;
            font-weight: 700;
            text-transform: uppercase;
            align-self: flex-start;
            margin-top: 3px;
        }

        /* Sidebar Navigation Menu */
        .sidebar-menu {
            list-style: none;
            padding: 20px 12px;
            flex: 1;
            overflow-y: auto;
        }

        .sidebar-menu li {
            margin-bottom: 6px;
        }

        .menu-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 16px;
            color: rgba(255, 255, 255, 0.75);
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
            border-radius: 8px;
            transition: var(--transition);
            cursor: pointer;
        }

        .menu-link:hover {
            color: var(--white);
            background-color: rgba(255, 255, 255, 0.08);
        }

        .menu-link.active {
            color: var(--white);
            background-color: var(--secondary);
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        .sidebar-footer {
            padding: 20px 24px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        .btn-logout-sidebar {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            background-color: var(--danger);
            color: var(--white);
            width: 100%;
            border: none;
            padding: 10px;
            border-radius: 8px;
            font-size: 13.5px;
            font-weight: 700;
            text-decoration: none;
            transition: var(--transition);
        }

        .btn-logout-sidebar:hover {
            background-color: #b71c1c;
        }

        /* Main Viewport Panel */
        .main-panel {
            flex: 1;
            display: flex;
            flex-direction: column;
            height: 100%;
            overflow: hidden;
        }

        /* Panel Header */
        .panel-header {
            background-color: var(--white);
            border-bottom: 1px solid var(--gray-medium);
            padding: 16px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            z-index: 10;
        }

        .header-title {
            font-size: 18px;
            font-weight: 700;
            color: var(--primary);
        }

        .mobile-toggle {
            display: none;
            background: none;
            border: none;
            font-size: 24px;
            color: var(--primary);
            cursor: pointer;
            margin-right: 15px;
        }

        /* Panel Content Area */
        .panel-content {
            flex: 1;
            padding: 30px;
            overflow-y: auto;
            position: relative;
        }

        /* Tab Content Section Container */
        .tab-content {
            display: none;
            animation: fadeIn 0.4s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(8px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Card Elements */
        .welcome-section {
            background: linear-gradient(135deg, #e8f5e9 0%, #c8e6c9 100%);
            border-radius: var(--radius);
            padding: 24px 30px;
            margin-bottom: 30px;
            box-shadow: var(--shadow);
            position: relative;
            overflow: hidden;
        }

        .welcome-text h1 {
            font-size: 24px;
            font-weight: 800;
            color: #0d5c14;
            margin-bottom: 6px;
        }

        .welcome-text p {
            color: #437046;
            font-size: 14.5px;
        }

        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .card-stat {
            background-color: var(--white);
            border-radius: var(--radius);
            padding: 20px;
            box-shadow: var(--shadow);
            display: flex;
            align-items: center;
            gap: 16px;
            transition: var(--transition);
            border: 1px solid rgba(0,0,0,0.02);
        }

        .card-stat:hover {
            transform: translateY(-3px);
            box-shadow: var(--shadow-hover);
        }

        .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
        }

        .stat-content h3 {
            font-size: 11px;
            color: #888;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            margin-bottom: 4px;
            font-weight: 700;
        }

        .stat-content p {
            font-size: 18px;
            font-weight: 800;
            color: var(--dark);
        }

        .icon-accent-1 { background-color: #e8f5e9; color: #2e7d32; }
        .icon-accent-2 { background-color: #e3f2fd; color: #1e88e5; }
        .icon-accent-3 { background-color: #fff8e1; color: #ff8f00; }
        .icon-accent-4 { background-color: #f3e5f5; color: #8e24aa; }

        /* UI Blocks */
        .ui-block {
            background-color: var(--white);
            border-radius: var(--radius);
            padding: 24px;
            box-shadow: var(--shadow);
            border: 1px solid rgba(0,0,0,0.02);
            margin-bottom: 25px;
        }

        .block-title {
            font-size: 16px;
            font-weight: 700;
            color: var(--primary);
            margin-bottom: 18px;
            padding-bottom: 10px;
            border-bottom: 2px solid var(--primary-light);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        /* E-Gamifikasi Center */
        .gamifikasi-card {
            background: linear-gradient(135deg, #2e7d32 0%, #1b5e20 100%);
            color: var(--white);
            border-radius: var(--radius);
            padding: 24px;
            box-shadow: var(--shadow);
            margin-bottom: 25px;
        }

        .gamifikasi-title {
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: 700;
            margin-bottom: 12px;
            color: var(--primary-medium);
        }

        .gamifikasi-points {
            font-size: 32px;
            font-weight: 800;
            margin-bottom: 5px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .gamifikasi-points span { font-size: 16px; font-weight: 500; opacity: 0.8; }

        .level-progress {
            margin-top: 12px;
            margin-bottom: 20px;
        }

        .level-meta {
            display: flex;
            justify-content: space-between;
            font-size: 11.5px;
            margin-bottom: 6px;
            opacity: 0.9;
        }

        .level-bar {
            background-color: rgba(255, 255, 255, 0.2);
            height: 10px;
            border-radius: 5px;
            overflow: hidden;
        }

        .level-fill {
            background: linear-gradient(90deg, var(--accent) 0%, #ffe082 100%);
            height: 100%;
            border-radius: 5px;
            transition: width 0.5s ease-in-out;
        }

        /* Badges Collection */
        .badges-title {
            font-size: 11.5px;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            margin-bottom: 10px;
            font-weight: 600;
            opacity: 0.85;
        }

        .badges-row {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
        }

        .badge-circle {
            width: 46px;
            height: 46px;
            border-radius: 50%;
            background-color: rgba(255, 255, 255, 0.15);
            border: 2px dashed rgba(255, 255, 255, 0.3);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            position: relative;
            cursor: pointer;
            transition: var(--transition);
        }

        .badge-circle.active {
            background-color: var(--white);
            border: 2px solid var(--accent);
            box-shadow: 0 4px 10px rgba(0,0,0,0.15);
        }

        .badge-circle:hover .tooltip {
            visibility: visible;
            opacity: 1;
        }

        .tooltip {
            visibility: hidden;
            background-color: var(--dark);
            color: #fff;
            text-align: center;
            padding: 5px 10px;
            border-radius: 6px;
            position: absolute;
            z-index: 1;
            bottom: 125%;
            left: 50%;
            transform: translateX(-50%);
            opacity: 0;
            transition: opacity 0.3s;
            font-size: 11px;
            white-space: nowrap;
        }

        /* Form Controls */
        .form-group {
            margin-bottom: 16px;
        }

        .form-group label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: #555;
            margin-bottom: 6px;
        }

        .form-control {
            width: 100%;
            padding: 10px 14px;
            border: 1px solid var(--gray-medium);
            border-radius: 8px;
            font-size: 13.5px;
            outline: none;
            font-family: inherit;
            transition: var(--transition);
        }

        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(27,94,32,0.12);
        }

        /* Buttons */
        .btn-submit {
            background-color: var(--primary);
            color: var(--white);
            border: none;
            padding: 11px 20px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            width: 100%;
            transition: var(--transition);
        }

        .btn-submit:hover {
            background-color: var(--secondary);
        }

        .btn-action-sm {
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 11.5px;
            font-weight: 700;
            text-decoration: none;
            cursor: pointer;
            border: none;
            display: inline-block;
            transition: var(--transition);
        }

        .btn-success { background-color: var(--primary-light); color: var(--primary); }
        .btn-success:hover { background-color: var(--primary-medium); color: var(--white); }
        
        .btn-danger { background-color: var(--danger-light); color: var(--danger); }
        .btn-danger:hover { background-color: var(--danger); color: var(--white); }

        .btn-secondary { background-color: var(--gray-light); color: #555; border: 1px solid var(--gray-medium); }
        .btn-secondary:hover { background-color: var(--gray-medium); }

        /* Table Style */
        .table-wrap {
            width: 100%;
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13.5px;
            text-align: left;
        }

        th {
            background-color: var(--gray-light);
            padding: 12px;
            font-weight: 600;
            color: #666;
            border-bottom: 2px solid var(--gray-medium);
        }

        td {
            padding: 12px;
            border-bottom: 1px solid var(--gray-light);
        }

        tr:hover td {
            background-color: #fafdfb;
        }

        /* GPS Geolokasi Module */
        .gps-section {
            background-color: #eef7f2;
            border: 1px solid #cce6d5;
            border-radius: var(--radius);
            padding: 20px;
        }

        .gps-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            flex-wrap: wrap;
            gap: 12px;
        }

        .gps-btn {
            background-color: var(--secondary);
            color: var(--white);
            border: none;
            padding: 9px 18px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 700;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: var(--transition);
        }

        .gps-btn:hover {
            background-color: var(--primary);
        }

        .gps-list {
            display: flex;
            flex-direction: column;
            gap: 12px;
            margin-top: 15px;
        }

        .gps-item {
            background-color: var(--white);
            border: 1px solid rgba(0,0,0,0.05);
            padding: 14px 18px;
            border-radius: 8px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 6px rgba(0,0,0,0.02);
            flex-wrap: wrap;
            gap: 12px;
        }

        .gps-meta h4 {
            font-size: 14.5px;
            font-weight: 700;
            color: var(--primary);
            margin-bottom: 3px;
        }

        .gps-meta p {
            font-size: 12px;
            color: #666;
        }

        .status-badge {
            font-size: 9.5px;
            padding: 2px 8px;
            border-radius: 12px;
            font-weight: 700;
            text-transform: uppercase;
        }

        .status-badge.open { background-color: #e8f5e9; color: #2e7d32; }
        .status-badge.closed { background-color: #fee2e2; color: #c62828; }

        .distance-badge {
            font-size: 12.5px;
            font-weight: 700;
            background-color: #e3f2fd;
            color: #1565c0;
            padding: 5px 12px;
            border-radius: 20px;
        }

        /* Alert styling */
        .alert {
            padding: 16px 20px;
            border-radius: var(--radius);
            margin-bottom: 25px;
            font-size: 14px;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 12px;
            box-shadow: var(--shadow);
            animation: slideDown 0.4s ease-out;
        }

        .alert-success { background-color: var(--primary-light); border-left: 5px solid var(--secondary); color: #1b5e20; }
        .alert-error { background-color: var(--danger-light); border-left: 5px solid var(--danger); color: #b71c1c; }

        /* Responsive Mobile Layout adjustments */
        @media (max-width: 768px) {
            .sidebar {
                position: fixed;
                left: -280px;
                top: 0;
                bottom: 0;
                height: 100%;
            }

            .sidebar.mobile-active {
                left: 0;
            }

            .mobile-toggle {
                display: block;
            }
        }
    </style>
</head>
<body>

    <div class="app-layout">
        
        <!-- ======================================================= -->
        <!--                   🌿 LEFT SIDEBAR                        -->
        <!-- ======================================================= -->
        <aside class="sidebar">
            <div class="sidebar-brand">
                <span>🌿</span> Bank Sampah
            </div>
            
            <div class="sidebar-profile">
                <div class="profile-avatar">
                    {{ substr($userType === 'admin' ? $user->admin_nama : $user->nasabah_nama, 0, 1) }}
                </div>
                <div class="profile-info">
                    <span class="profile-name">{{ $userType === 'admin' ? $user->admin_nama : $user->nasabah_nama }}</span>
                    <span class="profile-role-badge">{{ $userType }}</span>
                </div>
            </div>

            <ul class="sidebar-menu">
                @if($userType === 'admin')
                    <!-- Admin Options -->
                    <li>
                        <a class="menu-link active" data-tab="tab-overview" onclick="switchTab(event, 'tab-overview')">
                            🏠 Ringkasan
                        </a>
                    </li>
                    <li>
                        <a class="menu-link" data-tab="tab-persetujuan" onclick="switchTab(event, 'tab-persetujuan')">
                            🔔 Persetujuan
                        </a>
                    </li>
                    <li>
                        <a class="menu-link" data-tab="tab-pengepul" onclick="switchTab(event, 'tab-pengepul')">
                            🚛 Kelola Pengepul
                        </a>
                    </li>
                    <li>
                        <a class="menu-link" data-tab="tab-sampah" onclick="switchTab(event, 'tab-sampah')">
                            🏷️ Master Sampah
                        </a>
                    </li>
                    <li>
                        <a class="menu-link" data-tab="tab-geolokasi" onclick="switchTab(event, 'tab-geolokasi')">
                            📍 Master Geolokasi
                        </a>
                    </li>
                    <li>
                        <a class="menu-link" data-tab="tab-laporan" onclick="switchTab(event, 'tab-laporan')">
                            📊 Cetak Laporan
                        </a>
                    </li>
                    <li>
                        <a class="menu-link" href="{{ route('admin.setoran.index') }}" style="display:flex;justify-content:space-between;align-items:center;">
                            💸 Setoran Pengepul
                            @if(($setoranMenunggu ?? 0) > 0)
                                <span style="background:#d32f2f;color:#fff;border-radius:10px;padding:1px 8px;font-size:11px;">
                                    {{ $setoranMenunggu }}
                                </span>
                            @endif
                        </a>
                    </li>
                    <li>
                        <a class="menu-link" href="{{ route('admin.pencairan.index') }}">
                            💳 Pencairan Nasabah
                        </a>
                    </li>
                    <li>
                        <a class="menu-link" href="{{ route('admin.penukaran.index') }}" style="display:flex;justify-content:space-between;align-items:center;">
                            🎁 Penukaran & Hadiah
                            @if(($rewardsMenunggu ?? 0) > 0)
                                <span style="background:#ffb300;color:#212121;border-radius:10px;padding:1px 8px;font-size:11px;font-weight:700;">
                                    {{ $rewardsMenunggu }}
                                </span>
                            @endif
                        </a>
                    </li>
                @else
                    <!-- Nasabah Options -->
                    <li>
                        <a class="menu-link active" data-tab="tab-overview" onclick="switchTab(event, 'tab-overview')">
                            🏠 Ringkasan Akun
                        </a>
                    </li>
                    <li>
                        <a class="menu-link" data-tab="tab-gps" onclick="switchTab(event, 'tab-gps')">
                            📍 Cari Pengepul (GPS)
                        </a>
                    </li>
                    <li>
                        <a class="menu-link" data-tab="tab-tarik" onclick="switchTab(event, 'tab-tarik')">
                            💸 Tarik Tabungan
                        </a>
                    </li>
                    <li>
                        <a class="menu-link" data-tab="tab-riwayat" onclick="switchTab(event, 'tab-riwayat')">
                            📈 Riwayat Transaksi
                        </a>
                    </li>
                    <li>
                        <a class="menu-link" data-tab="tab-rewards" onclick="switchTab(event, 'tab-rewards')">
                            🎁 Tukar Eco Poin
                        </a>
                    </li>
                @endif
            </ul>

            <div class="sidebar-footer">
                <a href="{{ url('/logout') }}" class="btn-logout-sidebar">🚪 Logout</a>
            </div>
        </aside>

        <!-- ======================================================= -->
        <!--                 📁 RIGHT PANEL CONTENT                  -->
        <!-- ======================================================= -->
        <main class="main-panel">
            
            <header class="panel-header">
                <div style="display:flex; align-items:center;">
                    <button class="mobile-toggle" onclick="toggleSidebar()">☰</button>
                    <div class="header-title">Bank Sampah Digital</div>
                </div>
                <div class="role-badge" style="background-color: var(--primary-light); color: var(--primary); border:none; padding:6px 14px; border-radius:20px; font-weight:700;">
                    Otoritas: {{ ucfirst($userType) }}
                </div>
            </header>

            <div class="panel-content">
                
                <!-- Alerts (Flash Messages) -->
                @if(session('success'))
                    <div class="alert alert-success">
                        <span>✅</span> {{ session('success') }}
                    </div>
                @endif
                @if(session('error'))
                    <div class="alert alert-error">
                        <span>⚠️</span> {{ session('error') }}
                    </div>
                @endif
                @if($errors->any())
                    <div class="alert alert-error">
                        <span>⚠️</span>
                        <ul style="padding-left: 15px; margin: 0;">
                            @foreach($errors->all() as $err)
                                <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if($userType === 'admin')
                    <!-- ======================================================= -->
                    <!--                   👨💼 ADMIN SECTIONS                    -->
                    <!-- ======================================================= -->

                    <!-- 1. OVERVIEW TAB -->
                    <div id="tab-overview" class="tab-content" style="display:block;">
                        <div class="welcome-section">
                            <div class="welcome-text">
                                <h1>Selamat Datang Kembali, Admin!</h1>
                                <p>Gunakan sidebar di sebelah kiri untuk mengakses pengelolaan pengepul, verifikasi nasabah, pengaturan master data, dan cetak laporan.</p>
                            </div>
                        </div>

                        <!-- Statistics -->
                        <div class="stats-grid">
                            <div class="card-stat">
                                <div class="stat-icon icon-accent-1">👥</div>
                                <div class="stat-content">
                                    <h3>Nasabah Aktif</h3>
                                    <p>{{ $totalNasabah }} Orang</p>
                                </div>
                            </div>
                            <div class="card-stat">
                                <div class="stat-icon icon-accent-2">📦</div>
                                <div class="stat-content">
                                    <h3>Sampah Terkumpul</h3>
                                    <p>{{ number_format($totalBeratSampahBulanIni, 2, ',', '.') }} kg</p>
                                </div>
                            </div>
                            <div class="card-stat">
                                <div class="stat-icon icon-accent-3">📥</div>
                                <div class="stat-content">
                                    <h3>Total Kas Masuk</h3>
                                    <p>Rp {{ number_format($totalKasMasuk, 0, ',', '.') }}</p>
                                </div>
                            </div>
                            <div class="card-stat">
                                <div class="stat-icon icon-accent-4">📤</div>
                                <div class="stat-content">
                                    <h3>Total Kas Keluar</h3>
                                    <p>Rp {{ number_format($totalKasKeluar, 0, ',', '.') }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="ui-block">
                            <h2 class="block-title">Nasabah Baru Bergabung</h2>
                            <div class="table-wrap">
                                @if($nasabahs->isEmpty())
                                    <p style="color: #888; text-align: center; padding: 20px 0;">Belum ada nasabah terdaftar.</p>
                                @else
                                    <table>
                                        <thead>
                                            <tr>
                                                <th>Nama Nasabah</th>
                                                <th>Username</th>
                                                <th>Nomor Telepon</th>
                                                <th>Email</th>
                                                <th>Tanggal Daftar</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($nasabahs as $n)
                                                <tr>
                                                    <td><strong>{{ $n->nasabah_nama }}</strong></td>
                                                    <td><code>{{ $n->nasabah_username }}</code></td>
                                                    <td>{{ $n->nasabah_telepon }}</td>
                                                    <td>{{ $n->nasabah_email }}</td>
                                                    <td>{{ \Carbon\Carbon::parse($n->nasabah_tgl_daftar)->format('d M Y') }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                @endif
                            </div>
                        </div>
                    </div>


                    <!-- 2. KELOLA PENGEPUL TAB -->
                    <div id="tab-pengepul" class="tab-content">

                        <!-- Form Tambah Pengepul -->
                        <div class="ui-block" style="margin-bottom:24px;">
                            <h2 class="block-title">🚛 Tambah Akun Pengepul Baru</h2>
                            <form method="POST" action="{{ route('admin.pengepul.store') }}">
                                @csrf
                                <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
                                    <div class="form-group">
                                        <label>Nama Pengepul</label>
                                        <input type="text" name="nama" class="form-control" placeholder="Contoh: Budi Santoso" required>
                                    </div>
                                    <div class="form-group">
                                        <label>Nomor Telepon</label>
                                        <input type="text" name="telepon" class="form-control" placeholder="08xxxxxxxxxx">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Alamat</label>
                                    <input type="text" name="alamat" class="form-control" placeholder="Jl. Contoh No. 1, Kel. Contoh" required>
                                </div>
                                <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:16px;">
                                    <div class="form-group">
                                        <label>Username (untuk login)</label>
                                        <input type="text" name="username" class="form-control" placeholder="Contoh: pengepul_budi" required>
                                    </div>
                                    <div class="form-group">
                                        <label>Password</label>
                                        <input type="password" name="password" class="form-control" placeholder="Min. 6 karakter" required>
                                    </div>
                                    <div class="form-group">
                                        <label>Status Aktif</label>
                                        <select name="status_aktif" class="form-control">
                                            <option value="1">Aktif</option>
                                            <option value="0">Nonaktif</option>
                                        </select>
                                    </div>
                                </div>
                                <button type="submit" class="btn-submit" style="width:auto;padding:10px 28px;">
                                    ➕ Tambah Pengepul
                                </button>
                            </form>
                        </div>

                        <!-- Daftar Pengepul -->
                        <div class="ui-block">
                            <h2 class="block-title">📋 Daftar Pengepul Terdaftar</h2>
                            <div class="table-wrap">
                                @if($allPengepul->isEmpty())
                                    <p style="color:#888;text-align:center;padding:20px 0;">Belum ada pengepul terdaftar.</p>
                                @else
                                    <table>
                                        <thead>
                                            <tr>
                                                <th>Nama</th>
                                                <th>Username</th>
                                                <th>Alamat</th>
                                                <th>Telepon</th>
                                                <th>Koordinat GPS</th>
                                                <th>Status</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($allPengepul as $pg)
                                            <tr>
                                                <td><strong>{{ $pg->nama }}</strong></td>
                                                <td><code>{{ $pg->username }}</code></td>
                                                <td>{{ $pg->alamat }}</td>
                                                <td>{{ $pg->telepon ?? '-' }}</td>
                                                <td>
                                                    @if($pg->latitude && $pg->longitude)
                                                        <code style="font-size:11px;">{{ $pg->latitude }}, {{ $pg->longitude }}</code>
                                                    @else
                                                        <span style="color:#aaa; font-style:italic; font-size:11.5px;">Belum aktif (GPS)</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <span class="status-badge {{ $pg->status_aktif ? 'open' : 'closed' }}">
                                                        {{ $pg->status_aktif ? 'Aktif' : 'Nonaktif' }}
                                                    </span>
                                                </td>
                                                <td style="display:flex;gap:6px;flex-wrap:wrap;">
                                                    <!-- Tombol Edit (toggle form inline) -->
                                                    <button class="btn-action-sm btn-success"
                                                        onclick="toggleEditPengepul({{ $pg->id }})">✏️ Edit</button>
                                                    <!-- Tombol Hapus -->
                                                    <form method="POST" action="{{ route('admin.pengepul.delete', $pg->id) }}"
                                                        onsubmit="return confirm('Hapus pengepul {{ $pg->nama }}?')">
                                                        @csrf
                                                        <button type="submit" class="btn-action-sm btn-danger">🗑️ Hapus</button>
                                                    </form>
                                                </td>
                                            </tr>
                                            <!-- Form Edit Inline (tersembunyi) -->
                                            <tr id="edit-pengepul-{{ $pg->id }}" style="display:none;background:#f9fdf9;">
                                                <td colspan="7" style="padding:16px;">
                                                    <form method="POST" action="{{ route('admin.pengepul.update', $pg->id) }}">
                                                        @csrf
                                                        <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:12px;">
                                                            <div class="form-group" style="margin:0;">
                                                                <label style="font-size:12px;">Nama</label>
                                                                <input type="text" name="nama" class="form-control" value="{{ $pg->nama }}" required>
                                                            </div>
                                                            <div class="form-group" style="margin:0;">
                                                                <label style="font-size:12px;">Telepon</label>
                                                                <input type="text" name="telepon" class="form-control" value="{{ $pg->telepon }}">
                                                            </div>
                                                            <div class="form-group" style="margin:0;">
                                                                <label style="font-size:12px;">Username</label>
                                                                <input type="text" name="username" class="form-control" value="{{ $pg->username }}" required>
                                                            </div>
                                                        </div>
                                                        <div style="display:grid;grid-template-columns:2fr 1fr 1fr;gap:12px;margin-top:8px;">
                                                            <div class="form-group" style="margin:0;">
                                                                <label style="font-size:12px;">Alamat</label>
                                                                <input type="text" name="alamat" class="form-control" value="{{ $pg->alamat }}" required>
                                                            </div>
                                                            <div class="form-group" style="margin:0;">
                                                                <label style="font-size:12px;">Password baru (kosongkan jika tidak diubah)</label>
                                                                <input type="password" name="password" class="form-control" placeholder="Opsional">
                                                            </div>
                                                            <div class="form-group" style="margin:0;">
                                                                <label style="font-size:12px;">Status</label>
                                                                <select name="status_aktif" class="form-control">
                                                                    <option value="1" {{ $pg->status_aktif ? 'selected' : '' }}>Aktif</option>
                                                                    <option value="0" {{ !$pg->status_aktif ? 'selected' : '' }}>Nonaktif</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div style="margin-top:10px;display:flex;gap:8px;">
                                                            <button type="submit" class="btn-action-sm btn-success">💾 Simpan</button>
                                                            <button type="button" class="btn-action-sm btn-secondary"
                                                                onclick="toggleEditPengepul({{ $pg->id }})">Batal</button>
                                                        </div>
                                                    </form>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- 3. PERSETUJUAN & VERIFIKASI TAB -->
                    <div id="tab-persetujuan" class="tab-content">
                        <div class="ui-block">
                            <h2 class="block-title">🔔 Persetujuan Pendaftaran Nasabah Baru (Pending)</h2>
                            <div class="table-wrap" style="margin-bottom: 30px;">
                                @if($pendingNasabahs->isEmpty())
                                    <p style="font-size: 13.5px; color: #888; text-align: center; padding: 15px 0;">Tidak ada pendaftaran baru yang tertunda.</p>
                                @else
                                    <table>
                                        <thead>
                                            <tr>
                                                <th>Nama Lengkap</th>
                                                <th>NIK Identitas</th>
                                                <th>Alamat Domisili</th>
                                                <th>Telepon</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($pendingNasabahs as $pn)
                                                <tr>
                                                    <td><strong>{{ $pn->nasabah_nama }}</strong></td>
                                                    <td><code>{{ $pn->nasabah_nik }}</code></td>
                                                    <td>{{ $pn->nasabah_alamat }}</td>
                                                    <td>{{ $pn->nasabah_telepon }}</td>
                                                    <td>
                                                        <form method="POST" action="{{ route('admin.verifikasi', $pn->id_nasabah) }}" style="display:inline;">
                                                            @csrf
                                                            <button type="submit" class="btn-action-sm btn-success">✅ Setujui</button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                @endif
                            </div>

                            <h2 class="block-title">🔔 Persetujuan Pengajuan Tarik Uang</h2>
                            <div class="table-wrap">
                                @if($pendingTarikRequests->isEmpty())
                                    <p style="font-size: 13.5px; color: #888; text-align: center; padding: 15px 0;">Tidak ada pengajuan penarikan dana tertunda.</p>
                                @else
                                    <table>
                                        <thead>
                                            <tr>
                                                <th>Nama Nasabah</th>
                                                <th>Tanggal Pengajuan</th>
                                                <th>Jumlah Pengambilan</th>
                                                <th>Aksi Tindakan</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($pendingTarikRequests as $tr)
                                                <tr>
                                                    <td><strong>{{ $tr->nasabah->nasabah_nama }}</strong></td>
                                                    <td>{{ \Carbon\Carbon::parse($tr->tarik_tanggal)->format('d M Y H:i') }}</td>
                                                    <td><strong style="color:var(--danger)">Rp {{ number_format($tr->tarik_jumlah, 0, ',', '.') }}</strong></td>
                                                    <td>
                                                        <form method="POST" action="{{ route('admin.persetujuan_tarik', [$tr->id_tarik, 'setuju']) }}" style="display:inline;">
                                                            @csrf
                                                            <button type="submit" class="btn-action-sm btn-success">✅ Setujui</button>
                                                        </form>
                                                        <form method="POST" action="{{ route('admin.persetujuan_tarik', [$tr->id_tarik, 'tolak']) }}" style="display:inline; margin-left: 5px;">
                                                            @csrf
                                                            <button type="submit" class="btn-action-sm btn-danger">❌ Tolak</button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- 4. MASTER SAMPAH TAB -->
                    <div id="tab-sampah" class="tab-content">
                        <div class="ui-block">
                            <h2 class="block-title">
                                <span>🏷️ Manajemen Master Harga Sampah</span>
                                <button onclick="openAddSampahForm()" class="btn-action-sm btn-success">➕ Tambah Sampah Baru</button>
                            </h2>
                            <p style="font-size:13px; color:#666; margin-bottom:20px;">Ubah nilai Rupiah per kg jika harga pasar sampah naik/turun, atau tambahkan jenis sampah baru.</p>
                            
                            <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 30px;">
                                <div class="table-wrap">
                                    <table>
                                        <thead>
                                            <tr>
                                                <th>Nama Bahan</th>
                                                <th>Kategori Jenis</th>
                                                <th>Harga Per kg</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($allSampah as $sam)
                                                <tr>
                                                    <td><strong>{{ $sam->sampah_name }}</strong></td>
                                                    <td>{{ $sam->sampah_jenis }}</td>
                                                    <td><strong style="color:var(--secondary)">Rp {{ number_format($sam->sampah_harga_kg, 0, ',', '.') }}</strong></td>
                                                    <td>
                                                        <button onclick="openEditPriceForm({{ $sam->id_sampah }}, '{{ $sam->sampah_name }}', {{ $sam->sampah_harga_kg }})" class="btn-action-sm btn-success">✏️ Edit Harga</button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                <!-- Form update price & add new -->
                                <div>
                                    <!-- Edit Harga Form -->
                                    <div id="price_edit_section" style="display:none; background-color: var(--primary-light); padding: 20px; border-radius: var(--radius); border: 1px solid var(--primary-medium);">
                                        <h4 style="font-size:14px; color:var(--primary); margin-bottom:12px; font-weight:700;">Ubah Harga: <span id="price_edit_title"></span></h4>
                                        <form method="POST" action="{{ route('admin.master_sampah.update') }}">
                                            @csrf
                                            <input type="hidden" name="id_sampah" id="price_edit_id">
                                            <div class="form-group">
                                                <label for="price_edit_val">Harga Baru (Rp /kg)</label>
                                                <input type="number" name="sampah_harga_kg" id="price_edit_val" class="form-control" required min="0">
                                            </div>
                                            <div style="display:flex; gap:10px;">
                                                <button type="submit" class="btn-action-sm btn-success" style="padding: 8px 16px;">💾 Simpan</button>
                                                <button type="button" onclick="document.getElementById('price_edit_section').style.display='none'; document.getElementById('price_edit_placeholder').style.display='block';" class="btn-action-sm btn-secondary" style="padding: 8px 16px;">Batal</button>
                                            </div>
                                        </form>
                                    </div>

                                    <!-- Tambah Sampah Baru Form -->
                                    <div id="sampah_add_section" style="display:none; background-color: var(--primary-light); padding: 20px; border-radius: var(--radius); border: 1px solid var(--primary-medium);">
                                        <h4 style="font-size:14px; color:var(--primary); margin-bottom:12px; font-weight:700;">Tambah Sampah Baru</h4>
                                        <form method="POST" action="{{ route('admin.master_sampah.store') }}">
                                            @csrf
                                            <div class="form-group">
                                                <label for="add_sampah_name">Nama Bahan</label>
                                                <input type="text" name="sampah_name" id="add_sampah_name" class="form-control" placeholder="Contoh: Plastik Kresek" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="add_sampah_jenis">Kategori Jenis</label>
                                                <select name="sampah_jenis" id="add_sampah_jenis" class="form-control" required>
                                                    <option value="Plastik">Plastik</option>
                                                    <option value="Kertas">Kertas</option>
                                                    <option value="Logam">Logam</option>
                                                    <option value="Kaca">Kaca</option>
                                                    <option value="Organik">Organik</option>
                                                    <option value="Lainnya">Lainnya</option>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label for="add_sampah_satuan">Satuan</label>
                                                <input type="text" name="sampah_satuan" id="add_sampah_satuan" class="form-control" value="kg" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="add_sampah_harga">Harga Per kg (Rp)</label>
                                                <input type="number" name="sampah_harga_kg" id="add_sampah_harga" class="form-control" required min="0" placeholder="Contoh: 1500">
                                            </div>
                                            <div class="form-group">
                                                <label for="add_sampah_keterangan">Keterangan</label>
                                                <textarea name="sampah_keterangan" id="add_sampah_keterangan" class="form-control" rows="3" placeholder="Opsional..."></textarea>
                                            </div>
                                            <div style="display:flex; gap:10px;">
                                                <button type="submit" class="btn-action-sm btn-success" style="padding: 8px 16px;">💾 Simpan</button>
                                                <button type="button" onclick="document.getElementById('sampah_add_section').style.display='none'; document.getElementById('price_edit_placeholder').style.display='block';" class="btn-action-sm btn-secondary" style="padding: 8px 16px;">Batal</button>
                                            </div>
                                        </form>
                                    </div>

                                    <div id="price_edit_placeholder" style="background-color: var(--gray-light); padding:20px; border-radius:var(--radius); text-align:center; color:#888; font-size:13px; border: 1px dashed var(--gray-medium);">
                                        *Klik tombol Edit Harga di tabel untuk mengubah harga bahan, atau klik tombol Tambah Sampah Baru untuk menambah jenis sampah baru.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 5. MASTER GEOLOKASI TAB -->
                    <div id="tab-geolokasi" class="tab-content">
                        <div class="ui-block">
                            <h2 class="block-title">
                                <span>📍 Manajemen Master Geolokasi (Mitra Pengepul)</span>
                                <button onclick="toggleForm('form_tambah_lokasi')" class="btn-action-sm btn-success">➕ Tambah Lokasi Baru</button>
                            </h2>

                            <!-- Add Geolocation form -->
                            <div id="form_tambah_lokasi" style="display:none; background-color: var(--gray-light); padding: 24px; border-radius: var(--radius); margin-bottom: 25px; border: 1px solid var(--gray-medium);">
                                <h3 style="font-size:15px; font-weight:700; margin-bottom:15px; color: var(--primary);">Tambah Lokasi Baru</h3>
                                <form method="POST" action="{{ route('admin.master_geolokasi.store') }}">
                                    @csrf
                                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                                        <div class="form-group">
                                            <label for="nama_lokasi">Nama Pengepul</label>
                                            <input type="text" name="nama_lokasi" id="nama_lokasi" class="form-control" required placeholder="Contoh: Pengepul Maju Sejahtera">
                                        </div>
                                        <div class="form-group">
                                            <label for="alamat">Alamat Lengkap</label>
                                            <input type="text" name="alamat" id="alamat" class="form-control" required placeholder="Jl. Sudirman No 25">
                                        </div>
                                    </div>
                                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                                        <div class="form-group">
                                            <label for="latitude">Latitude Koordinat</label>
                                            <input type="number" step="0.000001" name="latitude" id="latitude" class="form-control" required placeholder="-6.182400">
                                        </div>
                                        <div class="form-group">
                                            <label for="longitude">Longitude Koordinat</label>
                                            <input type="number" step="0.000001" name="longitude" id="longitude" class="form-control" required placeholder="106.829400">
                                        </div>
                                    </div>
                                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                                        <div class="form-group">
                                            <label for="jam_operasional">Jam Kerja Operasional</label>
                                            <input type="text" name="jam_operasional" id="jam_operasional" class="form-control" required placeholder="08:00 - 17:00">
                                        </div>
                                        <div class="form-group">
                                            <label for="status_aktif">Status Kemitraan</label>
                                            <select name="status_aktif" id="status_aktif" class="form-control" required>
                                                <option value="aktif">Aktif (Mitra Aktif)</option>
                                                <option value="nonaktif">Nonaktif (Tutup Sementara)</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div style="display: flex; gap: 10px; margin-top: 10px;">
                                        <button type="submit" class="btn-action-sm btn-success" style="padding: 9px 18px;">💾 Simpan Lokasi</button>
                                        <button type="button" onclick="toggleForm('form_tambah_lokasi')" class="btn-action-sm btn-secondary" style="padding: 9px 18px;">Batal</button>
                                    </div>
                                </form>
                            </div>

                            <!-- Table list geolokasi -->
                            <div class="table-wrap">
                                <table>
                                    <thead>
                                        <tr>
                                            <th>Nama Tempat Pengepul</th>
                                            <th>Alamat</th>
                                            <th>Koordinat (Lat, Lng)</th>
                                            <th>Jam Operasional</th>
                                            <th>Status</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($allGeolokasi as $geo)
                                            <tr>
                                                <td><strong>{{ $geo->nama_lokasi }}</strong></td>
                                                <td>{{ $geo->alamat }}</td>
                                                <td><code style="font-size:11.5px;">{{ $geo->latitude }}, {{ $geo->longitude }}</code></td>
                                                <td>{{ $geo->jam_operasional }}</td>
                                                <td>
                                                    <span class="status-badge {{ $geo->status_aktif === 'aktif' ? 'open' : 'closed' }}">{{ $geo->status_aktif }}</span>
                                                </td>
                                                <td>
                                                    <button onclick="openEditGeoModal({{ $geo }})" class="btn-action-sm btn-secondary">✏️ Edit</button>
                                                    <form method="POST" action="{{ route('admin.master_geolokasi.delete', $geo->id_lokasi) }}" style="display:inline;" onsubmit="return confirm('Apakah Anda yakin ingin menghapus lokasi pengepul ini?')">
                                                        @csrf
                                                        <button type="submit" class="btn-action-sm btn-danger" style="margin-left:4px;">🗑️ Hapus</button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- 6. CETAK LAPORAN TAB -->
                    <div id="tab-laporan" class="tab-content">
                        <div class="ui-block" style="max-width: 600px;">
                            <h2 class="block-title">📊 Cetak & Export Laporan Bulanan (PDF)</h2>
                            <p style="font-size: 13.5px; color:#555; margin-bottom: 20px; line-height: 1.5;">Pilih periode bulan dan tahun laporan operasional bank sampah. Hasil dokumen terformat rapi A4 dan siap untuk diprint fisik maupun diekspor langsung sebagai PDF.</p>
                            
                            <form method="GET" action="{{ route('admin.cetak_laporan') }}" target="_blank">
                                <div class="form-group">
                                    <label for="report_bulan">Pilih Periode Bulan</label>
                                    <select name="bulan" id="report_bulan" class="form-control" required>
                                        @for($m = 1; $m <= 12; $m++)
                                            <option value="{{ str_pad($m, 2, '0', STR_PAD_LEFT) }}" {{ date('m') == $m ? 'selected' : '' }}>
                                                {{ date('F', mktime(0, 0, 0, $m, 10)) }}
                                            </option>
                                        @endfor
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="report_tahun">Pilih Periode Tahun</label>
                                    <select name="tahun" id="report_tahun" class="form-control" required>
                                        @for($y = date('Y') - 2; $y <= date('Y') + 1; $y++)
                                            <option value="{{ $y }}" {{ date('Y') == $y ? 'selected' : '' }}>{{ $y }}</option>
                                        @endfor
                                    </select>
                                </div>
                                <button type="submit" class="btn-submit" style="background-color: var(--secondary); display:flex; align-items:center; justify-content:center; gap:10px;">
                                    🖨️ Generate & Buka Laporan Cetak
                                </button>
                            </form>
                        </div>
                    </div>

                @else
                    <!-- ======================================================= -->
                    <!--                 👤 NASABAH SECTIONS                     -->
                    <!-- ======================================================= -->

                    <!-- 1. OVERVIEW TAB -->
                    <div id="tab-overview" class="tab-content" style="display:block;">
                        <div class="welcome-section">
                            <div class="welcome-text">
                                <h1>Selamat Datang Pejuang Ekologis!</h1>
                                <p>Selamat berkontribusi menjaga kelestarian bumi. Tabungan sampah Anda dikonversi menjadi saldo keuangan dan poin level gamifikasi.</p>
                            </div>
                        </div>

                        <!-- Statistics -->
                        <div class="stats-grid">
                            <div class="card-stat">
                                <div class="stat-icon icon-accent-1">🎁</div>
                                <div class="stat-content">
                                    <h3>Eco Poin</h3>
                                    <p>{{ number_format($user->gamifikasi->poin_diperoleh ?? 0, 0, ',', '.') }} Poin</p>
                                </div>
                            </div>
                            <div class="card-stat">
                                <div class="stat-icon icon-accent-2">💰</div>
                                <div class="stat-content">
                                    <h3>Sisa Uang Saldo</h3>
                                    <p>Rp {{ number_format($user->tabungan->tabungan_saldo_akhir ?? 0, 0, ',', '.') }}</p>
                                </div>
                            </div>
                            <div class="card-stat">
                                <div class="stat-icon icon-accent-3">⚖️</div>
                                <div class="stat-content">
                                    <h3>Sampah Disetor (Total)</h3>
                                    <p>{{ number_format($totalSampahDisetorKg, 2, ',', '.') }} kg</p>
                                </div>
                            </div>
                        </div>

                        <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 25px;">
                            <!-- E-Gamifikasi Center -->
                            <div class="gamifikasi-card">
                                <div class="gamifikasi-title">🏆 Status E-Gamifikasi Nasabah</div>
                                <div class="gamifikasi-points">
                                    {{ $user->gamifikasi->poin_diperoleh ?? 0 }} <span>Eco Poin</span>
                                </div>
                                <p style="font-size: 13px; opacity:0.95; margin-bottom: 15px;">
                                    Tingkat Level Anda: <strong>{{ $user->gamifikasi->level_nasabah ?? 'Pemula' }}</strong>
                                </p>

                                <div class="level-progress">
                                    <div class="level-meta">
                                        <span>Poin saat ini: {{ $user->gamifikasi->poin_diperoleh ?? 0 }} Poin</span>
                                        <span>Target Level Bintang: 500 Poin</span>
                                    </div>
                                    <div class="level-bar">
                                        @php
                                            $curPoin = $user->gamifikasi->poin_diperoleh ?? 0;
                                            $pct = min(100, ($curPoin / 500) * 100);
                                        @endphp
                                        <div class="level-fill" style="width: {{ $pct }}%"></div>
                                    </div>
                                </div>

                                <div class="badges-title">🎖️ Koleksi Lencana Digital:</div>
                                <div class="badges-row">
                                    <!-- Badge 1: Eco Starter (Setiap nasabah punya) -->
                                    <div class="badge-circle active">
                                        🌿
                                        <span class="tooltip">Eco Starter - Anggota Pejuang Kebersihan</span>
                                    </div>

                                    <!-- Badge 2: Penyetor Konsisten -->
                                    <div class="badge-circle {{ $totalSampahDisetorKg > 0 ? 'active' : '' }}">
                                        📦
                                        <span class="tooltip">Penyetor Konsisten - Melakukan setoran perdana</span>
                                    </div>

                                    <!-- Badge 3: Penyelamat Bumi -->
                                    <div class="badge-circle {{ $totalSampahDisetorKg >= 10 ? 'active' : '' }}">
                                        🌎
                                        <span class="tooltip">Penyelamat Bumi - Menabung lebih dari 10kg sampah</span>
                                    </div>
                                </div>
                            </div>

                            <!-- User Info Box -->
                            <div class="ui-block">
                                <h3 style="font-size: 14px; font-weight:700; color:var(--primary); margin-bottom: 12px;">ℹ️ Data Akun</h3>
                                <div style="font-size:12.5px; line-height: 1.8; color: #555;">
                                    <p>Nama: <strong>{{ $user->nasabah_nama }}</strong></p>
                                    <p>NIK: {{ $user->nasabah_nik }}</p>
                                    <p>Username: <code>{{ $user->nasabah_username }}</code></p>
                                    <p>Telepon: {{ $user->nasabah_telepon }}</p>
                                    <p>Alamat: {{ $user->nasabah_alamat }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 2. CARI PENGEPUL (GPS) TAB -->
                    <div id="tab-gps" class="tab-content">
                        <div class="ui-block">
                            <div class="gps-section">
                                <div class="gps-header">
                                    <div>
                                        <h3 style="font-size:15.5px; font-weight:700; color:var(--primary); display:flex; align-items:center; gap:8px;">📍 Cari Pengepul Mitra Terdekat (Radius GPS)</h3>
                                        <p style="font-size:12px; color:#555; margin-top:3px;">Aktifkan koordinat GPS perangkat Anda untuk menghitung radius jarak pengepul aktif secara real-time.</p>
                                    </div>
                                    <button onclick="aktifkanGPS()" class="gps-btn">📡 Aktifkan GPS</button>
                                </div>

                                <div id="gps_status_msg" style="font-size:12.5px; color:#666; font-style:italic;">
                                    *Tekan tombol "Aktifkan GPS" untuk melakukan pencarian berbasis lokasi.
                                </div>

                                <!-- GPS Sorted Output List -->
                                <div id="gps_render_list" class="gps-list" style="display:none;"></div>
                            </div>
                        </div>
                    </div>

                    <!-- 3. TARIK TABUNGAN TAB -->
                    <div id="tab-tarik" class="tab-content">
                        <div class="ui-block" style="max-width: 550px;">
                            <h2 class="block-title">💸 Ajukan Pencairan Saldo</h2>
                            <p style="font-size: 13px; color: #666; margin-bottom: 16px; line-height: 1.5;">Masukkan nominal uang yang ingin dicairkan. Minimal pencairan <strong>Rp 100.000</strong>. Pengajuan akan diproses oleh admin.</p>

                            {{-- Info saldo saat ini --}}
                            <div style="background: #e8f5e9; border-radius: 10px; padding: 14px 18px; margin-bottom: 20px; display:flex; justify-content:space-between; align-items:center;">
                                <div>
                                    <div style="font-size:12px;color:#555;font-weight:600;">Saldo Anda Saat Ini</div>
                                    <div style="font-size:22px;font-weight:800;color:#1b5e20;">Rp {{ number_format($saldoNasabah ?? 0, 0, ',', '.') }}</div>
                                </div>
                                <div style="font-size:28px;">💰</div>
                            </div>

                            @if(($saldoNasabah ?? 0) < $minimalPencairan)
                                <div style="background:#fff8e1;border:1px solid #ffcc02;border-radius:10px;padding:12px 16px;margin-bottom:16px;font-size:13px;color:#e65100;">
                                    ⚠️ Saldo Anda belum mencukupi untuk melakukan pencairan. Minimal saldo <strong>Rp {{ number_format($minimalPencairan,0,',','.') }}</strong>.
                                </div>
                            @endif

                            <form method="POST" action="{{ route('nasabah.pencairan.store') }}">
                                @csrf
                                <div class="form-group">
                                    <label for="tarik_jumlah">Nominal Pencairan (Rp)</label>
                                    <input type="number" name="tarik_jumlah" id="tarik_jumlah" class="form-control"
                                        min="{{ $minimalPencairan }}"
                                        max="{{ $saldoNasabah ?? 0 }}"
                                        step="1000"
                                        placeholder="Contoh: 150000" required
                                        {{ ($saldoNasabah ?? 0) < $minimalPencairan ? 'disabled' : '' }}>
                                    <span style="font-size: 11px; color:#888; display:block; margin-top:4px;">*Minimal pencairan Rp {{ number_format($minimalPencairan,0,',','.') }}</span>
                                </div>
                                <div class="form-group" style="margin-top: 15px;">
                                    <label for="tarik_bank_tujuan">Bank / E-Wallet Tujuan Transfer</label>
                                    <select name="tarik_bank_tujuan" id="tarik_bank_tujuan" class="form-control" required
                                        {{ ($saldoNasabah ?? 0) < $minimalPencairan ? 'disabled' : '' }}>
                                        <option value="" disabled selected>-- Pilih Bank / E-Wallet --</option>
                                        <option value="BCA">Bank BCA</option>
                                        <option value="Mandiri">Bank Mandiri</option>
                                        <option value="BRI">Bank BRI</option>
                                        <option value="BNI">Bank BNI</option>
                                        <option value="BSI">Bank Syariah Indonesia (BSI)</option>
                                        <option value="GoPay">GoPay</option>
                                        <option value="OVO">OVO</option>
                                        <option value="Dana">Dana</option>
                                        <option value="LinkAja">LinkAja</option>
                                        <option value="ShopeePay">ShopeePay</option>
                                    </select>
                                </div>
                                <div class="form-group" style="margin-top: 15px;">
                                    <label for="tarik_nomor_rekening">Nomor Rekening / No. HP E-Wallet</label>
                                    <input type="text" name="tarik_nomor_rekening" id="tarik_nomor_rekening" class="form-control"
                                        placeholder="Contoh: 1234567890 atau 08123456789" required
                                        {{ ($saldoNasabah ?? 0) < $minimalPencairan ? 'disabled' : '' }}>
                                </div>
                                <div class="form-group" style="margin-top: 15px; margin-bottom: 20px;">
                                    <label for="tarik_atas_nama">Nama Pemilik Rekening / Akun</label>
                                    <input type="text" name="tarik_atas_nama" id="tarik_atas_nama" class="form-control"
                                        placeholder="Contoh: Budi Susanto" required
                                        {{ ($saldoNasabah ?? 0) < $minimalPencairan ? 'disabled' : '' }}>
                                </div>
                                <button type="submit" class="btn-submit" {{ ($saldoNasabah ?? 0) < $minimalPencairan ? 'disabled style=opacity:0.5;cursor:not-allowed;' : '' }}>
                                    💸 Kirim Pengajuan Pencairan
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- 4. RIWAYAT TRANSAKSI TAB -->
                    <div id="tab-riwayat" class="tab-content">
                        <!-- Deposit History -->
                        <div class="ui-block" style="margin-bottom: 30px;">
                            <h2 class="block-title">📥 Histori Penyetoran Sampah</h2>
                            <div class="table-wrap">
                                @if($recentSetorans->isEmpty())
                                    <p style="font-size: 13.5px; color: #888; text-align: center; padding: 15px 0;">Belum pernah melakukan penyetoran sampah.</p>
                                @else
                                    <table>
                                        <thead>
                                            <tr>
                                                <th>Tanggal Setor</th>
                                                <th>Jenis Sampah</th>
                                                <th>Berat Timbangan</th>
                                                <th>Rupiah Didapat</th>
                                                <th>Catatan Petugas</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($recentSetorans as $rs)
                                                <tr>
                                                    <td>{{ \Carbon\Carbon::parse($rs->setor_tanggal)->format('d M Y H:i') }}</td>
                                                    <td><strong>{{ $rs->sampah->sampah_name }}</strong></td>
                                                    <td>{{ number_format($rs->setor_berat_kg, 2, ',', '.') }} kg</td>
                                                    <td><strong style="color:var(--secondary)">+ Rp {{ number_format($rs->setor_harga_total, 0, ',', '.') }}</strong></td>
                                                    <td><span style="font-size:12px; color:#666;">{{ $rs->setor_keterangan }}</span></td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                @endif
                            </div>
                        </div>

                        <!-- Withdrawal History -->
                        <div class="ui-block">
                            <h2 class="block-title">📤 Histori Penarikan Uang Tabungan</h2>
                            <div class="table-wrap">
                                @if($recentPenarikans->isEmpty())
                                    <p style="font-size: 13.5px; color: #888; text-align: center; padding: 15px 0;">Belum pernah mengajukan penarikan uang.</p>
                                @else
                                    <table>
                                        <thead>
                                            <tr>
                                                <th>Tanggal Pengajuan</th>
                                                <th>Jumlah Pengambilan</th>
                                                <th>Sisa Saldo Rekening</th>
                                                <th>Rekening Tujuan</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($recentPenarikans as $rp)
                                                <tr>
                                                    <td>{{ \Carbon\Carbon::parse($rp->tarik_tanggal)->format('d M Y') }}</td>
                                                    <td><strong style="color:var(--danger)">Rp {{ number_format($rp->tarik_jumlah, 0, ',', '.') }}</strong></td>
                                                    <td>Rp {{ number_format($rp->tarik_sisa_saldo, 0, ',', '.') }}</td>
                                                    <td>
                                                        @if($rp->tarik_bank_tujuan)
                                                            <div style="font-size: 13px; font-weight: 700; color: #333;">
                                                                {{ $rp->tarik_bank_tujuan }} - {{ $rp->tarik_nomor_rekening }}
                                                            </div>
                                                            <div style="font-size: 11.5px; color: #666; margin-top: 2px;">
                                                                a/n {{ $rp->tarik_atas_nama }}
                                                            </div>
                                                        @else
                                                            <span style="color:#aaa; font-style:italic;">Manual / Tunai</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($rp->status === 'menunggu')
                                                            <span class="status-badge" style="background-color:#fff3e0; color:#ef6c00;">⏳ Menunggu</span>
                                                        @elseif($rp->status === 'disetujui')
                                                            <span class="status-badge" style="background-color:#e8f5e9; color:#2e7d32;">✅ Disetujui</span>
                                                        @else
                                                            <span class="status-badge" style="background-color:#ffebee; color:#c62828;">❌ Ditolak</span>
                                                        @endif
                                                    </td>
                                                    @if($rp->catatan)
                                                    <td style="font-size:12px;color:#888;">{{ $rp->catatan }}</td>
                                                    @endif
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- 5. TUKAR ECO POIN TAB -->
                    <div id="tab-rewards" class="tab-content">
                        <!-- Eco Poin Stats Card -->
                        <div style="background: linear-gradient(135deg, #1565c0 0%, #0d47a1 100%); color:#fff; border-radius: var(--radius); padding:20px; box-shadow: var(--shadow); margin-bottom: 25px; display:flex; justify-content:space-between; align-items:center;">
                            <div>
                                <div style="font-size:12.5px; opacity:0.8; text-transform:uppercase; font-weight:700; letter-spacing:0.5px;">Saldo Poin Anda Saat Ini</div>
                                <div style="font-size:28px; font-weight:800; margin-top:5px;">{{ $user->gamifikasi->poin_diperoleh ?? 0 }} <span style="font-size:16px; font-weight:500; opacity:0.8;">Eco Poin</span></div>
                                <div style="font-size:12px; opacity:0.9; margin-top:8px;">*Poin dapat ditukarkan dengan sembako atau barang ramah lingkungan di bawah ini.</div>
                            </div>
                            <div style="font-size:42px;">🎁</div>
                        </div>

                        <!-- Catalog Grid -->
                        <h2 class="block-title" style="margin-bottom:15px; display:flex; align-items:center; gap:8px;">🛍️ Katalog Eco Rewards</h2>
                        <p style="font-size:13px; color:#666; margin-bottom:20px;">Silakan pilih barang yang ingin ditukar. Setelah menukar, Anda dapat mengambil barang langsung di kantor Bank Sampah.</p>

                        <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(230px, 1fr)); gap:20px; margin-bottom:30px;">
                            @forelse($allHadiahs as $h)
                                <div class="ui-block" style="display:flex; flex-direction:column; padding:18px; position:relative; overflow:hidden; border-top: 4px solid var(--primary); background:#fff;">
                                    <!-- Reward Title -->
                                    <h3 style="font-size:15.5px; font-weight:700; color:#333; margin-bottom:8px;">{{ $h->nama_hadiah }}</h3>
                                    <!-- Description -->
                                    <p style="font-size:12.5px; color:#666; flex-grow:1; margin-bottom:15px; line-height:1.4;">{{ $h->keterangan ?? 'Tidak ada keterangan.' }}</p>
                                    <!-- Meta Row -->
                                    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:15px; background:#f9f9f9; padding:8px; border-radius:6px; font-size:12.5px;">
                                        <div>Biaya: <strong style="color:var(--primary); font-weight:700;">{{ $h->poin_butuh }} Poin</strong></div>
                                        <div>Stok: <strong style="{{ $h->stok > 0 ? 'color:#2e7d32;' : 'color:var(--danger);' }}">{{ $h->stok }} pcs</strong></div>
                                    </div>
                                    <!-- Action Form -->
                                    @if($h->stok > 0)
                                        @if(($user->gamifikasi->poin_diperoleh ?? 0) >= $h->poin_butuh)
                                            <form method="POST" action="{{ route('nasabah.tukar_poin') }}" style="margin:0;">
                                                @csrf
                                                <input type="hidden" name="id_hadiah" value="{{ $h->id_hadiah }}">
                                                <div style="display:flex; gap:8px;">
                                                    <input type="number" name="jumlah" value="1" min="1" max="{{ min($h->stok, floor(($user->gamifikasi->poin_diperoleh ?? 0) / $h->poin_butuh)) }}" class="form-control" style="width:70px; padding:6px 8px; text-align:center;" required>
                                                    <button type="submit" class="btn-submit" style="margin:0; font-size:12.5px; padding:8px 12px; background:var(--primary); color:#fff; border:none; border-radius:6px; cursor:pointer; font-weight:700;">Tukar</button>
                                                </div>
                                            </form>
                                        @else
                                            <button disabled style="width:100%; border:none; background:#e0e0e0; color:#888; font-size:12px; padding:10px; border-radius:8px; cursor:not-allowed; font-weight:700;">Poin Tidak Cukup</button>
                                        @endif
                                    @else
                                        <button disabled style="width:100%; border:none; background:#ffebee; color:var(--danger); font-size:12px; padding:10px; border-radius:8px; cursor:not-allowed; font-weight:700;">Stok Habis</button>
                                    @endif
                                </div>
                            @empty
                                <div style="grid-column: 1/-1; text-align:center; padding:30px; color:#aaa;">Belum ada hadiah yang ditambahkan ke katalog.</div>
                            @endforelse
                        </div>

                        <!-- Redemptions History -->
                        <div class="ui-block" style="background:#fff;">
                            <h2 class="block-title">🕒 Histori Penukaran Eco Rewards</h2>
                            <div class="table-wrap">
                                @if($recentPenukaranRewards->isEmpty())
                                    <p style="font-size: 13.5px; color: #888; text-align: center; padding: 15px 0;">Belum pernah mengajukan penukaran hadiah.</p>
                                @else
                                    <table>
                                        <thead>
                                            <tr>
                                                <th>Tanggal Tukar</th>
                                                <th>Barang Hadiah</th>
                                                <th>Jumlah</th>
                                                <th>Total Eco Poin</th>
                                                <th>Status</th>
                                                <th>Keterangan Admin</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($recentPenukaranRewards as $pr)
                                                <tr>
                                                     <td>{{ \Carbon\Carbon::parse($pr->tanggal_tukar)->format('d M Y') }}</td>
                                                     <td><strong>{{ $pr->hadiah->nama_hadiah ?? 'Hadiah Dihapus' }}</strong></td>
                                                     <td>{{ $pr->jumlah }} pcs</td>
                                                     <td><strong style="color:#0d47a1">- {{ $pr->total_poin_ditukar }} Poin</strong></td>
                                                     <td>
                                                         @if($pr->status === 'menunggu')
                                                             <span class="status-badge" style="background-color:#fff3e0; color:#ef6c00;">⏳ Menunggu</span>
                                                         @elseif($pr->status === 'diambil')
                                                             <span class="status-badge" style="background-color:#e8f5e9; color:#2e7d32;">✅ Sudah Diambil</span>
                                                         @else
                                                             <span class="status-badge" style="background-color:#ffebee; color:#c62828;">❌ Ditolak</span>
                                                         @endif
                                                     </td>
                                                     <td><span style="font-size:12px; color:#666;">{{ $pr->catatan ?? '-' }}</span></td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                @endif
                            </div>
                        </div>
                    </div>

                @endif

            </div>

            <footer style="margin-top:auto; padding:20px 30px; text-align:center; font-size:12px; color:#888; background:#fff; border-top: 1px solid var(--gray-medium);">
                &copy; 2026 Bank Sampah Digital. Bersama Kita Selamatkan Ekosistem Hijau & Finansial Masa Depan.
            </footer>

        </main>

    </div>

    <!-- Edit Geolokasi Modal (Admin Only, triggered by JS) -->
    @if($userType === 'admin')
        <div id="edit_geo_modal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:9999; align-items:center; justify-content:center;">
            <div class="ui-block" style="width:100%; max-width:550px; margin: 0 15px; border-top: 5px solid var(--secondary);">
                <h3 style="font-size:16px; font-weight:700; margin-bottom:15px; color:var(--primary);">Ubah Data Geolokasi</h3>
                <form id="edit_geo_form" method="POST" action="">
                    @csrf
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                        <div class="form-group">
                            <label for="edit_nama_lokasi">Nama Pengepul</label>
                            <input type="text" name="nama_lokasi" id="edit_nama_lokasi" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="edit_alamat">Alamat Lengkap</label>
                            <input type="text" name="alamat" id="edit_alamat" class="form-control" required>
                        </div>
                    </div>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                        <div class="form-group">
                            <label for="edit_latitude">Latitude</label>
                            <input type="number" step="0.000001" name="latitude" id="edit_latitude" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="edit_longitude">Longitude</label>
                            <input type="number" step="0.000001" name="longitude" id="edit_longitude" class="form-control" required>
                        </div>
                    </div>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                        <div class="form-group">
                            <label for="edit_jam_operasional">Jam Operasional</label>
                            <input type="text" name="jam_operasional" id="edit_jam_operasional" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="edit_status_aktif">Status Aktif</label>
                            <select name="status_aktif" id="edit_status_aktif" class="form-control" required>
                                <option value="aktif">Aktif</option>
                                <option value="nonaktif">Nonaktif</option>
                            </select>
                        </div>
                    </div>
                    <div style="display:flex; gap:10px; margin-top:15px; justify-content: flex-end;">
                        <button type="submit" class="btn-action-sm btn-success" style="padding:8px 16px;">💾 Perbarui</button>
                        <button type="button" onclick="document.getElementById('edit_geo_modal').style.display='none'" class="btn-action-sm btn-secondary" style="padding:8px 16px;">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- INTERACTIVE SIDEBAR & TAB LOGICS -->
    <script>
        // --- 1. GLOBAL LAYOUT SWITCHING (TABS) ---
        document.addEventListener('DOMContentLoaded', () => {
            const savedTab = localStorage.getItem('BS_active_tab') || 'tab-overview';
            const link = document.querySelector(`.menu-link[data-tab="${savedTab}"]`);
            
            // Hide all tabs
            const contents = document.querySelectorAll('.tab-content');
            contents.forEach(c => c.style.display = 'none');
            
            // Show saved tab
            const activeSection = document.getElementById(savedTab);
            if (activeSection) {
                activeSection.style.display = 'block';
            }
            
            // Highlight active menu item
            if (link) {
                const links = document.querySelectorAll('.menu-link');
                links.forEach(l => l.classList.remove('active'));
                link.classList.add('active');
            }

        });

        // Tab Switching Engine
        function switchTab(event, tabId) {
            if (event) event.preventDefault();
            
            // Hide all tab contents
            const contents = document.querySelectorAll('.tab-content');
            contents.forEach(c => c.style.display = 'none');
            
            // Show target tab
            const target = document.getElementById(tabId);
            if (target) {
                target.style.display = 'block';
            }
            
            // Save tab state to localStorage
            localStorage.setItem('BS_active_tab', tabId);
            
            // Update active styling
            const links = document.querySelectorAll('.menu-link');
            links.forEach(l => l.classList.remove('active'));
            
            if (event) {
                event.currentTarget.classList.add('active');
            }
            
            // Close mobile sidebar if open
            const sidebar = document.querySelector('.sidebar');
            if (sidebar.classList.contains('mobile-active')) {
                sidebar.classList.remove('mobile-active');
            }

        }        // Toggle mobile sidebar
        function toggleSidebar() {
            const sidebar = document.querySelector('.sidebar');
            sidebar.classList.toggle('mobile-active');
        }

        // --- 2. ADMIN INTERACTIVE SCRIPTS ---
        @if($userType === 'admin')
            // Live Price Calculator in Deposit Timbang form
            const selectSampah = document.getElementById('id_sampah');
            const inputBerat = document.getElementById('setor_berat_kg');
            const displayPrice = document.getElementById('live_price_display');

            function updateLivePrice() {
                const opt = selectSampah.options[selectSampah.selectedIndex];
                const weight = parseFloat(inputBerat.value) || 0;
                
                if (opt && opt.dataset.price) {
                    const priceKg = parseFloat(opt.dataset.price);
                    const grandTotal = priceKg * weight;
                    displayPrice.textContent = 'Rp ' + Math.round(grandTotal).toLocaleString('id-ID');
                } else {
                    displayPrice.textContent = 'Rp 0';
                }
            }

            if (selectSampah && inputBerat) {
                selectSampah.addEventListener('change', updateLivePrice);
                inputBerat.addEventListener('input', updateLivePrice);
            }

            // Edit price inline section
            function openEditPriceForm(id, name, currentPrice) {
                const sect = document.getElementById('price_edit_section');
                const addSect = document.getElementById('sampah_add_section');
                const ph = document.getElementById('price_edit_placeholder');
                
                if (addSect) addSect.style.display = 'none';
                ph.style.display = 'none';
                sect.style.display = 'block';
                
                document.getElementById('price_edit_id').value = id;
                document.getElementById('price_edit_title').textContent = name;
                document.getElementById('price_edit_val').value = currentPrice;
                document.getElementById('price_edit_val').focus();
            }

            function openAddSampahForm() {
                const sect = document.getElementById('price_edit_section');
                const addSect = document.getElementById('sampah_add_section');
                const ph = document.getElementById('price_edit_placeholder');
                
                if (sect) sect.style.display = 'none';
                ph.style.display = 'none';
                addSect.style.display = 'block';
                
                document.getElementById('add_sampah_name').value = '';
                document.getElementById('add_sampah_harga').value = '';
                document.getElementById('add_sampah_keterangan').value = '';
                document.getElementById('add_sampah_name').focus();
            }

            // Toggle layout visibility helper
            function toggleForm(id) {
                const el = document.getElementById(id);
                el.style.display = el.style.display === 'none' ? 'block' : 'none';
            }

            // Edit geolokasi form modal popup
            function openEditGeoModal(geo) {
                const modal = document.getElementById('edit_geo_modal');
                const form = document.getElementById('edit_geo_form');
                
                // Set route action
                form.action = `/admin/master-geolokasi/update/${geo.id_lokasi}`;
                
                // Populate inputs
                document.getElementById('edit_nama_lokasi').value = geo.nama_lokasi;
                document.getElementById('edit_alamat').value = geo.alamat;
                document.getElementById('edit_latitude').value = geo.latitude;
                document.getElementById('edit_longitude').value = geo.longitude;
                document.getElementById('edit_jam_operasional').value = geo.jam_operasional;
                document.getElementById('edit_status_aktif').value = geo.status_aktif;
                
                modal.style.display = 'flex';
            }

            // Map initialization removed since dynamic GPS coordinates are self-updated by Pengepul.
        @else
            // --- 3. NASABAH INTERACTIVE SCRIPTS ---
            // GPS Geolocator Mitra Finder (Haversine Distance sorting)
            const geolokasiData = @json($activeGeolokasi);

            function calculateDistance(lat1, lon1, lat2, lon2) {
                const R = 6371; // Earth radius in km
                const dLat = (lat2 - lat1) * Math.PI / 180;
                const dLon = (lon2 - lon1) * Math.PI / 180;
                
                const a = 
                    Math.sin(dLat / 2) * Math.sin(dLat / 2) +
                    Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) * 
                    Math.sin(dLon / 2) * Math.sin(dLon / 2);
                
                const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
                return R * c; // Distance in km
            }

            function aktifkanGPS() {
                const statusMsg = document.getElementById('gps_status_msg');
                const renderList = document.getElementById('gps_render_list');
                
                if (!navigator.geolocation) {
                    statusMsg.innerHTML = '<span style="color:var(--danger);">⚠️ Browser Anda tidak mendukung koordinat GPS.</span>';
                    return;
                }
                
                statusMsg.innerHTML = '🔄 Mengambil koordinat satelit GPS perangkat Anda...';
                
                navigator.geolocation.getCurrentPosition(
                    (pos) => {
                        const userLat = pos.coords.latitude;
                        const userLng = pos.coords.longitude;
                        statusMsg.innerHTML = `📍 GPS Aktif! Koordinat perangkat: <code>${userLat.toFixed(5)}, ${userLng.toFixed(5)}</code>. Diurutkan berdasarkan jarak terdekat:`;

                        // Map distance
                        const scoredList = geolokasiData.map(loc => {
                            const dist = calculateDistance(userLat, userLng, parseFloat(loc.latitude), parseFloat(loc.longitude));
                            return { ...loc, distance: dist };
                        });

                        // Sort by distance ascending
                        scoredList.sort((a, b) => a.distance - b.distance);

                        // Render output html
                        renderList.innerHTML = '';
                        scoredList.forEach(loc => {
                            const distText = loc.distance < 1 
                                ? Math.round(loc.distance * 1000) + ' meter' 
                                : loc.distance.toFixed(2) + ' km';

                            const tipeBadge = loc.tipe === 'Cabang Utama' 
                                ? '<span class="status-badge open" style="background-color:#e8f5e9; color:#2e7d32; margin-left: 8px;">🏛️ Cabang Utama</span>'
                                : '<span class="status-badge open" style="background-color:#e3f2fd; color:#1565c0; margin-left: 8px;">🚛 Mitra Pengepul</span>';

                            const itemHtml = `
                                <div class="gps-item">
                                    <div class="gps-meta">
                                        <h4 style="display:flex; align-items:center;">${loc.nama_lokasi} ${tipeBadge}</h4>
                                        <p>📍 Alamat: ${loc.alamat}</p>
                                        <p style="font-size:11px; margin-top:3px; color:#555;">⏱️ Operasional: ${loc.jam_operasional} | <span class="status-badge open">Aktif</span></p>
                                    </div>
                                    <div class="distance-badge">${distText}</div>
                                </div>
                            `;
                            renderList.innerHTML += itemHtml;
                        });

                        renderList.style.display = 'flex';
                    },
                    (err) => {
                        statusMsg.innerHTML = '<span style="color:var(--danger);">⚠️ Gagal melacak lokasi. Mohon berikan izin akses GPS/Lokasi di browser Anda.</span>';
                    }
                );
            }
        @endif

        // ── Toggle form edit pengepul inline ─────────────────────
        function toggleEditPengepul(id) {
            const row = document.getElementById('edit-pengepul-' + id);
            if (row) {
                const isShowing = (row.style.display === 'none' || row.style.display === '');
                row.style.display = isShowing ? 'table-row' : 'none';
            }
        }
    </script>

</body>
</html>
