<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Pencairan Saldo Nasabah – Realive</title>
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
        
        /* Stats */
        .stats-row { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; margin-bottom: 28px; }
        .stat-card {
            background: var(--bg-surface);
            border-radius: var(--radius-md);
            padding: 20px;
            box-shadow: var(--shadow-md);
            border: 1px solid rgba(45, 106, 45, 0.05);
            border-bottom: 4px solid;
            transition: var(--transition);
        }
        .stat-card:hover { transform: translateY(-4px); box-shadow: var(--shadow-lg); }
        .stat-card .lbl { font-size: 11.5px; color: var(--color-forest); font-weight: 700; text-transform: uppercase; margin-bottom: 8px; letter-spacing: 0.05em; }
        .stat-card .val { font-family: 'JetBrains Mono', monospace; font-size: 22px; font-weight: 800; }
        
        /* Tabs */
        .tabs { display: flex; gap: 8px; margin-bottom: 24px; flex-wrap: wrap; }
        .tab-btn {
            padding: 10px 22px;
            border-radius: var(--radius-full);
            border: 2px solid transparent;
            background: var(--color-white);
            color: var(--color-forest);
            font-weight: 700;
            font-size: 13.5px;
            cursor: pointer;
            transition: var(--transition);
            box-shadow: var(--shadow-sm);
        }
        .tab-btn:hover { background-color: var(--color-mist); border-color: var(--color-smoke); }
        .tab-btn.active { background: var(--color-forest); color: #fff; border-color: var(--color-forest); }
        
        .tab-pane { display: none; }
        .tab-pane.active { display: block; }
        
        /* Table */
        .card {
            background: var(--bg-surface);
            border-radius: 24px 24px 16px 16px;
            box-shadow: var(--shadow-md);
            border: 1px solid rgba(45, 106, 45, 0.05);
            overflow: hidden;
        }
        .card-head {
            padding: 18px 22px;
            font-family: 'Nunito', sans-serif;
            font-size: 16px;
            font-weight: 800;
            color: var(--color-canopy);
            border-bottom: 2px solid var(--color-mist);
        }
        .table-wrap { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; font-size: 14px; }
        th { background: var(--color-mist); padding: 14px 16px; font-weight: 700; color: var(--color-canopy); border-bottom: 2px solid var(--color-smoke); text-align: left; text-transform: uppercase; font-size: 12px; letter-spacing: 0.05em; }
        td { padding: 14px 16px; border-bottom: 1px solid var(--color-mist); vertical-align: middle; }
        tr:hover td { background: rgba(244, 247, 240, 0.5); }
        
        td.mono-col { font-family: 'JetBrains Mono', monospace; font-weight: 600; }
        
        /* Badges */
        .badge { display: inline-block; padding: 4px 12px; border-radius: var(--radius-full); font-size: 12px; font-weight: 700; text-transform: uppercase; }
        .badge-menunggu { background: rgba(255, 215, 0, 0.15); color: var(--color-sunburst); }
        .badge-disetujui { background: var(--color-mist); color: var(--color-forest); border: 1px solid rgba(45,106,45,0.15); }
        .badge-ditolak { background: rgba(230, 57, 70, 0.15); color: var(--color-flame); }
        
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
        
        /* Modal Overlay & Sheet */
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
            width: 460px;
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
        .form-group { margin-bottom: 16px; }
        .form-group label { display: block; font-size: 13px; font-weight: 700; color: var(--color-canopy); margin-bottom: 6px; }
        .form-control { width: 100%; height: 46px; padding: 0 14px; border: 2px solid var(--border-default); border-radius: var(--radius-sm); font-size: 13.5px; font-family: inherit; outline: none; background: #fff; }
        .form-control:focus { border-color: var(--border-focus); box-shadow: var(--shadow-focus); }
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
        <h1>Kelola Pencairan Saldo</h1>
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

    {{-- Statistik --}}
    <div class="stats-row">
        <div class="stat-card" style="border-bottom-color: var(--color-sunburst);">
            <div class="lbl">⏳ Menunggu Persetujuan</div>
            <div class="val" style="color:var(--color-sunburst);">{{ $menunggu->count() }} Trx</div>
        </div>
        <div class="stat-card" style="border-bottom-color: var(--color-sprout);">
            <div class="lbl">✅ Disetujui</div>
            <div class="val" style="color:var(--color-forest);">{{ $disetujui->count() }} Trx</div>
        </div>
        <div class="stat-card" style="border-bottom-color: var(--color-flame);">
            <div class="lbl">❌ Ditolak</div>
            <div class="val" style="color:var(--color-flame);">{{ $ditolak->count() }} Trx</div>
        </div>
        <div class="stat-card" style="border-bottom-color: var(--color-forest); background:var(--color-mist);">
            <div class="lbl">💰 Total Dicairkan</div>
            <div class="val" style="color:var(--color-forest); font-size:18px;">Rp {{ number_format($totalDicairkan,0,',','.') }}</div>
        </div>
    </div>

    {{-- Tabs --}}
    <div class="tabs">
        <button class="tab-btn active" onclick="switchTab(event,'pane-menunggu')">⏳ Menunggu ({{ $menunggu->count() }})</button>
        <button class="tab-btn" onclick="switchTab(event,'pane-disetujui')">✅ Disetujui ({{ $disetujui->count() }})</button>
        <button class="tab-btn" onclick="switchTab(event,'pane-ditolak')">❌ Ditolak ({{ $ditolak->count() }})</button>
    </div>

    {{-- Pane Menunggu --}}
    <div class="tab-pane active" id="pane-menunggu">
        <div class="card">
            <div class="card-head">Pengajuan Pencairan – Menunggu Persetujuan</div>
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Nasabah</th>
                            <th>Tgl Pengajuan</th>
                            <th>Jumlah Dicairkan</th>
                            <th>Rekening Tujuan</th>
                            <th>Sisa Saldo (Perkiraan)</th>
                            <th>Saldo Saat Ini</th>
                            <th style="width: 220px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($menunggu as $t)
                        <tr>
                            <td>
                                <strong>{{ $t->nasabah->nasabah_nama ?? '-' }}</strong><br>
                                <span style="font-size:11px;color:var(--color-fog);">{{ $t->nasabah->nasabah_username ?? '-' }}</span>
                            </td>
                            <td class="mono-col">{{ \Carbon\Carbon::parse($t->transaksi_tarik_tanggal)->format('d/m/Y') }}</td>
                            <td class="mono-col" style="font-weight:800;color:var(--color-flame);font-size:15px;">Rp {{ number_format($t->transaksi_tarik_jumlah,0,',','.') }}</td>
                            <td>
                                @if($t->transaksi_tarik_bank_tujuan)
                                    <div style="font-weight: 700; color: var(--color-forest);">
                                        {{ $t->transaksi_tarik_bank_tujuan }} - {{ $t->transaksi_tarik_nomor_rekening }}
                                    </div>
                                    <div style="font-size: 11px; color: var(--color-canopy); margin-top: 2px;">
                                        a/n {{ $t->transaksi_tarik_atas_nama }}
                                    </div>
                                @else
                                    <span style="color:var(--color-fog); font-style:italic;">Manual / Tunai</span>
                                @endif
                            </td>
                            <td class="mono-col">Rp {{ number_format($t->transaksi_tarik_sisa_saldo,0,',','.') }}</td>
                            <td class="mono-col">Rp {{ number_format($t->nasabah->nasabah_saldo ?? 0,0,',','.') }}</td>
                            <td>
                                <button class="btn btn-approve" onclick="openApprove({{ $t->id_tarik }}, '{{ $t->nasabah->nasabah_nama ?? '' }}', {{ $t->transaksi_tarik_jumlah }}, '{{ $t->transaksi_tarik_bank_tujuan }}', '{{ $t->transaksi_tarik_nomor_rekening }}', '{{ $t->transaksi_tarik_atas_nama }}')">✅ Setujui</button>
                                <button class="btn btn-reject" onclick="openReject({{ $t->id_tarik }}, '{{ $t->nasabah->nasabah_nama ?? '' }}')">❌ Tolak</button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7">
                                <div class="empty-state">
                                    <div class="icon">🎉</div>
                                    <p style="font-weight:700;">Tidak ada pengajuan pencairan yang menunggu</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Pane Disetujui --}}
    <div class="tab-pane" id="pane-disetujui">
        <div class="card">
            <div class="card-head">Pencairan yang Disetujui</div>
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Nasabah</th>
                            <th>Tgl Pengajuan</th>
                            <th>Jumlah Dicairkan</th>
                            <th>Rekening Tujuan</th>
                            <th>Sisa Saldo</th>
                            <th>Catatan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($disetujui as $t)
                        <tr>
                            <td><strong>{{ $t->nasabah->nasabah_nama ?? '-' }}</strong></td>
                            <td class="mono-col">{{ \Carbon\Carbon::parse($t->transaksi_tarik_tanggal)->format('d/m/Y') }}</td>
                            <td class="mono-col" style="font-weight:700;color:var(--color-forest);">Rp {{ number_format($t->transaksi_tarik_jumlah,0,',','.') }}</td>
                            <td>
                                @if($t->transaksi_tarik_bank_tujuan)
                                    <div style="font-weight: 700; color: var(--color-canopy);">
                                        {{ $t->transaksi_tarik_bank_tujuan }} - {{ $t->transaksi_tarik_nomor_rekening }}
                                    </div>
                                    <div style="font-size: 11px; color: var(--color-fog);">
                                        a/n {{ $t->transaksi_tarik_atas_nama }}
                                    </div>
                                @else
                                    <span style="color:var(--color-fog); font-style:italic;">Manual / Tunai</span>
                                @endif
                                @if($t->transaksi_tarik_gambar)
                                    <div style="margin-top: 6px;">
                                        <a href="{{ asset('storage/' . $t->transaksi_tarik_gambar) }}" target="_blank" class="badge badge-terverifikasi" style="text-decoration:none; display:inline-flex; align-items:center; gap:4px; font-size:11px;">
                                            🖼️ Lihat Bukti
                                        </a>
                                    </div>
                                @endif
                            </td>
                            <td class="mono-col">Rp {{ number_format($t->transaksi_tarik_sisa_saldo,0,',','.') }}</td>
                            <td style="color:var(--color-forest);font-size:13px;">{{ $t->transaksi_tarik_catatan ?? '-' }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="6"><div class="empty-state"><p style="font-weight:700;">Belum ada pencairan yang disetujui</p></div></td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Pane Ditolak --}}
    <div class="tab-pane" id="pane-ditolak">
        <div class="card">
            <div class="card-head">Pencairan yang Ditolak</div>
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Nasabah</th>
                            <th>Tgl Pengajuan</th>
                            <th>Jumlah</th>
                            <th>Rekening Tujuan</th>
                            <th>Alasan Ditolak</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($ditolak as $t)
                        <tr>
                            <td><strong>{{ $t->nasabah->nasabah_nama ?? '-' }}</strong></td>
                            <td class="mono-col">{{ \Carbon\Carbon::parse($t->transaksi_tarik_tanggal)->format('d/m/Y') }}</td>
                            <td class="mono-col" style="color:var(--color-flame);font-weight:700;">Rp {{ number_format($t->transaksi_tarik_jumlah,0,',','.') }}</td>
                            <td>
                                @if($t->transaksi_tarik_bank_tujuan)
                                    <div style="font-weight: 700; color: var(--color-canopy);">
                                        {{ $t->transaksi_tarik_bank_tujuan }} - {{ $t->transaksi_tarik_nomor_rekening }}
                                    </div>
                                    <div style="font-size: 11px; color: var(--color-fog);">
                                        a/n {{ $t->transaksi_tarik_atas_nama }}
                                    </div>
                                @else
                                    <span style="color:var(--color-fog); font-style:italic;">Manual / Tunai</span>
                                @endif
                            </td>
                            <td style="color:var(--color-flame);font-size:13px;font-weight:600;">{{ $t->transaksi_tarik_catatan ?? '-' }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="5"><div class="empty-state"><p style="font-weight:700;">Tidak ada pencairan yang ditolak</p></div></td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

{{-- Modal Setujui --}}
<div class="modal-overlay" id="modalApprove">
    <div class="modal-box">
        <div class="modal-title">✅ Setujui Pencairan</div>
        <div class="modal-sub" id="approveDesc"></div>
        <form method="POST" id="formApprove" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label>Bukti Transfer / Pembayaran <span style="color:var(--color-flame)">*</span></label>
                <input type="file" name="bukti_pembayaran" class="form-control" accept="image/*" required style="padding-top:10px;">
            </div>
            <div class="form-group">
                <label>Catatan (opsional)</label>
                <input type="text" name="catatan" class="form-control" placeholder="Catatan persetujuan...">
            </div>
            <div class="modal-actions">
                <button type="button" class="btn btn-cancel" onclick="closeModal('modalApprove')">Batal</button>
                <button type="submit" class="btn btn-approve">✅ Setujui &amp; Proses</button>
            </div>
        </form>
    </div>
</div>

{{-- Modal Tolak --}}
<div class="modal-overlay" id="modalReject">
    <div class="modal-box">
        <div class="modal-title">❌ Tolak Pencairan</div>
        <div class="modal-sub" id="rejectDesc"></div>
        <form method="POST" id="formReject">
            @csrf
            <div class="form-group">
                <label>Alasan Penolakan <span style="color:var(--color-flame)">*</span></label>
                <input type="text" name="catatan" class="form-control" placeholder="Jelaskan alasan penolakan..." required>
            </div>
            <div class="modal-actions">
                <button type="button" class="btn btn-cancel" onclick="closeModal('modalReject')">Batal</button>
                <button type="submit" class="btn btn-approve" style="background:var(--color-flame); color:white;">❌ Tolak Pengajuan</button>
            </div>
        </form>
    </div>
</div>

<script>
function switchTab(e, paneId) {
    document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
    document.querySelectorAll('.tab-pane').forEach(p => p.classList.remove('active'));
    e.target.classList.add('active');
    document.getElementById(paneId).classList.add('active');
}
function openApprove(id, nama, jumlah, bank, norek, atasnama) {
    document.getElementById('formApprove').action = `/admin/pencairan/${id}/approve`;
    let bankHtml = '';
    if (bank && bank !== 'null' && bank !== '') {
        bankHtml = `
        <div style="background:var(--color-mist); border: 1px dashed var(--color-sprout); padding: 16px; border-radius: 8px; margin: 15px 0; font-size:13px; text-align:left;">
            <div style="font-weight:800; color:var(--color-forest); margin-bottom:8px; text-transform:uppercase; font-size:11px; letter-spacing:0.05em;">📋 Rekening Tujuan Transfer:</div>
            <div style="display:flex; justify-content:space-between; margin-bottom:5px;">
                <span>Bank/E-Wallet:</span> <b>${bank}</b>
            </div>
            <div style="display:flex; justify-content:space-between; margin-bottom:5px; align-items:center;">
                <span>No. Rekening/HP:</span>
                <span>
                    <b id="copy_norek" style="font-family:'JetBrains Mono',monospace; font-size:14.5px; color:var(--color-forest); cursor:pointer;" title="Salin Nomor Rekening">${norek}</b>
                    <span style="font-size:10px; color:var(--color-fog); margin-left:5px; font-style:italic;">(Klik untuk salin)</span>
                </span>
            </div>
            <div style="display:flex; justify-content:space-between;">
                <span>Atas Nama:</span> <b>${atasnama}</b>
            </div>
        </div>
        `;
    } else {
        bankHtml = `
        <div style="background:rgba(255, 215, 0, 0.1); border: 1px dashed var(--color-sunburst); padding: 12px; border-radius: 8px; margin: 15px 0; font-size:13px; text-align:left; color:var(--color-sunburst); font-weight:700;">
            ℹ️ Pencairan dilakukan secara manual / tunai langsung kepada nasabah.
        </div>
        `;
    }
    document.getElementById('approveDesc').innerHTML =
        `Pencairan <b style="font-family:'JetBrains Mono',monospace;color:var(--color-flame);font-size:16px;">Rp ${jumlah.toLocaleString('id-ID')}</b> atas nama <b>${nama}</b> akan disetujui dan saldo nasabah akan dikurangi.<br>${bankHtml}`;
    document.getElementById('modalApprove').classList.add('show');
    
    // Copy event listener
    const copyEl = document.getElementById('copy_norek');
    if (copyEl) {
        copyEl.addEventListener('click', function() {
            navigator.clipboard.writeText(norek);
            alert('Nomor rekening berhasil disalin!');
        });
    }
}
function openReject(id, nama) {
    document.getElementById('formReject').action = `/admin/pencairan/${id}/reject`;
    document.getElementById('rejectDesc').innerHTML = `Pengajuan pencairan oleh <b>${nama}</b> akan ditolak. Saldo nasabah tidak berubah.`;
    document.getElementById('modalReject').classList.add('show');
}
function closeModal(id) { document.getElementById(id).classList.remove('show'); }
document.querySelectorAll('.modal-overlay').forEach(m => {
    m.addEventListener('click', e => { if (e.target === m) m.classList.remove('show'); });
});
</script>
</body>
</html>
