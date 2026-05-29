<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Setoran Pengepul – Bank Sampah Digital</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #1b5e20; --primary-light: #e8f5e9;
            --accent: #ffb300; --danger: #d32f2f; --danger-light: #ffebee;
            --info: #1565c0; --info-light: #e3f2fd;
            --dark: #212121; --gray-light: #f5f5f5; --gray-medium: #e0e0e0;
            --white: #ffffff; --shadow: 0 4px 20px rgba(0,0,0,0.07);
            --radius: 12px; --transition: all 0.3s ease;
        }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Outfit', sans-serif; background: #f4f6f5; color: var(--dark); min-height: 100vh; }
        .topbar { background: linear-gradient(135deg, var(--primary), #0c3610); color: #fff; padding: 16px 32px; display: flex; align-items: center; justify-content: space-between; box-shadow: 0 2px 10px rgba(0,0,0,0.15); }
        .topbar h1 { font-size: 20px; font-weight: 800; }
        .topbar a { color: #fff; text-decoration: none; font-size: 13px; background: rgba(255,255,255,0.15); padding: 6px 14px; border-radius: 20px; }
        .topbar a:hover { background: rgba(255,255,255,0.25); }
        .container { max-width: 1200px; margin: 0 auto; padding: 28px 24px; }
        .alert { padding: 14px 18px; border-radius: 10px; margin-bottom: 20px; font-weight: 600; font-size: 14px; }
        .alert-success { background: var(--primary-light); color: var(--primary); border: 1px solid #a5d6a7; }
        .alert-error { background: var(--danger-light); color: var(--danger); border: 1px solid #ef9a9a; }
        /* Stats */
        .stats-row { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px,1fr)); gap: 16px; margin-bottom: 28px; }
        .stat-card { background: var(--white); border-radius: var(--radius); padding: 20px; box-shadow: var(--shadow); }
        .stat-card .label { font-size: 12px; color: #888; font-weight: 600; margin-bottom: 8px; }
        .stat-card .value { font-size: 22px; font-weight: 800; }
        /* Tabs */
        .tabs { display: flex; gap: 8px; margin-bottom: 20px; }
        .tab-btn { padding: 10px 22px; border-radius: 8px; border: none; background: var(--gray-light); color: #555; font-weight: 700; font-size: 13px; cursor: pointer; transition: var(--transition); }
        .tab-btn.active { background: var(--primary); color: #fff; }
        .tab-pane { display: none; }
        .tab-pane.active { display: block; }
        /* Table */
        .card { background: var(--white); border-radius: var(--radius); box-shadow: var(--shadow); overflow: hidden; }
        .card-head { padding: 18px 22px; font-size: 15px; font-weight: 700; color: var(--primary); border-bottom: 2px solid var(--primary-light); display: flex; justify-content: space-between; align-items: center; }
        .table-wrap { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; font-size: 13.5px; }
        th { background: var(--gray-light); padding: 12px 14px; font-weight: 600; color: #666; border-bottom: 2px solid var(--gray-medium); text-align: left; }
        td { padding: 12px 14px; border-bottom: 1px solid var(--gray-light); vertical-align: middle; }
        tr:hover td { background: #fafafa; }
        .badge { display: inline-block; padding: 3px 10px; border-radius: 12px; font-size: 12px; font-weight: 700; }
        .badge-menunggu { background: #fff8e1; color: #e65100; }
        .badge-terverifikasi { background: var(--primary-light); color: var(--primary); }
        .badge-ditolak { background: var(--danger-light); color: var(--danger); }
        /* Action buttons */
        .btn { padding: 7px 16px; border-radius: 8px; border: none; font-weight: 700; font-size: 12px; cursor: pointer; transition: var(--transition); }
        .btn-verify { background: var(--primary); color: #fff; }
        .btn-verify:hover { background: #0c3610; }
        .btn-reject { background: var(--danger-light); color: var(--danger); }
        .btn-reject:hover { background: #ffcdd2; }
        .btn-detail { background: var(--info-light); color: var(--info); }
        .btn-detail:hover { background: #bbdefb; }
        /* Modal */
        .modal-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.45); z-index: 1000; justify-content: center; align-items: center; }
        .modal-overlay.show { display: flex; }
        .modal-box { background: #fff; border-radius: 16px; padding: 28px; width: 440px; max-width: 95vw; box-shadow: 0 20px 60px rgba(0,0,0,0.2); }
        .modal-title { font-size: 17px; font-weight: 800; margin-bottom: 16px; }
        .form-group { margin-bottom: 14px; }
        .form-group label { display: block; font-size: 13px; font-weight: 600; color: #555; margin-bottom: 6px; }
        .form-control { width: 100%; padding: 10px 14px; border: 1px solid var(--gray-medium); border-radius: 8px; font-size: 13px; font-family: inherit; outline: none; }
        .form-control:focus { border-color: var(--primary); }
        .modal-actions { display: flex; gap: 10px; justify-content: flex-end; margin-top: 18px; }
        .btn-cancel { background: var(--gray-light); color: #555; }
        .empty-state { text-align: center; padding: 40px; color: #aaa; }
        .empty-state .icon { font-size: 40px; margin-bottom: 12px; }
    </style>
</head>
<body>
<div class="topbar">
    <h1>💸 Kelola Setoran Pengepul</h1>
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
        <div class="stat-card" style="border-left: 4px solid #e65100;">
            <div class="label">⏳ Menunggu Verifikasi</div>
            <div class="value" style="color:#e65100;">{{ $setoranMenunggu->count() }}</div>
        </div>
        <div class="stat-card" style="border-left: 4px solid var(--primary);">
            <div class="label">✅ Terverifikasi</div>
            <div class="value" style="color:var(--primary);">{{ $setoranTerverifikasi->count() }}</div>
        </div>
        <div class="stat-card" style="border-left: 4px solid var(--danger);">
            <div class="label">❌ Ditolak</div>
            <div class="value" style="color:var(--danger);">{{ $setoranDitolak->count() }}</div>
        </div>
        <div class="stat-card" style="border-left: 4px solid var(--info); background: var(--info-light);">
            <div class="label">💰 Total Pendapatan Admin</div>
            <div class="value" style="color:var(--info); font-size:17px;">Rp {{ number_format($totalPendapatanAdmin, 0, ',', '.') }}</div>
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
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($setoranMenunggu as $s)
                        <tr>
                            <td><b>{{ $s->pengepul->nama ?? '-' }}</b></td>
                            <td>{{ $s->created_at->format('d/m/Y H:i') }}</td>
                            <td>Rp {{ number_format($s->total_nilai_nasabah, 0, ',', '.') }}</td>
                            <td style="color:var(--info);font-weight:700;">Rp {{ number_format($s->total_bagian_admin, 0, ',', '.') }}</td>
                            <td style="color:#e65100;">Rp {{ number_format($s->total_komisi_pengepul, 0, ',', '.') }}</td>
                            <td style="color:var(--info);font-weight:800;">Rp {{ number_format($s->total_disetor, 0, ',', '.') }}</td>
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
                                    <p>Tidak ada setoran yang menunggu verifikasi</p>
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
                            <td><b>{{ $s->pengepul->nama ?? '-' }}</b></td>
                            <td>{{ $s->created_at->format('d/m/Y H:i') }}</td>
                            <td>Rp {{ number_format($s->total_nilai_nasabah, 0, ',', '.') }}</td>
                            <td style="color:var(--info);font-weight:700;">Rp {{ number_format($s->total_bagian_admin, 0, ',', '.') }}</td>
                            <td style="color:#e65100;">Rp {{ number_format($s->total_komisi_pengepul, 0, ',', '.') }}</td>
                            <td style="font-weight:800;">Rp {{ number_format($s->total_disetor, 0, ',', '.') }}</td>
                            <td style="color:#666;font-size:12px;">{{ $s->catatan ?? '-' }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="7"><div class="empty-state"><p>Belum ada setoran terverifikasi</p></div></td></tr>
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
                            <td><b>{{ $s->pengepul->nama ?? '-' }}</b></td>
                            <td>{{ $s->created_at->format('d/m/Y H:i') }}</td>
                            <td>Rp {{ number_format($s->total_disetor, 0, ',', '.') }}</td>
                            <td style="color:var(--danger);font-size:12px;">{{ $s->catatan ?? '-' }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="4"><div class="empty-state"><p>Tidak ada setoran yang ditolak</p></div></td></tr>
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
        <p style="font-size:13px;color:#555;margin-bottom:16px;">Setoran ini akan ditandai terverifikasi. Dana akan dianggap diterima oleh admin.</p>
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
        <p style="font-size:13px;color:#555;margin-bottom:16px;">Setoran akan ditolak dan transaksi terkait dapat disetor ulang oleh pengepul.</p>
        <form method="POST" id="formReject">
            @csrf
            <div class="form-group">
                <label>Alasan Penolakan <span style="color:red;">*</span></label>
                <input type="text" name="catatan" class="form-control" placeholder="Jelaskan alasan penolakan..." required>
            </div>
            <div class="modal-actions">
                <button type="button" class="btn btn-cancel" onclick="closeModal('modalReject')">Batal</button>
                <button type="submit" class="btn btn-reject">❌ Tolak Setoran</button>
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
