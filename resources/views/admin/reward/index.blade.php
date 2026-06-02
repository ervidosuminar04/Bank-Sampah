<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eco Rewards – Realive</title>
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
            --text-primary:     var(--color-canopy);
            --text-secondary:   var(--color-forest);
            --text-muted:       var(--color-fog);
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
            --radius-xl:   36px;
            --radius-full: 9999px;
            
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            --ease-spring: cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Nunito Sans', sans-serif; background: var(--bg-page); color: var(--text-primary); min-height: 100vh; }

        body::before {
            content: "";
            position: absolute;
            top: 0; left: 0; width: 100%; height: 100%;
            z-index: -1;
            background-image: url('{{ asset('images/Pattern 1@3x.png') }}');
            background-size: cover;
            background-position: center;
            opacity: 0.12;
            pointer-events: none;
        }

        .topbar {
            background-color: var(--color-canopy);
            color: #fff;
            padding: 16px 32px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 2px 10px rgba(0,0,0,0.15);
        }
        .topbar-brand {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .topbar-brand img {
            max-height: 36px;
            object-fit: contain;
        }
        .topbar h1 { font-family: 'Nunito', sans-serif; font-size: 20px; font-weight: 800; }
        .topbar a {
            color: #fff;
            text-decoration: none;
            font-size: 13.5px;
            font-weight: 700;
            background: rgba(255,255,255,0.15);
            padding: 8px 18px;
            border-radius: var(--radius-full);
            transition: var(--transition);
        }
        .topbar a:hover { background: rgba(255,255,255,0.25); }

        .container { max-width: 1200px; margin: 0 auto; padding: 32px 24px; }

        .alert {
            padding: 16px 20px;
            border-radius: var(--radius-md);
            margin-bottom: 25px;
            font-size: 14.5px;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 12px;
            box-shadow: var(--shadow-md);
            border-left: 5px solid;
        }
        .alert-success { background-color: var(--color-mist); border-left-color: var(--color-sprout); color: var(--color-forest); }
        .alert-error { background-color: rgba(230, 57, 70, 0.1); border-left-color: var(--color-flame); color: var(--color-flame); }

        /* Main Tabs Navigation */
        .main-tabs { display: flex; gap: 12px; margin-bottom: 28px; border-bottom: 2px solid var(--color-smoke); padding-bottom: 12px; }
        .main-tab-btn {
            padding: 12px 24px;
            border: none;
            background: transparent;
            color: var(--color-forest);
            font-family: 'Nunito', sans-serif;
            font-weight: 800;
            font-size: 15px;
            cursor: pointer;
            transition: var(--transition);
            border-radius: var(--radius-sm);
        }
        .main-tab-btn:hover { background-color: var(--color-mist); }
        .main-tab-btn.active { background-color: var(--color-canopy); color: var(--color-white); }

        .main-pane { display: none; }
        .main-pane.active { display: block; }

        /* Sub tabs for Status selection */
        .tabs { display: flex; gap: 8px; margin-bottom: 20px; }
        .tab-btn {
            padding: 8px 18px;
            border-radius: var(--radius-full);
            border: 2px solid transparent;
            background: var(--color-white);
            color: var(--color-forest);
            font-weight: 700;
            font-size: 13px;
            cursor: pointer;
            transition: var(--transition);
            box-shadow: var(--shadow-sm);
        }
        .tab-btn:hover { background-color: var(--color-mist); border-color: var(--color-smoke); }
        .tab-btn.active { background: var(--color-forest); color: #fff; border-color: var(--color-forest); }

        .tab-pane { display: none; }
        .tab-pane.active { display: block; }

        /* Cards and blocks */
        .card {
            background: var(--bg-surface);
            border-radius: 24px 24px 16px 16px;
            box-shadow: var(--shadow-md);
            border: 1px solid rgba(45, 106, 45, 0.05);
            overflow: hidden;
            margin-bottom: 24px;
        }
        .card-head {
            padding: 20px 24px;
            font-family: 'Nunito', sans-serif;
            font-size: 16px;
            font-weight: 800;
            color: var(--color-canopy);
            border-bottom: 2px solid var(--color-mist);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        /* Table */
        .table-wrap { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; font-size: 14px; }
        th { background: var(--color-mist); padding: 14px 16px; font-weight: 700; color: var(--color-canopy); border-bottom: 2px solid var(--color-smoke); text-align: left; text-transform: uppercase; font-size: 11.5px; letter-spacing: 0.05em; }
        td { padding: 14px 16px; border-bottom: 1px solid var(--color-mist); vertical-align: middle; }
        tr:hover td { background: rgba(244, 247, 240, 0.5); }

        /* Action buttons */
        .btn {
            padding: 8px 18px;
            border-radius: var(--radius-full);
            border: none;
            font-weight: 700;
            font-size: 12.5px;
            cursor: pointer;
            transition: var(--transition);
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        .btn-approve { background: var(--accent-cta); color: var(--color-canopy); }
        .btn-approve:hover { background: var(--color-sunburst); transform: scale(1.03); box-shadow: var(--shadow-glow); }
        
        .btn-reject { background: transparent; border: 2px solid var(--color-flame); color: var(--color-flame); }
        .btn-reject:hover { background: var(--color-flame); color: var(--color-white); }

        .btn-add { background: var(--gradient-brand); color: white; border-radius: var(--radius-full); box-shadow: var(--shadow-sm); }
        .btn-add:hover { transform: translateY(-1px); box-shadow: var(--shadow-md); }

        .btn-edit { background: var(--color-white); border: 2px solid var(--color-forest); color: var(--color-forest); }
        .btn-edit:hover { background: var(--color-mist); border-color: var(--color-sprout); }

        .btn-delete { background: rgba(230, 57, 70, 0.1); color: var(--color-flame); }
        .btn-delete:hover { background: var(--color-flame); color: var(--color-white); }

        /* Form elements */
        .form-group { margin-bottom: 16px; }
        .form-group label { display: block; font-size: 13px; font-weight: 700; color: var(--color-canopy); margin-bottom: 6px; }
        .form-control { width: 100%; padding: 12px 14px; border: 2px solid var(--border-default); border-radius: var(--radius-sm); font-size: 14px; font-family: inherit; outline: none; background: #fff; }
        .form-control:focus { border-color: var(--border-focus); box-shadow: var(--shadow-focus); }
        textarea.form-control { height: auto; resize: vertical; }

        /* Modals style */
        .modal-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(10, 10, 10, 0.45);
            backdrop-filter: blur(6px);
            z-index: 1000;
            justify-content: center;
            align-items: center;
            transition: opacity 0.3s ease;
        }
        .modal-overlay.show { display: flex; }
        .modal-box {
            background: #fff;
            border-radius: 24px;
            padding: 32px;
            width: 480px;
            max-width: 95vw;
            box-shadow: var(--shadow-xl);
            border: 1px solid rgba(125, 184, 37, 0.15);
            animation: sheetUp 0.35s var(--ease-spring) forwards;
        }
        @keyframes sheetUp {
            from { transform: translateY(30px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }

        .modal-title { font-family: 'Nunito', sans-serif; font-size: 18px; font-weight: 800; margin-bottom: 8px; color: var(--color-canopy); }
        .modal-sub { font-size: 13.5px; color: var(--color-forest); margin-bottom: 18px; line-height: 1.5; }
        .modal-actions { display: flex; gap: 10px; justify-content: flex-end; margin-top: 20px; }
        .btn-cancel { background: var(--color-mist); color: var(--color-forest); }
        .btn-cancel:hover { background: var(--color-smoke); }

        .empty-state { text-align: center; padding: 48px; color: var(--color-fog); }
        .empty-state .icon { font-size: 48px; margin-bottom: 12px; }
    </style>
</head>
<body>

<div class="topbar">
    <div class="topbar-brand">
        <h1>Kelola Penukaran &amp; Katalog Hadiah</h1>
    </div>
    <a href="{{ url('/dashboard') }}">← Kembali ke Dashboard</a>
</div>

<div class="container">

    @if(session('success'))
        <div class="alert alert-success">✅ {{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-error">⚠️ {{ session('error') }}</div>
    @endif

    <!-- Main Navigation Tabs -->
    <div class="main-tabs">
        <button class="main-tab-btn active" onclick="switchMainTab(event, 'pane-penukaran')">⏳ Kelola Penukaran</button>
        <button class="main-tab-btn" onclick="switchMainTab(event, 'pane-katalog')">🛍️ Kelola Katalog Hadiah</button>
    </div>

    <!-- PANE 1: KELOLA PENUKARAN -->
    <div class="main-pane active" id="pane-penukaran">
        <!-- Sub-tabs untuk filter Status -->
        <div class="tabs">
            <button class="tab-btn active" onclick="switchSubTab(event, 'sub-menunggu')">⏳ Menunggu ({{ $menunggu->count() }})</button>
            <button class="tab-btn" onclick="switchSubTab(event, 'sub-diambil')">✅ Sudah Diambil ({{ $diambil->count() }})</button>
            <button class="tab-btn" onclick="switchSubTab(event, 'sub-ditolak')">❌ Ditolak ({{ $ditolak->count() }})</button>
        </div>

        <!-- Sub-pane Menunggu -->
        <div class="tab-pane active" id="sub-menunggu">
            <div class="card">
                <div class="card-head">Permohonan Penukaran Hadiah – Menunggu Pengambilan</div>
                <div class="table-wrap">
                    <table>
                        <thead>
                            <tr>
                                <th>Nasabah</th>
                                <th>Tanggal Tukar</th>
                                <th>Barang Hadiah</th>
                                <th>Jumlah</th>
                                <th>Eco Poin Ditukar</th>
                                <th style="width: 320px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($menunggu as $t)
                            <tr>
                                <td>
                                    <strong>{{ $t->nasabah->nasabah_nama ?? '-' }}</strong><br>
                                    <span style="font-size:11px;color:var(--color-fog);">{{ $t->nasabah->nasabah_username ?? '-' }}</span>
                                </td>
                                <td style="font-family:'JetBrains Mono',monospace;">{{ \Carbon\Carbon::parse($t->tanggal_tukar)->format('d/m/Y') }}</td>
                                <td style="font-weight:700;color:var(--color-forest);">{{ $t->hadiah->nama_hadiah ?? 'Hadiah Dihapus' }}</td>
                                <td>{{ $t->jumlah }} pcs</td>
                                <td style="font-family:'JetBrains Mono',monospace;font-weight:800;color:var(--color-forest);font-size:15px;">{{ $t->total_poin_ditukar }} Poin</td>
                                <td>
                                    <button class="btn btn-approve" onclick="openApprove({{ $t->id_penukaran }}, '{{ $t->nasabah->nasabah_nama ?? '' }}', '{{ $t->hadiah->nama_hadiah ?? '' }}', {{ $t->jumlah }})">✅ Serahkan Barang</button>
                                    <button class="btn btn-reject" onclick="openReject({{ $t->id_penukaran }}, '{{ $t->nasabah->nasabah_nama ?? '' }}')">❌ Tolak</button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6">
                                    <div class="empty-state">
                                        <div class="icon">🎉</div>
                                        <p style="font-weight:700;">Tidak ada permohonan penukaran barang yang menunggu</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Sub-pane Sudah Diambil -->
        <div class="tab-pane" id="sub-diambil">
            <div class="card">
                <div class="card-head">Penukaran Hadiah yang Sudah Diambil</div>
                <div class="table-wrap">
                    <table>
                        <thead>
                            <tr>
                                <th>Nasabah</th>
                                <th>Tanggal Tukar</th>
                                <th>Barang Hadiah</th>
                                <th>Jumlah</th>
                                <th>Poin Ditukar</th>
                                <th>Petugas Verifikator</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($diambil as $t)
                            <tr>
                                <td><strong>{{ $t->nasabah->nasabah_nama ?? '-' }}</strong></td>
                                <td style="font-family:'JetBrains Mono',monospace;">{{ \Carbon\Carbon::parse($t->tanggal_tukar)->format('d/m/Y') }}</td>
                                <td style="font-weight:700;color:var(--color-forest);">{{ $t->hadiah->nama_hadiah ?? 'Hadiah Dihapus' }}</td>
                                <td>{{ $t->jumlah }} pcs</td>
                                <td style="font-family:'JetBrains Mono',monospace;font-weight:700;color:var(--color-forest);">{{ $t->total_poin_ditukar }} Poin</td>
                                <td><span style="font-size:12.5px;color:var(--color-canopy);font-weight:700;">{{ $t->admin->admin_nama ?? 'Admin' }}</span></td>
                            </tr>
                            @empty
                            <tr><td colspan="6"><div class="empty-state"><p style="font-weight:700;">Belum ada penukaran barang yang diambil</p></div></td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Sub-pane Ditolak -->
        <div class="tab-pane" id="sub-ditolak">
            <div class="card">
                <div class="card-head">Penukaran Hadiah yang Ditolak (Poin &amp; Stok Dikembalikan)</div>
                <div class="table-wrap">
                    <table>
                        <thead>
                            <tr>
                                <th>Nasabah</th>
                                <th>Tanggal Tukar</th>
                                <th>Barang Hadiah</th>
                                <th>Jumlah</th>
                                <th>Poin Direfund</th>
                                <th>Alasan Penolakan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($ditolak as $t)
                            <tr>
                                <td><strong>{{ $t->nasabah->nasabah_nama ?? '-' }}</strong></td>
                                <td style="font-family:'JetBrains Mono',monospace;">{{ \Carbon\Carbon::parse($t->tanggal_tukar)->format('d/m/Y') }}</td>
                                <td style="font-weight:700;color:var(--color-canopy);">{{ $t->hadiah->nama_hadiah ?? 'Hadiah Dihapus' }}</td>
                                <td>{{ $t->jumlah }} pcs</td>
                                <td style="font-family:'JetBrains Mono',monospace;font-weight:700;color:var(--color-flame);">+ {{ $t->total_poin_ditukar }} Poin</td>
                                <td style="color:var(--color-flame);font-size:13px;font-weight:600;">{{ $t->catatan ?? '-' }}</td>
                            </tr>
                            @empty
                            <tr><td colspan="6"><div class="empty-state"><p style="font-weight:700;">Tidak ada penukaran barang yang ditolak</p></div></td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- PANE 2: KELOLA KATALOG HADIAH (CRUD) -->
    <div class="main-pane" id="pane-katalog">
        <div class="card">
            <div class="card-head">
                <span>🛍&nbsp; Daftar Katalog Barang Eco Rewards</span>
                <button class="btn btn-add" onclick="openAddHadiah()">➕ Tambah Hadiah Baru</button>
            </div>
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Nama Barang</th>
                            <th>Poin Dibutuhkan</th>
                            <th>Stok Tersedia</th>
                            <th>Keterangan</th>
                            <th style="width:200px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($hadiahs as $h)
                        <tr>
                            <td><strong style="color:var(--color-canopy);">{{ $h->nama_hadiah }}</strong></td>
                            <td style="font-family:'JetBrains Mono',monospace;font-weight:700;color:var(--color-forest);">{{ $h->poin_butuh }} Eco Poin</td>
                            <td>
                                <strong style="{{ $h->stok > 0 ? 'color:var(--color-forest);' : 'color:var(--color-flame);' }}">
                                    {{ $h->stok }} pcs
                                </strong>
                            </td>
                            <td style="font-size:13px;color:var(--color-forest);line-height:1.4;">{{ $h->keterangan ?? '-' }}</td>
                            <td>
                                <button class="btn btn-edit" onclick="openEditHadiah({{ $h->id_hadiah }}, '{{ $h->nama_hadiah }}', {{ $h->poin_butuh }}, {{ $h->stok }}, '{{ $h->keterangan ?? '' }}')">✏️ Edit</button>
                                <form method="POST" action="/admin/hadiah/delete/{{ $h->id_hadiah }}" style="display:inline;" onsubmit="return confirm('Apakah Anda yakin ingin menghapus barang ini dari katalog?')">
                                    @csrf
                                    <button type="submit" class="btn btn-delete">🗑️ Hapus</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5">
                                <div class="empty-state"><p style="font-weight:700;">Belum ada hadiah di katalog. Klik tombol di kanan atas untuk menambah.</p></div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

<!-- MODAL SECTION -->

<!-- 1. Modal Setujui Penukaran -->
<div class="modal-overlay" id="modalApprove">
    <div class="modal-box">
        <div class="modal-title">✅ Serahkan Hadiah</div>
        <div class="modal-sub" id="approveDesc"></div>
        <form method="POST" id="formApprove">
            @csrf
            <div class="modal-actions">
                <button type="button" class="btn btn-cancel" onclick="closeModal('modalApprove')">Batal</button>
                <button type="submit" class="btn btn-approve">✅ Serahkan Barang</button>
            </div>
        </form>
    </div>
</div>

<!-- 2. Modal Tolak Penukaran -->
<div class="modal-overlay" id="modalReject">
    <div class="modal-box">
        <div class="modal-title">❌ Tolak Penukaran</div>
        <div class="modal-sub" id="rejectDesc"></div>
        <form method="POST" id="formReject">
            @csrf
            <div class="form-group">
                <label>Alasan Penolakan <span style="color:var(--color-flame)">*</span></label>
                <input type="text" name="catatan" class="form-control" placeholder="Jelaskan alasan penolakan..." required>
            </div>
            <div class="modal-actions">
                <button type="button" class="btn btn-cancel" onclick="closeModal('modalReject')">Batal</button>
                <button type="submit" class="btn btn-approve" style="background:var(--color-flame); color:white;">❌ Tolak &amp; Refund Poin</button>
            </div>
        </form>
    </div>
</div>

<!-- 3. Modal Tambah Hadiah -->
<div class="modal-overlay" id="modalAddHadiah">
    <div class="modal-box">
        <div class="modal-title">🎁 Tambah Hadiah Baru</div>
        <div class="modal-sub">Masukkan rincian barang ramah lingkungan atau sembako untuk dipublikasikan ke katalog nasabah.</div>
        <form method="POST" action="/admin/hadiah/store">
            @csrf
            <div class="form-group">
                <label for="add_nama">Nama Barang</label>
                <input type="text" name="nama_hadiah" id="add_nama" class="form-control" placeholder="Contoh: Sabun Cuci Piring Cair 400ml" required>
            </div>
            <div style="display:grid; grid-template-columns: 1fr 1fr; gap:15px;">
                <div class="form-group">
                    <label for="add_poin">Biaya (Eco Poin)</label>
                    <input type="number" name="poin_butuh" id="add_poin" class="form-control" min="1" placeholder="Contoh: 100" required>
                </div>
                <div class="form-group">
                    <label for="add_stok">Stok Awal (pcs)</label>
                    <input type="number" name="stok" id="add_stok" class="form-control" min="0" placeholder="Contoh: 50" required>
                </div>
            </div>
            <div class="form-group">
                <label for="add_keterangan">Keterangan Barang</label>
                <textarea name="keterangan" id="add_keterangan" class="form-control" rows="3" placeholder="Contoh: Sabun pencuci ramah lingkungan buatan lokal..."></textarea>
            </div>
            <div class="modal-actions">
                <button type="button" class="btn btn-cancel" onclick="closeModal('modalAddHadiah')">Batal</button>
                <button type="submit" class="btn btn-approve">➕ Simpan Hadiah</button>
            </div>
        </form>
    </div>
</div>

<!-- 4. Modal Edit Hadiah -->
<div class="modal-overlay" id="modalEditHadiah">
    <div class="modal-box">
        <div class="modal-title">✏️ Edit Detail Hadiah</div>
        <div class="modal-sub">Perbarui informasi barang hadiah katalog di bawah ini.</div>
        <form method="POST" id="formEditHadiah">
            @csrf
            <div class="form-group">
                <label for="edit_nama">Nama Barang</label>
                <input type="text" name="nama_hadiah" id="edit_nama" class="form-control" required>
            </div>
            <div style="display:grid; grid-template-columns: 1fr 1fr; gap:15px;">
                <div class="form-group">
                    <label for="edit_poin">Biaya (Eco Poin)</label>
                    <input type="number" name="poin_butuh" id="edit_poin" class="form-control" min="1" required>
                </div>
                <div class="form-group">
                    <label for="edit_stok">Stok Tersedia (pcs)</label>
                    <input type="number" name="stok" id="edit_stok" class="form-control" min="0" required>
                </div>
            </div>
            <div class="form-group">
                <label for="edit_keterangan">Keterangan Barang</label>
                <textarea name="keterangan" id="edit_keterangan" class="form-control" rows="3"></textarea>
            </div>
            <div class="modal-actions">
                <button type="button" class="btn btn-cancel" onclick="closeModal('modalEditHadiah')">Batal</button>
                <button type="submit" class="btn btn-approve">💾 Perbarui Hadiah</button>
            </div>
        </form>
    </div>
</div>

<script>
// Main tabs navigation
function switchMainTab(e, paneId) {
    document.querySelectorAll('.main-tab-btn').forEach(b => b.classList.remove('active'));
    document.querySelectorAll('.main-pane').forEach(p => p.classList.remove('active'));
    e.target.classList.add('active');
    document.getElementById(paneId).classList.add('active');
}

// Sub tabs navigation
function switchSubTab(e, paneId) {
    document.querySelectorAll('#pane-penukaran .tab-btn').forEach(b => b.classList.remove('active'));
    document.querySelectorAll('#pane-penukaran .tab-pane').forEach(p => p.classList.remove('active'));
    e.target.classList.add('active');
    document.getElementById(paneId).classList.add('active');
}

// Modal actions
function openApprove(id, nasabah, barang, qty) {
    document.getElementById('formApprove').action = `/admin/penukaran-hadiah/${id}/approve`;
    document.getElementById('approveDesc').innerHTML = `
        Konfirmasi bahwa <b>${nasabah}</b> telah mengambil barang <b>${qty}x ${barang}</b> secara fisik.<br>
        Status penukaran akan diubah menjadi <b>Sudah Diambil</b>.
    `;
    document.getElementById('modalApprove').classList.add('show');
}

// Open reject modal
function openReject(id, nasabah) {
    document.getElementById('formReject').action = `/admin/penukaran-hadiah/${id}/reject`;
    document.getElementById('rejectDesc').innerHTML = `
        Permohonan penukaran oleh nasabah <b>${nasabah}</b> akan ditolak.<br>
        Eco Poin nasabah dan stok barang di katalog akan dikembalikan secara otomatis.
    `;
    document.getElementById('modalReject').classList.add('show');
}

function openAddHadiah() {
    document.getElementById('modalAddHadiah').classList.add('show');
}

function openEditHadiah(id, nama, poin, stok, ket) {
    document.getElementById('formEditHadiah').action = `/admin/hadiah/update/${id}`;
    document.getElementById('edit_nama').value = nama;
    document.getElementById('edit_poin').value = poin;
    document.getElementById('edit_stok').value = stok;
    document.getElementById('edit_keterangan').value = ket;
    document.getElementById('modalEditHadiah').classList.add('show');
}

function closeModal(id) {
    document.getElementById(id).classList.remove('show');
}

// Close modals when clicking on background overlay
document.querySelectorAll('.modal-overlay').forEach(m => {
    m.addEventListener('click', e => { if (e.target === m) m.classList.remove('show'); });
});
</script>
</body>
</html>
