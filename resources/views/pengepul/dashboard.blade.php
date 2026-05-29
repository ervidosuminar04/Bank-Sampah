<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Pengepul – Bank Sampah Digital</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        /* Sistem Warna – selaras dengan dashboard utama */
        :root {
            --primary:        #1b5e20;
            --primary-light:  #e8f5e9;
            --primary-medium: #81c784;
            --secondary:      #2e7d32;
            --accent:         #ffb300;
            --danger:         #d32f2f;
            --danger-light:   #ffebee;
            --dark:           #212121;
            --gray-light:     #f5f5f5;
            --gray-medium:    #e0e0e0;
            --white:          #ffffff;
            --shadow:         0 4px 20px rgba(0,0,0,0.06);
            --shadow-hover:   0 8px 30px rgba(27,94,32,0.12);
            --radius:         12px;
            --transition:     all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Outfit', sans-serif;
            background-color: #f4f6f5;
            color: var(--dark);
            min-height: 100vh;
            display: flex;
            overflow: hidden;
        }

        /* ── LAYOUT ── */
        .app-layout { display: flex; width: 100vw; height: 100vh; }

        /* ── SIDEBAR ── */
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
            border-bottom: 1px solid rgba(255,255,255,0.1);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .sidebar-profile {
            padding: 20px 24px;
            display: flex;
            align-items: center;
            gap: 12px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            background-color: rgba(255,255,255,0.03);
        }

        .profile-avatar {
            width: 44px; height: 44px;
            border-radius: 50%;
            background-color: var(--white);
            color: var(--primary);
            font-size: 20px;
            display: flex; align-items: center; justify-content: center;
            font-weight: 700;
        }

        .profile-info { display: flex; flex-direction: column; overflow: hidden; }
        .profile-name { font-size: 14px; font-weight: 700; white-space: nowrap; text-overflow: ellipsis; overflow: hidden; }
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

        .sidebar-menu { list-style: none; padding: 20px 12px; flex: 1; overflow-y: auto; }
        .sidebar-menu li { margin-bottom: 6px; }

        .menu-link {
            display: flex; align-items: center; gap: 12px;
            padding: 12px 16px;
            color: rgba(255,255,255,0.75);
            text-decoration: none;
            font-size: 14px; font-weight: 600;
            border-radius: 8px;
            transition: var(--transition);
            cursor: pointer;
        }
        .menu-link:hover { color: var(--white); background-color: rgba(255,255,255,0.08); }
        .menu-link.active { color: var(--white); background-color: var(--secondary); box-shadow: 0 4px 12px rgba(0,0,0,0.1); }

        .sidebar-footer { padding: 20px 24px; border-top: 1px solid rgba(255,255,255,0.1); }
        .btn-logout-sidebar {
            display: flex; align-items: center; justify-content: center; gap: 8px;
            background-color: var(--danger);
            color: var(--white);
            width: 100%; border: none;
            padding: 10px; border-radius: 8px;
            font-size: 13.5px; font-weight: 700;
            text-decoration: none;
            transition: var(--transition);
        }
        .btn-logout-sidebar:hover { background-color: #b71c1c; }

        /* ── MAIN PANEL ── */
        .main-panel { flex: 1; display: flex; flex-direction: column; height: 100%; overflow: hidden; }

        .panel-header {
            background-color: var(--white);
            border-bottom: 1px solid var(--gray-medium);
            padding: 16px 30px;
            display: flex; justify-content: space-between; align-items: center;
            z-index: 10;
        }

        .header-title { font-size: 18px; font-weight: 700; color: var(--primary); }

        .mobile-toggle {
            display: none;
            background: none; border: none;
            font-size: 24px; color: var(--primary);
            cursor: pointer; margin-right: 15px;
        }

        .panel-content { flex: 1; padding: 30px; overflow-y: auto; position: relative; }

        /* ── TAB CONTENT ── */
        .tab-content { display: none; animation: fadeIn 0.4s ease; }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(8px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* ── WELCOME ── */
        .welcome-section {
            background: linear-gradient(135deg, #e8f5e9 0%, #c8e6c9 100%);
            border-radius: var(--radius);
            padding: 24px 30px;
            margin-bottom: 30px;
            box-shadow: var(--shadow);
            position: relative; overflow: hidden;
        }
        .welcome-text h1 { font-size: 22px; font-weight: 800; color: #0d5c14; margin-bottom: 6px; }
        .welcome-text p  { color: #437046; font-size: 14px; }

        /* ── STATS ── */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px; margin-bottom: 30px;
        }
        .card-stat {
            background-color: var(--white);
            border-radius: var(--radius);
            padding: 20px;
            box-shadow: var(--shadow);
            display: flex; align-items: center; gap: 16px;
            transition: var(--transition);
            border: 1px solid rgba(0,0,0,0.02);
        }
        .card-stat:hover { transform: translateY(-3px); box-shadow: var(--shadow-hover); }

        .stat-icon {
            width: 50px; height: 50px;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 22px;
        }
        .stat-content h3 { font-size: 11px; color: #888; text-transform: uppercase; letter-spacing: 0.8px; margin-bottom: 4px; font-weight: 700; }
        .stat-content p  { font-size: 18px; font-weight: 800; color: var(--dark); }

        .icon-accent-1 { background-color: #e8f5e9; color: #2e7d32; }
        .icon-accent-2 { background-color: #e3f2fd; color: #1e88e5; }
        .icon-accent-3 { background-color: #fff8e1; color: #ff8f00; }

        /* ── UI BLOCK ── */
        .ui-block {
            background-color: var(--white);
            border-radius: var(--radius);
            padding: 24px;
            box-shadow: var(--shadow);
            border: 1px solid rgba(0,0,0,0.02);
            margin-bottom: 25px;
        }
        .block-title {
            font-size: 16px; font-weight: 700; color: var(--primary);
            margin-bottom: 18px; padding-bottom: 10px;
            border-bottom: 2px solid var(--primary-light);
            display: flex; justify-content: space-between; align-items: center;
        }

        /* ── FORM ── */
        .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
        .form-group { margin-bottom: 16px; }
        .form-group label { display: block; font-size: 13px; font-weight: 600; color: #555; margin-bottom: 6px; }
        .form-control {
            width: 100%; padding: 10px 14px;
            border: 1px solid var(--gray-medium);
            border-radius: 8px;
            font-size: 13.5px; outline: none;
            font-family: inherit; transition: var(--transition);
        }
        .form-control:focus { border-color: var(--primary); box-shadow: 0 0 0 3px rgba(27,94,32,0.12); }

        /* Preview nilai rupiah realtime */
        .nilai-preview {
            background: var(--primary-light);
            border-radius: 8px;
            padding: 12px 16px;
            margin-top: 4px;
            font-size: 15px;
            font-weight: 700;
            color: var(--primary);
            display: flex; align-items: center; gap: 8px;
        }

        /* ── BUTTONS ── */
        .btn-submit {
            background-color: var(--primary);
            color: var(--white); border: none;
            padding: 11px 20px; border-radius: 8px;
            font-size: 14px; font-weight: 700;
            cursor: pointer; width: 100%;
            transition: var(--transition);
        }
        .btn-submit:hover { background-color: var(--secondary); transform: translateY(-1px); }

        .btn-print {
            background-color: var(--primary);
            color: var(--white); border: none;
            padding: 9px 18px; border-radius: 8px;
            font-size: 13px; font-weight: 700;
            cursor: pointer; text-decoration: none;
            display: inline-flex; align-items: center; gap: 8px;
            transition: var(--transition);
        }
        .btn-print:hover { background-color: var(--secondary); }

        .btn-filter {
            background-color: var(--secondary);
            color: var(--white); border: none;
            padding: 9px 18px; border-radius: 8px;
            font-size: 13px; font-weight: 700;
            cursor: pointer;
            display: inline-flex; align-items: center; gap: 6px;
            transition: var(--transition);
        }
        .btn-filter:hover { background-color: var(--primary); }

        /* ── TABLE ── */
        .table-wrap { width: 100%; overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; font-size: 13.5px; text-align: left; }
        th { background-color: var(--gray-light); padding: 12px; font-weight: 600; color: #666; border-bottom: 2px solid var(--gray-medium); }
        td { padding: 12px; border-bottom: 1px solid var(--gray-light); }
        tr:hover td { background-color: #fafdfb; }

        /* ── ALERT ── */
        .alert {
            padding: 16px 20px; border-radius: var(--radius);
            margin-bottom: 25px; font-size: 14px; font-weight: 500;
            display: flex; align-items: center; gap: 12px;
            box-shadow: var(--shadow);
        }
        .alert-success { background-color: var(--primary-light); border-left: 5px solid var(--secondary); color: #1b5e20; }
        .alert-error   { background-color: var(--danger-light);  border-left: 5px solid var(--danger);    color: #b71c1c; }

        /* ── FILTER ROW ── */
        .filter-row { display: flex; gap: 12px; align-items: flex-end; flex-wrap: wrap; margin-bottom: 20px; }
        .filter-row .form-group { margin-bottom: 0; }

        /* ── LAPORAN TOTAL ── */
        .laporan-total {
            display: flex; gap: 16px; flex-wrap: wrap; margin-bottom: 20px;
        }
        .total-box {
            background: var(--primary-light);
            border-radius: var(--radius);
            padding: 14px 20px; flex: 1; min-width: 160px;
        }
        .total-box h4 { font-size: 11px; color: #437046; text-transform: uppercase; font-weight: 700; margin-bottom: 4px; }
        .total-box p  { font-size: 20px; font-weight: 800; color: var(--primary); }

        /* ── RESPONSIVE ── */
        @media (max-width: 768px) {
            .sidebar { position: fixed; left: -280px; top: 0; bottom: 0; height: 100%; }
            .sidebar.mobile-active { left: 0; }
            .mobile-toggle { display: block; }
            .form-row { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
<div class="app-layout">

    <!-- ═══════════════════════════════════════════ -->
    <!--               🌿 LEFT SIDEBAR               -->
    <!-- ═══════════════════════════════════════════ -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-brand">
            <span>🌿</span> Bank Sampah
        </div>

        <div class="sidebar-profile">
            <div class="profile-avatar">{{ substr($pengepul->nama, 0, 1) }}</div>
            <div class="profile-info">
                <span class="profile-name">{{ $pengepul->nama }}</span>
                <span class="profile-role-badge">Pengepul</span>
            </div>
        </div>

        <ul class="sidebar-menu">
            <li>
                <a class="menu-link active" data-tab="tab-dashboard" onclick="switchTab(event,'tab-dashboard')">
                    🏠 Dashboard
                </a>
            </li>
            <li>
                <a class="menu-link" data-tab="tab-timbang" onclick="switchTab(event,'tab-timbang')">
                    ⚖️ Timbang &amp; Setor
                </a>
            </li>
            <li>
                <a class="menu-link" data-tab="tab-setoran" onclick="switchTab(event,'tab-setoran')">
                    💸 Setoran ke Admin
                    @if($transaksiBelumDisetor->count() > 0)
                        <span style="background:#ffb300;color:#fff;border-radius:10px;padding:1px 8px;font-size:11px;margin-left:6px;">{{ $transaksiBelumDisetor->count() }}</span>
                    @endif
                </a>
            </li>
            <li>
                <a class="menu-link" data-tab="tab-riwayat-setoran" onclick="switchTab(event,'tab-riwayat-setoran')">
                    📜 Riwayat Setoran
                </a>
            </li>
            <li>
                <a class="menu-link" data-tab="tab-laporan" onclick="switchTab(event,'tab-laporan')">
                    📊 Laporan Bulanan
                </a>
            </li>
        </ul>

        <div class="sidebar-footer">
            <a href="{{ url('/logout') }}" class="btn-logout-sidebar">🚪 Logout</a>
        </div>
    </aside>

    <!-- ═══════════════════════════════════════════ -->
    <!--          📁 RIGHT PANEL CONTENT             -->
    <!-- ═══════════════════════════════════════════ -->
    <main class="main-panel">
        <header class="panel-header">
            <div style="display:flex;align-items:center;">
                <button class="mobile-toggle" onclick="document.getElementById('sidebar').classList.toggle('mobile-active')">☰</button>
                <div class="header-title">Bank Sampah Digital</div>
            </div>
            <div style="background:var(--primary-light);color:var(--primary);padding:6px 14px;border-radius:20px;font-weight:700;font-size:13px;">
                Pengepul
            </div>
        </header>

        <div class="panel-content">

            @if(session('success'))
                <div class="alert alert-success">✅ {{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-error">⚠️ {{ session('error') }}</div>
            @endif

            <!-- ═══════ TAB: DASHBOARD ═══════ -->
            <div class="tab-content" id="tab-dashboard">

                <div class="welcome-section">
                    <div class="welcome-text">
                        <h1>Selamat Datang, {{ $pengepul->nama }}! 👋</h1>
                        <p>{{ $pengepul->alamat }} &nbsp;|&nbsp; 📞 {{ $pengepul->telepon ?? '-' }}</p>
                    </div>
                </div>

                <div class="stats-grid" style="grid-template-columns: repeat(auto-fit, minmax(160px,1fr));">
                    <div class="card-stat">
                        <div class="stat-icon icon-accent-1">⚖️</div>
                        <div class="stat-content">
                            <h3>Total Transaksi</h3>
                            <p>{{ number_format($totalTransaksi) }}</p>
                        </div>
                    </div>
                    <div class="card-stat">
                        <div class="stat-icon icon-accent-2">🗂️</div>
                        <div class="stat-content">
                            <h3>Total Berat Terkumpul</h3>
                            <p>{{ number_format($totalBeratKg, 2) }} kg</p>
                        </div>
                    </div>
                    <div class="card-stat">
                        <div class="stat-icon icon-accent-3">💰</div>
                        <div class="stat-content">
                            <h3>Nilai ke Nasabah</h3>
                            <p>Rp {{ number_format($totalNilaiRupiah, 0, ',', '.') }}</p>
                        </div>
                    </div>
                    <div class="card-stat" style="border-left:4px solid #ffb300;">
                        <div class="stat-icon" style="background:#fff8e1;color:#ff8f00;">🤑</div>
                        <div class="stat-content">
                            <h3>Total Komisi Saya</h3>
                            <p style="color:#e65100;">Rp {{ number_format($totalKomisi, 0, ',', '.') }}</p>
                        </div>
                    </div>
                    <div class="card-stat" style="border-left:4px solid #d32f2f;">
                        <div class="stat-icon" style="background:#ffebee;color:#c62828;">⏳</div>
                        <div class="stat-content">
                            <h3>Belum Disetor</h3>
                            <p style="color:#c62828;">{{ $transaksiBelumDisetor->count() }} transaksi</p>
                        </div>
                    </div>
                </div>

                <div class="ui-block">
                    <div class="block-title">📋 Transaksi Bulan Ini</div>
                    <div class="table-wrap">
                        <table>
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Nasabah</th>
                                    <th>Jenis Sampah</th>
                                    <th>Berat (kg)</th>
                                    <th>Nilai (Rp)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($transaksibulanIni as $t)
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($t->tanggal)->format('d/m/Y') }}</td>
                                    <td>{{ $t->nasabah->nasabah_nama ?? '-' }}</td>
                                    <td>{{ $t->sampah->sampah_name ?? '-' }}</td>
                                    <td>{{ number_format($t->berat_kg, 2) }}</td>
                                    <td>{{ number_format($t->nilai_idr, 0, ',', '.') }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" style="text-align:center;color:#aaa;padding:24px;">
                                        Belum ada transaksi bulan ini
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Geolokasi Stasiun Pengepul (Update GPS) -->
                <div class="ui-block" style="margin-top:24px;">
                    <div class="block-title">📍 Geolokasi Stasiun Pengepul</div>
                    <p style="font-size:12.5px; color:#555; margin-bottom:16px;">
                        Nasabah memerlukan data GPS Anda untuk mencari stasiun terdekat. Anda dapat memperbarui lokasi koordinat stasiun pengepul Anda berdasarkan posisi GPS perangkat Anda saat ini secara real-time.
                    </p>
                    
                    <div style="background:#eef7f2; border:1px solid #cce6d5; border-radius:8px; padding:16px; margin-bottom:16px;">
                        <p style="font-size:13.5px; color:#333; font-weight:600;">
                            📍 Koordinat Stasiun Saat Ini: 
                            <span style="color:var(--primary); font-family:monospace; font-size:14px;" id="current_coords">
                                {{ $pengepul->latitude ?? '-' }}, {{ $pengepul->longitude ?? '-' }}
                            </span>
                        </p>
                        <div id="gps_status" style="font-size:12.5px; color:#666; font-style:italic; margin-top:6px;">
                            *Tekan tombol di bawah untuk melacak GPS perangkat Anda.
                        </div>
                    </div>
                    
                    <div style="display:flex; gap:12px; align-items:center; flex-wrap:wrap;">
                        <button type="button" onclick="ambilLokasiGPS()" class="btn-filter" style="background-color: var(--secondary); font-size:13px; padding:10px 20px; font-weight:700;">
                            📡 Ambil Lokasi GPS Perangkat
                        </button>
                        
                        <form method="POST" action="{{ route('pengepul.update_lokasi') }}" id="formUpdateLokasi" style="display:none; margin:0;">
                            @csrf
                            <input type="hidden" name="latitude" id="input_latitude">
                            <input type="hidden" name="longitude" id="input_longitude">
                            <button type="submit" class="btn-submit" style="width:auto; padding:10px 24px; background-color: var(--primary);">
                                💾 Simpan Koordinat GPS Baru
                            </button>
                        </form>
                    </div>
                </div>

            </div>

            <!-- ═══════ TAB: TIMBANG & SETOR ═══════ -->
            <div class="tab-content" id="tab-timbang">
                <div class="ui-block">
                    <div class="block-title">⚖️ Timbang &amp; Setor Sampah Nasabah</div>

                    <form method="POST" action="{{ route('pengepul.setor') }}" id="formSetor">
                        @csrf

                        <div class="form-row">
                            <div class="form-group">
                                <label>Nasabah</label>
                                <select name="nasabah_id" id="nasabah_id" class="form-control" required>
                                    <option value="">-- Pilih Nasabah --</option>
                                    @foreach($nasabahs as $nasabah)
                                        <option value="{{ $nasabah->id_nasabah }}">{{ $nasabah->nasabah_nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Jenis Sampah</label>
                                <select name="id_sampah" id="id_sampah" class="form-control" required>
                                    <option value="">-- Pilih Jenis Sampah --</option>
                                    @foreach($sampahs as $s)
                                        <option value="{{ $s->id_sampah }}" data-harga="{{ $s->sampah_harga_kg }}">
                                            {{ $s->sampah_name }} – Rp {{ number_format($s->sampah_harga_kg, 0, ',', '.') }}/kg
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label>Berat (kg)</label>
                                <input type="number" name="berat_kg" id="berat_kg"
                                    class="form-control" step="0.01" min="0.01"
                                    placeholder="Contoh: 2.50" required
                                    oninput="hitungNilai()">
                                <div class="nilai-preview" id="nilaiPreview">
                                    💰 Nilai: <span id="nilaiText">Rp 0</span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Keterangan (opsional)</label>
                                <input type="text" name="keterangan" class="form-control"
                                    placeholder="Catatan tambahan...">
                            </div>
                        </div>

                        <button type="submit" class="btn-submit">✅ Simpan Setoran</button>
                    </form>
                </div>

                @if($errors->any())
                    <div class="alert alert-error">
                        ⚠️ {{ $errors->first() }}
                    </div>
                @endif
            </div>

            <!-- ═══════ TAB: SETORAN KE ADMIN ═══════ -->
            <div class="tab-content" id="tab-setoran">
                <div class="ui-block">
                    <div class="block-title">💸 Setoran ke Admin
                        <span style="font-size:12px;font-weight:500;color:#666;">Pilih transaksi yang akan disetor</span>
                    </div>

                    @if($transaksiBelumDisetor->isEmpty())
                        <div style="text-align:center;padding:40px;color:#aaa;">
                            <div style="font-size:40px;">✅</div>
                            <p style="margin-top:12px;font-size:14px;">Semua transaksi sudah disetor ke admin.</p>
                        </div>
                    @else
                        {{-- Ringkasan setoran --}}
                        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(160px,1fr));gap:14px;margin-bottom:20px;">
                            <div style="background:#e8f5e9;border-radius:10px;padding:14px;text-align:center;">
                                <div style="font-size:12px;color:#555;font-weight:600;">Nilai ke Nasabah</div>
                                <div style="font-size:18px;font-weight:800;color:#1b5e20;margin-top:4px;">Rp {{ number_format($totalBelumDisetor,0,',','.') }}</div>
                            </div>
                            <div style="background:#fff8e1;border-radius:10px;padding:14px;text-align:center;">
                                <div style="font-size:12px;color:#555;font-weight:600;">Komisi Saya (50%)</div>
                                <div style="font-size:18px;font-weight:800;color:#e65100;margin-top:4px;">Rp {{ number_format($totalKomisiBelum,0,',','.') }}</div>
                            </div>
                            <div style="background:#fce4ec;border-radius:10px;padding:14px;text-align:center;">
                                <div style="font-size:12px;color:#555;font-weight:600;">Bagian Admin (50%)</div>
                                <div style="font-size:18px;font-weight:800;color:#c62828;margin-top:4px;">Rp {{ number_format($totalAdminBelum,0,',','.') }}</div>
                            </div>
                            <div style="background:#e3f2fd;border-radius:10px;padding:14px;text-align:center;border:2px solid #1565c0;">
                                <div style="font-size:12px;color:#555;font-weight:600;">Total Disetor ke Admin</div>
                                <div style="font-size:18px;font-weight:800;color:#1565c0;margin-top:4px;">Rp {{ number_format($totalHarusDisetor,0,',','.') }}</div>
                                <div style="font-size:10px;color:#777;margin-top:2px;">(nilai nasabah + bagian admin)</div>
                            </div>
                        </div>

                        <form method="POST" action="{{ route('pengepul.setoran.store') }}" id="formSetoran">
                            @csrf
                            <div class="table-wrap">
                                <table>
                                    <thead>
                                        <tr>
                                            <th><input type="checkbox" id="checkAll" onchange="toggleAllCheck(this)"> Pilih Semua</th>
                                            <th>Tanggal</th>
                                            <th>Nasabah</th>
                                            <th>Jenis Sampah</th>
                                            <th>Berat (kg)</th>
                                            <th>Nilai Nasabah</th>
                                            <th>Komisi Saya</th>
                                            <th>Bagian Admin</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($transaksiBelumDisetor as $t)
                                        <tr>
                                            <td><input type="checkbox" name="transaksi_ids[]" value="{{ $t->id }}" class="trx-check" checked></td>
                                            <td>{{ \Carbon\Carbon::parse($t->tanggal)->format('d/m/Y') }}</td>
                                            <td>{{ $t->nasabah->nasabah_nama ?? '-' }}</td>
                                            <td>{{ $t->sampah->sampah_name ?? '-' }}</td>
                                            <td>{{ number_format($t->berat_kg,2) }}</td>
                                            <td>Rp {{ number_format($t->nilai_idr,0,',','.') }}</td>
                                            <td style="color:#e65100;font-weight:600;">Rp {{ number_format($t->komisi_pengepul,0,',','.') }}</td>
                                            <td style="color:#c62828;font-weight:600;">Rp {{ number_format($t->bagian_admin,0,',','.') }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div style="margin-top:20px;display:flex;justify-content:flex-end;">
                                <button type="submit" class="btn-submit" style="width:auto;padding:12px 32px;"
                                    onclick="return confirm('Yakin ingin membuat setoran? Setelah dibuat, transaksi yang dipilih tidak dapat diubah sebelum admin memverifikasi.')">
                                    💸 Buat Setoran ke Admin
                                </button>
                            </div>
                        </form>
                    @endif
                </div>
            </div>

            <!-- ═══════ TAB: RIWAYAT SETORAN ═══════ -->
            <div class="tab-content" id="tab-riwayat-setoran">
                <div class="ui-block">
                    <div class="block-title">📜 Riwayat Setoran ke Admin</div>
                    <div class="table-wrap">
                        <table>
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Nilai Nasabah</th>
                                    <th>Komisi Saya</th>
                                    <th>Total Disetor</th>
                                    <th>Status</th>
                                    <th>Catatan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($riwayatSetoran as $s)
                                <tr>
                                    <td>{{ $s->created_at->format('d/m/Y H:i') }}</td>
                                    <td>Rp {{ number_format($s->total_nilai_nasabah,0,',','.') }}</td>
                                    <td style="color:#e65100;font-weight:600;">Rp {{ number_format($s->total_komisi_pengepul,0,',','.') }}</td>
                                    <td style="color:#1565c0;font-weight:700;">Rp {{ number_format($s->total_disetor,0,',','.') }}</td>
                                    <td>
                                        @if($s->status === 'terverifikasi')
                                            <span style="background:#e8f5e9;color:#1b5e20;padding:3px 10px;border-radius:12px;font-size:12px;font-weight:700;">✅ Terverifikasi</span>
                                        @elseif($s->status === 'menunggu')
                                            <span style="background:#fff8e1;color:#e65100;padding:3px 10px;border-radius:12px;font-size:12px;font-weight:700;">⏳ Menunggu</span>
                                        @else
                                            <span style="background:#ffebee;color:#c62828;padding:3px 10px;border-radius:12px;font-size:12px;font-weight:700;">❌ Ditolak</span>
                                        @endif
                                    </td>
                                    <td style="color:#666;font-size:12px;">{{ $s->catatan ?? '-' }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" style="text-align:center;color:#aaa;padding:24px;">Belum ada riwayat setoran</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- ═══════ TAB: LAPORAN BULANAN ═══════ -->
            <div class="tab-content" id="tab-laporan">
                <div class="ui-block">
                    <div class="block-title">📊 Laporan Bulanan</div>

                    {{-- Form filter – kirim ke dashboard dengan query param --}}
                    <form method="GET" action="{{ route('pengepul.dashboard') }}" id="formLaporan">
                        <input type="hidden" name="_tab" value="tab-laporan">
                        <div class="filter-row">
                            <div class="form-group">
                                <label>Bulan</label>
                                <select name="bulan" class="form-control">
                                    @foreach(range(1,12) as $bln)
                                        <option value="{{ str_pad($bln,2,'0',STR_PAD_LEFT) }}"
                                            {{ $laporanBulan == str_pad($bln,2,'0',STR_PAD_LEFT) ? 'selected' : '' }}>
                                            {{ \Carbon\Carbon::create()->month($bln)->locale('id')->isoFormat('MMMM') }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Tahun</label>
                                <select name="tahun" class="form-control">
                                    @foreach(range(now()->year, now()->year - 3, -1) as $thn)
                                        <option value="{{ $thn }}" {{ $laporanTahun == $thn ? 'selected' : '' }}>
                                            {{ $thn }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="submit" class="btn-filter">🔍 Filter</button>
                            <a href="{{ route('pengepul.laporan', ['bulan' => $laporanBulan, 'tahun' => $laporanTahun, 'cetak' => 1]) }}"
                               class="btn-print" target="_blank">🖨️ Cetak / Print</a>
                        </div>
                    </form>

                    {{-- Ringkasan --}}
                    <div class="laporan-total">
                        <div class="total-box">
                            <h4>Total Berat</h4>
                            <p>{{ number_format($laporanTotalBerat, 2) }} kg</p>
                        </div>
                        <div class="total-box">
                            <h4>Total Nilai</h4>
                            <p>Rp {{ number_format($laporanTotalNilai, 0, ',', '.') }}</p>
                        </div>
                        <div class="total-box">
                            <h4>Jumlah Transaksi</h4>
                            <p>{{ $laporanTransaksi->count() }}</p>
                        </div>
                    </div>

                    {{-- Tabel hasil --}}
                    <div class="table-wrap">
                        <table>
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Tanggal</th>
                                    <th>Nasabah</th>
                                    <th>Jenis Sampah</th>
                                    <th>Berat (kg)</th>
                                    <th>Nilai (Rp)</th>
                                    <th>Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($laporanTransaksi as $index => $t)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ \Carbon\Carbon::parse($t->tanggal)->format('d/m/Y') }}</td>
                                    <td>{{ $t->nasabah->nasabah_nama ?? '-' }}</td>
                                    <td>{{ $t->sampah->sampah_name ?? '-' }}</td>
                                    <td>{{ number_format($t->berat_kg, 2) }}</td>
                                    <td>{{ number_format($t->nilai_idr, 0, ',', '.') }}</td>
                                    <td>{{ $t->keterangan ?? '-' }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" style="text-align:center;color:#aaa;padding:24px;">
                                        Tidak ada transaksi pada periode ini
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div><!-- /panel-content -->
    </main>
</div><!-- /app-layout -->

<script>
    // ── Tab Engine (identik dengan dashboard utama) ──────────────────
    const STORAGE_KEY = 'BS_pengepul_tab';

    function switchTab(event, tabId) {
        if (event) event.preventDefault();

        document.querySelectorAll('.tab-content').forEach(el => el.style.display = 'none');
        document.querySelectorAll('.menu-link').forEach(el => el.classList.remove('active'));

        const target = document.getElementById(tabId);
        if (target) target.style.display = 'block';

        if (event && event.currentTarget) event.currentTarget.classList.add('active');

        localStorage.setItem(STORAGE_KEY, tabId);
    }

    // Restore tab: prioritas → query param _tab → localStorage → default
    (function () {
        const urlParams   = new URLSearchParams(window.location.search);
        const tabFromUrl  = urlParams.get('_tab');
        const savedTab    = tabFromUrl || localStorage.getItem(STORAGE_KEY) || 'tab-dashboard';

        document.querySelectorAll('.tab-content').forEach(el => el.style.display = 'none');
        document.querySelectorAll('.menu-link').forEach(e => e.classList.remove('active'));

        const target = document.getElementById(savedTab);
        if (target) target.style.display = 'block';

        const link = document.querySelector(`[data-tab="${savedTab}"]`);
        if (link) link.classList.add('active');

        // Simpan ke localStorage agar persistent
        localStorage.setItem(STORAGE_KEY, savedTab);
    })();

    // ── Kalkulasi nilai real-time ──────────────────────────────────
    function hitungNilai() {
        const select    = document.getElementById('id_sampah');
        const beratInput = document.getElementById('berat_kg');
        const nilaiText  = document.getElementById('nilaiText');

        const selectedOption = select.options[select.selectedIndex];
        const harga = parseFloat(selectedOption?.dataset?.harga || 0);
        const berat = parseFloat(beratInput.value || 0);
        const nilai = harga * berat;

        nilaiText.textContent = 'Rp ' + nilai.toLocaleString('id-ID', {minimumFractionDigits: 0});
    }

    document.getElementById('id_sampah')?.addEventListener('change', hitungNilai);

    // ── Redirect tab laporan ke server dengan GET ──────────────────
    // (ditangani langsung via form GET, tidak perlu JS tambahan)

    // ── Ambil lokasi dari GPS Perangkat ─────────────────────────────
    function ambilLokasiGPS() {
        const statusMsg = document.getElementById('gps_status');
        const formUpdate = document.getElementById('formUpdateLokasi');
        const inputLat = document.getElementById('input_latitude');
        const inputLng = document.getElementById('input_longitude');

        if (!navigator.geolocation) {
            statusMsg.innerHTML = '<span style="color:var(--danger);">⚠️ Browser Anda tidak mendukung koordinat GPS / Geolocation.</span>';
            return;
        }

        statusMsg.innerHTML = '🔄 Mengakses koordinat satelit GPS perangkat Anda...';

        navigator.geolocation.getCurrentPosition(
            function(pos) {
                const lat = pos.coords.latitude;
                const lng = pos.coords.longitude;

                statusMsg.innerHTML = '✅ Sukses melacak GPS! Koordinat baru terdeteksi: <code>' + lat.toFixed(7) + ', ' + lng.toFixed(7) + '</code>. Klik tombol Simpan untuk memperbarui lokasi stasiun Anda.';
                
                inputLat.value = lat;
                inputLng.value = lng;
                formUpdate.style.display = 'block';
            },
            function(err) {
                statusMsg.innerHTML = '<span style="color:var(--danger);">⚠️ Gagal melacak lokasi. Mohon izinkan akses lokasi (GPS) di browser Anda.</span>';
            },
            {
                enableHighAccuracy: true,
                timeout: 10000,
                maximumAge: 0
            }
        );
    }

    // ── Toggle semua checkbox setoran ────────────────────────────────
    function toggleAllCheck(master) {
        document.querySelectorAll('.trx-check').forEach(cb => cb.checked = master.checked);
    }

    // ── Auto-buka tab jika ada URL hash ─────────────────────────────
    if (window.location.hash) {
        const target = window.location.hash.replace('#', '');
        const el = document.getElementById(target);
        if (el && el.classList.contains('tab-content')) {
            document.querySelectorAll('.tab-content').forEach(t => t.style.display = 'none');
            document.querySelectorAll('.menu-link').forEach(l => l.classList.remove('active'));
            el.style.display = 'block';
            const link = document.querySelector(`[data-tab="${target}"]`);
            if (link) link.classList.add('active');
        }
    }
</script>
</body>
</html>
