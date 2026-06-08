<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Setoran #{{ $setoran->id }} – Realive</title>
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
        .topbar h1 { font-family: 'Nunito', sans-serif; font-size: 19px; font-weight: 800; }
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

        .container { max-width: 1100px; margin: 0 auto; padding: 32px 24px; }

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
        }
        .alert-success { background-color: var(--color-mist); border-left-color: var(--color-sprout); color: var(--color-forest); }
        .alert-error { background-color: rgba(230, 57, 70, 0.1); border-left-color: var(--color-flame); color: var(--color-flame); }

        /* ── INFO GRID ── */
        .info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 28px; }
        .info-box {
            background: var(--bg-surface);
            border-radius: 24px 24px 16px 16px;
            padding: 24px;
            box-shadow: var(--shadow-md);
            border: 1px solid rgba(45, 106, 45, 0.05);
        }
        .info-box h3 {
            font-family: 'Nunito', sans-serif;
            font-size: 15px;
            font-weight: 800;
            color: var(--color-canopy);
            margin-bottom: 16px;
            padding-bottom: 10px;
            border-bottom: 2px solid var(--color-mist);
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            font-size: 14px;
            border-bottom: 1px dashed rgba(138, 158, 138, 0.25);
        }
        .info-row:last-child { border-bottom: none; }
        .info-row .lbl { color: var(--color-forest); font-weight: 600; }
        .info-row .val { font-weight: 700; color: var(--text-primary); text-align: right; }

        /* ── SUMMARY ── */
        .summary-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 16px; margin-bottom: 28px; }
        .sum-card {
            background: var(--bg-surface);
            border-radius: var(--radius-md);
            padding: 20px;
            box-shadow: var(--shadow-md);
            border: 1px solid rgba(45, 106, 45, 0.05);
            border-bottom: 4px solid var(--color-sprout);
        }
        .sum-card .lbl { font-size: 11.5px; color: var(--color-forest); font-weight: 700; text-transform: uppercase; margin-bottom: 6px; letter-spacing: 0.05em; }
        .sum-card .val { font-family: 'JetBrains Mono', monospace; font-size: 22px; font-weight: 800; }

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

        /* ── ACTION BUTTONS ── */
        .actions { display: flex; gap: 12px; margin-bottom: 28px; }
        .btn {
            padding: 10px 24px;
            border-radius: var(--radius-full);
            border: none;
            font-weight: 700;
            font-size: 14px;
            cursor: pointer;
            transition: var(--transition);
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        .btn-verify { background: var(--accent-cta); color: var(--color-canopy); box-shadow: var(--shadow-sm); }
        .btn-verify:hover { background: var(--color-sunburst); transform: scale(1.03); box-shadow: var(--shadow-glow); }

        .btn-reject { background: transparent; border: 2px solid var(--color-flame); color: var(--color-flame); }
        .btn-reject:hover { background: var(--color-flame); color: var(--color-white); }

        .btn-back { background: var(--color-mist); color: var(--color-forest); text-decoration: none; border: 1px solid rgba(45,106,45,0.15); }
        .btn-back:hover { background: var(--color-smoke); }

        /* ── CARD & TABLE ── */
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

        @media(max-width:640px) { .info-grid { grid-template-columns: 1fr; } }
    </style>
</head>
<body>
<div class="topbar">
    <div class="topbar-brand">
        <h1>Detail Setoran #{{ $setoran->id }}</h1>
    </div>
    <a href="{{ route('admin.setoran.index') }}">← Kembali ke Daftar</a>
</div>

<div class="container">

    @if(session('success'))
        <div class="alert alert-success">✅ {{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-error">⚠️ {{ session('error') }}</div>
    @endif

    {{-- Info Setoran & Pengepul --}}
    <div class="info-grid">
        <div class="info-box">
            <h3>👤 Informasi Pengepul</h3>
            <div class="info-row"><span class="lbl">Nama</span><span class="val">{{ $setoran->pengepul->nama ?? '-' }}</span></div>
            <div class="info-row"><span class="lbl">Alamat</span><span class="val">{{ $setoran->pengepul->alamat ?? '-' }}</span></div>
            <div class="info-row"><span class="lbl">Telepon</span><span class="val">{{ $setoran->pengepul->telepon ?? '-' }}</span></div>
        </div>
        <div class="info-box">
            <h3>📋 Informasi Setoran</h3>
            <div class="info-row"><span class="lbl">Tanggal Setoran</span><span class="val" style="font-family:'JetBrains Mono',monospace;">{{ $setoran->created_at->format('d/m/Y H:i') }}</span></div>
            <div class="info-row"><span class="lbl">Jumlah Transaksi</span><span class="val">{{ count($setoran->transaksi_ids) }} transaksi</span></div>
            <div class="info-row"><span class="lbl">Status</span>
                <span class="badge badge-{{ $setoran->setoran_pengepul_status }}">
                    @if($setoran->setoran_pengepul_status==='terverifikasi') ✅ Terverifikasi
                    @elseif($setoran->setoran_pengepul_status==='menunggu') ⏳ Menunggu
                    @else ❌ Ditolak @endif
                </span>
            </div>
            @if($setoran->catatan)
            <div class="info-row"><span class="lbl">Catatan Admin</span><span class="val" style="color:var(--color-forest);max-width:220px;text-align:right;">{{ $setoran->catatan }}</span></div>
            @endif
        </div>
    </div>

    {{-- Bukti Pembayaran / Transfer Pengepul --}}
    @if($setoran->setoran_pengepul_gambar)
    <div class="card" style="margin-bottom: 28px;">
        <div class="card-head">📸 Bukti Transfer / Pembayaran Pengepul</div>
        <div style="padding: 20px; text-align: center;">
            <a href="{{ asset('storage/' . $setoran->setoran_pengepul_gambar) }}" target="_blank">
                <img src="{{ asset('storage/' . $setoran->setoran_pengepul_gambar) }}" alt="Bukti Transfer Pengepul" style="max-width: 100%; max-height: 400px; border-radius: var(--radius-md); box-shadow: var(--shadow-sm); border: 1px solid var(--border-default);">
            </a>
            <p style="margin-top: 10px; font-size: 13px; color: var(--color-fog);">Klik gambar untuk memperbesar</p>
        </div>
    </div>
    @endif

    {{-- Ringkasan Finansial --}}
    <div class="summary-grid">
        <div class="sum-card">
            <div class="lbl">💚 Nilai ke Nasabah</div>
            <div class="val" style="color:var(--color-forest);">Rp {{ number_format($setoran->total_nilai_nasabah,0,',','.') }}</div>
        </div>
        <div class="sum-card">
            <div class="lbl">📊 Total Selisih</div>
            <div class="val">Rp {{ number_format($setoran->total_selisih,0,',','.') }}</div>
        </div>
        <div class="sum-card" style="border-bottom-color: var(--color-sunburst);">
            <div class="lbl">🤑 Komisi Pengepul (50%)</div>
            <div class="val" style="color:var(--color-sunburst);">Rp {{ number_format($setoran->total_komisi_pengepul,0,',','.') }}</div>
        </div>
        <div class="sum-card" style="border-bottom-color: var(--color-forest);">
            <div class="lbl">🏛️ Bagian Admin (50%)</div>
            <div class="val" style="color:var(--color-forest);">Rp {{ number_format($setoran->total_bagian_admin,0,',','.') }}</div>
        </div>
        <div class="sum-card" style="border:2px solid var(--color-forest);background:var(--color-mist);border-bottom: 4px solid var(--color-forest);">
            <div class="lbl">💸 Total Disetor ke Admin</div>
            <div class="val" style="color:var(--color-forest);">Rp {{ number_format($setoran->total_disetor,0,',','.') }}</div>
            <div style="font-size:11px;color:var(--color-fog);margin-top:4px;">(nilai nasabah + bagian admin)</div>
        </div>
    </div>

    {{-- Tombol Aksi (hanya jika menunggu) --}}
    @if($setoran->setoran_pengepul_status === 'menunggu')
    <div class="actions">
        <button class="btn btn-verify" onclick="document.getElementById('modalVerify').classList.add('show')">✅ Verifikasi Setoran</button>
        <button class="btn btn-reject" onclick="document.getElementById('modalReject').classList.add('show')">❌ Tolak Setoran</button>
    </div>
    @endif

    {{-- Tabel Transaksi --}}
    <div class="card">
        <div class="card-head">⚖️ Daftar Transaksi dalam Setoran Ini</div>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>No</th><th>Tanggal</th><th>Nasabah</th><th>Jenis Sampah</th>
                        <th>Berat (kg)</th><th>Harga Beli/kg</th><th>Harga Pasar/kg</th>
                        <th>Nilai Nasabah</th><th>Komisi</th><th>Bagian Admin</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transaksi as $i => $t)
                    <tr>
                        <td>{{ $i+1 }}</td>
                        <td class="mono-col">{{ \Carbon\Carbon::parse($t->transaksi_pengepul_tanggal)->format('d/m/Y') }}</td>
                        <td><strong>{{ $t->nasabah->nasabah_nama ?? '-' }}</strong></td>
                        <td>{{ $t->sampah->sampah_nama ?? '-' }}</td>
                        <td class="mono-col">{{ number_format($t->berat_kg,2) }}</td>
                        <td class="mono-col">Rp {{ number_format($t->harga_beli_kg,0,',','.') }}</td>
                        <td class="mono-col">Rp {{ number_format($t->harga_pasar_kg,0,',','.') }}</td>
                        <td class="mono-col">Rp {{ number_format($t->nilai_idr,0,',','.') }}</td>
                        <td class="mono-col" style="color:var(--color-sunburst);font-weight:700;">Rp {{ number_format($t->transaksi_pengepul_komisi_pengepul,0,',','.') }}</td>
                        <td class="mono-col" style="color:var(--color-forest);font-weight:700;">Rp {{ number_format($t->bagian_admin,0,',','.') }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="10" style="text-align:center;color:var(--color-fog);padding:24px;font-weight:700;">Tidak ada transaksi ditemukan</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

{{-- Modal Verifikasi --}}
<div class="modal-overlay" id="modalVerify">
    <div class="modal-box">
        <div class="modal-title">✅ Verifikasi Setoran #{{ $setoran->id }}</div>
        <p style="font-size:13.5px;color:var(--color-forest);margin-bottom:16px;line-height:1.5;">Setoran sebesar <b style="font-family:'JetBrains Mono',monospace;color:var(--color-canopy);">Rp {{ number_format($setoran->total_disetor,0,',','.') }}</b> akan ditandai terverifikasi.</p>
        <form method="POST" action="{{ route('admin.setoran.verify', $setoran->id) }}">
            @csrf
            <div class="form-group">
                <label>Catatan (opsional)</label>
                <input type="text" name="catatan" class="form-control" placeholder="Catatan verifikasi...">
            </div>
            <div class="modal-actions">
                <button type="button" class="btn btn-cancel" onclick="document.getElementById('modalVerify').classList.remove('show')">Batal</button>
                <button type="submit" class="btn btn-verify">✅ Verifikasi</button>
            </div>
        </form>
    </div>
</div>

{{-- Modal Tolak --}}
<div class="modal-overlay" id="modalReject">
    <div class="modal-box">
        <div class="modal-title">❌ Tolak Setoran #{{ $setoran->id }}</div>
        <p style="font-size:13.5px;color:var(--color-forest);margin-bottom:16px;line-height:1.5;">Transaksi terkait akan dapat disetor ulang oleh pengepul.</p>
        <form method="POST" action="{{ route('admin.setoran.reject', $setoran->id) }}">
            @csrf
            <div class="form-group">
                <label>Alasan Penolakan <span style="color:var(--color-flame)">*</span></label>
                <input type="text" name="catatan" class="form-control" placeholder="Jelaskan alasan..." required>
            </div>
            <div class="modal-actions">
                <button type="button" class="btn btn-cancel" onclick="document.getElementById('modalReject').classList.remove('show')">Batal</button>
                <button type="submit" class="btn btn-reject" style="background:var(--color-flame);color:#fff;border:none;">❌ Tolak</button>
            </div>
        </form>
    </div>
</div>

<script>
document.querySelectorAll('.modal-overlay').forEach(m => {
    m.addEventListener('click', e => { if (e.target === m) m.classList.remove('show'); });
});
</script>
</body>
</html>
