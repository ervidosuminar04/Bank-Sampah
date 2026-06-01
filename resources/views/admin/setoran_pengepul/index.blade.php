<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Setoran Pengepul – Realive</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Nunito:wght@700;800;900&family=Nunito+Sans:wght@400;600;700&family=JetBrains+Mono:wght@400&display=swap');

        :root {
            /* Warm Spectrum */
            --color-solar:      #FFD700;
            --color-sunburst:   #FFA500;
            --color-ember:      #F5511E;
            --color-flame:      #E63946;

            /* Green Spectrum */
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

            /* Semantic */
            --bg-page:          var(--color-mist);
            --bg-surface:       var(--color-white);
            --text-primary:     var(--color-canopy);
            --text-secondary:   var(--color-forest);
            --text-muted:       var(--color-fog);
            --accent-cta:       var(--color-solar);
            --accent-alert:     var(--color-flame);
            --border-default:   var(--color-smoke);
            --border-focus:     var(--color-sprout);

            /* Shadows */
            --shadow-sm:    0 1px 4px rgba(26, 58, 26, 0.08);
            --shadow-md:    0 4px 16px rgba(26, 58, 26, 0.12);
            --shadow-lg:    0 8px 32px rgba(26, 58, 26, 0.18);
            --shadow-xl:    0 16px 56px rgba(26, 58, 26, 0.24);
            --shadow-glow:  0 0 24px rgba(255, 215, 0, 0.35);
            --shadow-focus: 0 0 0 3px rgba(125, 184, 37, 0.45);

            /* Radius */
            --radius-sm:   8px;
            --radius-md:   16px;
            --radius-lg:   24px;
            --radius-full: 9999px;

            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            --ease-spring: cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Nunito Sans', sans-serif; background: var(--bg-page); color: var(--text-primary); min-height: 100vh; }

        /* ── TOPBAR ── */
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

        /* ── ALERTS ── */
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
            animation: slideDown 0.4s ease-out;
        }
        @keyframes slideDown {
            from { transform: translateY(-10px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        .alert-success { background-color: var(--color-mist); border-left-color: var(--color-sprout); color: var(--color-forest); }
        .alert-error { background-color: rgba(230, 57, 70, 0.1); border-left-color: var(--color-flame); color: var(--color-flame); }

        /* ── STATS ── */
        .stats-row { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; margin-bottom: 28px; }
        .stat-card {
            background: var(--bg-surface);
            border-radius: var(--radius-md);
            padding: 22px;
            box-shadow: var(--shadow-md);
            border: 1px solid rgba(45, 106, 45, 0.05);
            border-bottom: 4px solid;
            transition: var(--transition);
        }
        .stat-card:hover { transform: translateY(-4px); box-shadow: var(--shadow-lg); }
        .stat-card .label { font-size: 11.5px; color: var(--color-forest); font-weight: 700; text-transform: uppercase; margin-bottom: 8px; letter-spacing: 0.05em; }
        .stat-card .value { font-family: 'JetBrains Mono', monospace; font-size: 24px; font-weight: 800; }

        /* ── TABS ── */
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

        /* ── CARD / TABLE ── */
        .card {
            background: var(--bg-surface);
            border-radius: 24px 24px 16px 16px;
            box-shadow: var(--shadow-md);
            border: 1px solid rgba(45, 106, 45, 0.05);
            overflow: hidden;
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
        .table-wrap { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; font-size: 14px; }
        th {
            background: var(--color-mist);
            padding: 14px 16px;
            font-weight: 700;
            color: var(--color-canopy);
            border-bottom: 2px solid var(--color-smoke);
            text-align: left;
            text-transform: uppercase;
            font-size: 11.5px;
            letter-spacing: 0.05em;
        }
        td { padding: 14px 16px; border-bottom: 1px solid var(--color-mist); vertical-align: middle; }
        tr:hover td { background: rgba(244, 247, 240, 0.5); }

        td.mono-col { font-family: 'JetBrains Mono', monospace; font-weight: 600; }

        /* ── BADGES ── */
        .badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: var(--radius-full);
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
        }
        .badge-menunggu { background: rgba(255, 215, 0, 0.15); color: var(--color-sunburst); }
        .badge-terverifikasi { background: var(--color-mist); color: var(--color-forest); border: 1px solid rgba(45,106,45,0.15); }
        .badge-ditolak { background: rgba(230, 57, 70, 0.15); color: var(--color-flame); }

        /* ── BUTTONS ── */
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
        .btn-verify { background: var(--accent-cta); color: var(--color-canopy); }
        .btn-verify:hover { background: var(--color-sunburst); transform: scale(1.03); box-shadow: var(--shadow-glow); }

        .btn-reject { background: transparent; border: 2px solid var(--color-flame); color: var(--color-flame); }
        .btn-reject:hover { background: var(--color-flame); color: var(--color-white); }

        .btn-detail { background: var(--color-mist); color: var(--color-forest); border: 1px solid rgba(45,106,45,0.15); }
        .btn-detail:hover { background: rgba(125, 184, 37, 0.15); border-color: var(--color-sprout); }

        /* ── MODALS ── */
        .modal-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(10, 10, 10, 0.45);
            backdrop-filter: blur(6px);
            z-index: 1000;
            justify-content: center;
            align-items: center;
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
        .modal-title { font-family: 'Nunito', sans-serif; font-size: 18px; font-weight: 800; margin-bottom: 16px; color: var(--color-canopy); }
        .form-group { margin-bottom: 16px; }
        .form-group label { display: block; font-size: 13px; font-weight: 700; color: var(--color-canopy); margin-bottom: 6px; }
        .form-control {
            width: 100%;
            height: 46px;
            padding: 0 14px;
            border: 2px solid var(--border-default);
            border-radius: var(--radius-sm);
            font-size: 14px;
            font-family: inherit;
            outline: none;
            background: #fff;
        }
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
        <img src="{{ asset('images/logo Realive@3x.png') }}" alt="Realive Logo">
        <h1>Kelola Setoran Pengepul</h1>
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
            <div class="label">⏳ Menunggu Verifikasi</div>
            <div class="value" style="color:var(--color-sunburst);">{{ $setoranMenunggu->count() }}</div>
        </div>
        <div class="stat-card" style="border-bottom-color: var(--color-sprout);">
            <div class="label">✅ Terverifikasi</div>
            <div class="value" style="color:var(--color-forest);">{{ $setoranTerverifikasi->count() }}</div>
        </div>
        <div class="stat-card" style="border-bottom-color: var(--color-flame);">
            <div class="label">❌ Ditolak</div>
            <div class="value" style="color:var(--color-flame);">{{ $setoranDitolak->count() }}</div>
        </div>
        <div class="stat-card" style="border-bottom-color: var(--color-forest); background: var(--color-mist);">
            <div class="label">💰 Total Pendapatan Admin</div>
            <div class="value" style="color:var(--color-forest); font-size:18px;">Rp {{ number_format($totalPendapatanAdmin, 0, ',', '.') }}</div>
        </div>
    </div>

    {{-- Tabs --}}
    <div class="tabs">
        <button class="tab-btn active" onclick="switchTab(event,'pane-menunggu')">⏳ Menunggu ({{ $setoranMenunggu->count() }})</button>
        <button class="tab-btn" onclick="switchTab(event,'pane-terverifikasi')">✅ Terverifikasi ({{ $setoranTerverifikasi->count() }})</button>
        <button class="tab-btn" onclick="switchTab(event,'pane-ditolak')">❌ Ditolak ({{ $setoranDitolak->count() }})</button>
    </div>

    {{-- Pane: Menunggu --}}
    <div class="tab-pane active" id="pane-menunggu">
        <div class="card">
            <div class="card-head">Setoran Menunggu Verifikasi</div>
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Pengepul</th>
                            <th>Tgl Setoran</th>
                            <th>Nilai Nasabah</th>
                            <th>Bagian Admin</th>
                            <th>Komisi Pengepul</th>
                            <th>Total Disetor</th>
                            <th>Jumlah Trx</th>
                            <th style="width: 280px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($setoranMenunggu as $s)
                        <tr>
                            <td><strong>{{ $s->pengepul->nama ?? '-' }}</strong></td>
                            <td class="mono-col">{{ $s->created_at->format('d/m/Y H:i') }}</td>
                            <td class="mono-col">Rp {{ number_format($s->total_nilai_nasabah, 0, ',', '.') }}</td>
                            <td class="mono-col" style="color:var(--color-forest);font-weight:700;">Rp {{ number_format($s->total_bagian_admin, 0, ',', '.') }}</td>
                            <td class="mono-col" style="color:var(--color-sunburst);">Rp {{ number_format($s->total_komisi_pengepul, 0, ',', '.') }}</td>
                            <td class="mono-col" style="color:var(--color-forest);font-weight:800;">Rp {{ number_format($s->total_disetor, 0, ',', '.') }}</td>
                            <td>{{ count($s->transaksi_ids) }} transaksi</td>
                            <td>
                                <a href="{{ route('admin.setoran.show', $s->id) }}" class="btn btn-detail">🔍 Detail</a>
                                <button class="btn btn-verify" onclick="openVerify({{ $s->id }})">✅ Verifikasi</button>
                                <button class="btn btn-reject" onclick="openReject({{ $s->id }})">❌ Tolak</button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8">
                                <div class="empty-state">
                                    <div class="icon">🎉</div>
                                    <p style="font-weight:700;">Tidak ada setoran yang menunggu verifikasi</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Pane: Terverifikasi --}}
    <div class="tab-pane" id="pane-terverifikasi">
        <div class="card">
            <div class="card-head">Setoran Terverifikasi</div>
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr><th>Pengepul</th><th>Tgl Setoran</th><th>Nilai Nasabah</th><th>Bagian Admin</th><th>Komisi Pengepul</th><th>Total Disetor</th><th>Catatan</th></tr>
                    </thead>
                    <tbody>
                        @forelse($setoranTerverifikasi as $s)
                        <tr>
                            <td><strong>{{ $s->pengepul->nama ?? '-' }}</strong></td>
                            <td class="mono-col">{{ $s->created_at->format('d/m/Y H:i') }}</td>
                            <td class="mono-col">Rp {{ number_format($s->total_nilai_nasabah, 0, ',', '.') }}</td>
                            <td class="mono-col" style="color:var(--color-forest);font-weight:700;">Rp {{ number_format($s->total_bagian_admin, 0, ',', '.') }}</td>
                            <td class="mono-col" style="color:var(--color-sunburst);">Rp {{ number_format($s->total_komisi_pengepul, 0, ',', '.') }}</td>
                            <td class="mono-col" style="font-weight:800;">Rp {{ number_format($s->total_disetor, 0, ',', '.') }}</td>
                            <td style="color:var(--color-forest);font-size:13px;">{{ $s->catatan ?? '-' }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="7"><div class="empty-state"><p style="font-weight:700;">Belum ada setoran terverifikasi</p></div></td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Pane: Ditolak --}}
    <div class="tab-pane" id="pane-ditolak">
        <div class="card">
            <div class="card-head">Setoran Ditolak</div>
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr><th>Pengepul</th><th>Tgl Setoran</th><th>Total Disetor</th><th>Alasan Ditolak</th></tr>
                    </thead>
                    <tbody>
                        @forelse($setoranDitolak as $s)
                        <tr>
                            <td><strong>{{ $s->pengepul->nama ?? '-' }}</strong></td>
                            <td class="mono-col">{{ $s->created_at->format('d/m/Y H:i') }}</td>
                            <td class="mono-col">Rp {{ number_format($s->total_disetor, 0, ',', '.') }}</td>
                            <td style="color:var(--color-flame);font-size:13px;font-weight:600;">{{ $s->catatan ?? '-' }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="4"><div class="empty-state"><p style="font-weight:700;">Tidak ada setoran yang ditolak</p></div></td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

{{-- Modal Verifikasi --}}
<div class="modal-overlay" id="modalVerify">
    <div class="modal-box">
        <div class="modal-title">✅ Konfirmasi Verifikasi Setoran</div>
        <p style="font-size:13.5px;color:var(--color-forest);margin-bottom:16px;line-height:1.5;">Setoran ini akan ditandai terverifikasi. Dana akan dianggap diterima oleh admin.</p>
        <form method="POST" id="formVerify">
            @csrf
            <div class="form-group">
                <label>Catatan (opsional)</label>
                <input type="text" name="catatan" class="form-control" placeholder="Catatan verifikasi...">
            </div>
            <div class="modal-actions">
                <button type="button" class="btn btn-cancel" onclick="closeModal('modalVerify')">Batal</button>
                <button type="submit" class="btn btn-verify">✅ Verifikasi Sekarang</button>
            </div>
        </form>
    </div>
</div>

{{-- Modal Tolak --}}
<div class="modal-overlay" id="modalReject">
    <div class="modal-box">
        <div class="modal-title">❌ Tolak Setoran</div>
        <p style="font-size:13.5px;color:var(--color-forest);margin-bottom:16px;line-height:1.5;">Setoran akan ditolak dan transaksi terkait dapat disetor ulang oleh pengepul.</p>
        <form method="POST" id="formReject">
            @csrf
            <div class="form-group">
                <label>Alasan Penolakan <span style="color:var(--color-flame);">*</span></label>
                <input type="text" name="catatan" class="form-control" placeholder="Jelaskan alasan penolakan..." required>
            </div>
            <div class="modal-actions">
                <button type="button" class="btn btn-cancel" onclick="closeModal('modalReject')">Batal</button>
                <button type="submit" class="btn btn-reject" style="background:var(--color-flame);color:#fff;border:none;">❌ Tolak Setoran</button>
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
function openVerify(id) {
    document.getElementById('formVerify').action = `/admin/setoran-pengepul/${id}/verify`;
    document.getElementById('modalVerify').classList.add('show');
}
function openReject(id) {
    document.getElementById('formReject').action = `/admin/setoran-pengepul/${id}/reject`;
    document.getElementById('modalReject').classList.add('show');
}
function closeModal(id) {
    document.getElementById(id).classList.remove('show');
}
document.querySelectorAll('.modal-overlay').forEach(m => {
    m.addEventListener('click', function(e) { if (e.target === this) this.classList.remove('show'); });
});
</script>
</body>
</html>
