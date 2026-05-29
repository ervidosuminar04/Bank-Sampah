<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Pencairan Saldo Nasabah – Bank Sampah Digital</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root{--primary:#1b5e20;--primary-light:#e8f5e9;--danger:#d32f2f;--danger-light:#ffebee;--info:#1565c0;--info-light:#e3f2fd;--accent:#ffb300;--white:#ffffff;--gray-light:#f5f5f5;--gray-medium:#e0e0e0;--shadow:0 4px 20px rgba(0,0,0,0.07);--radius:12px;--transition:all 0.3s ease}
        *{box-sizing:border-box;margin:0;padding:0}
        body{font-family:'Outfit',sans-serif;background:#f4f6f5;color:#212121;min-height:100vh}
        .topbar{background:linear-gradient(135deg,var(--primary),#0c3610);color:#fff;padding:16px 32px;display:flex;align-items:center;justify-content:space-between;box-shadow:0 2px 10px rgba(0,0,0,0.15)}
        .topbar h1{font-size:20px;font-weight:800}
        .topbar a{color:#fff;text-decoration:none;font-size:13px;background:rgba(255,255,255,0.15);padding:6px 14px;border-radius:20px}
        .topbar a:hover{background:rgba(255,255,255,0.25)}
        .container{max-width:1200px;margin:0 auto;padding:28px 24px}
        .alert{padding:14px 18px;border-radius:10px;margin-bottom:20px;font-weight:600;font-size:14px}
        .alert-success{background:var(--primary-light);color:var(--primary);border:1px solid #a5d6a7}
        .alert-error{background:var(--danger-light);color:var(--danger);border:1px solid #ef9a9a}
        .stats-row{display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:16px;margin-bottom:28px}
        .stat-card{background:var(--white);border-radius:var(--radius);padding:20px;box-shadow:var(--shadow)}
        .stat-card .lbl{font-size:12px;color:#888;font-weight:600;margin-bottom:8px}
        .stat-card .val{font-size:22px;font-weight:800}
        .tabs{display:flex;gap:8px;margin-bottom:20px;flex-wrap:wrap}
        .tab-btn{padding:10px 22px;border-radius:8px;border:none;background:var(--gray-light);color:#555;font-weight:700;font-size:13px;cursor:pointer;transition:var(--transition)}
        .tab-btn.active{background:var(--primary);color:#fff}
        .tab-pane{display:none}
        .tab-pane.active{display:block}
        .card{background:var(--white);border-radius:var(--radius);box-shadow:var(--shadow);overflow:hidden}
        .card-head{padding:18px 22px;font-size:15px;font-weight:700;color:var(--primary);border-bottom:2px solid var(--primary-light)}
        .table-wrap{overflow-x:auto}
        table{width:100%;border-collapse:collapse;font-size:13.5px}
        th{background:var(--gray-light);padding:12px 14px;font-weight:600;color:#666;border-bottom:2px solid var(--gray-medium);text-align:left}
        td{padding:12px 14px;border-bottom:1px solid var(--gray-light);vertical-align:middle}
        tr:hover td{background:#fafafa}
        .badge{display:inline-block;padding:3px 10px;border-radius:12px;font-size:12px;font-weight:700}
        .badge-menunggu{background:#fff8e1;color:#e65100}
        .badge-disetujui{background:var(--primary-light);color:var(--primary)}
        .badge-ditolak{background:var(--danger-light);color:var(--danger)}
        .btn{padding:7px 16px;border-radius:8px;border:none;font-weight:700;font-size:12px;cursor:pointer;transition:var(--transition)}
        .btn-approve{background:var(--primary);color:#fff}
        .btn-approve:hover{background:#0c3610}
        .btn-reject{background:var(--danger-light);color:var(--danger)}
        .btn-reject:hover{background:#ffcdd2}
        .modal-overlay{display:none;position:fixed;inset:0;background:rgba(0,0,0,0.45);z-index:1000;justify-content:center;align-items:center}
        .modal-overlay.show{display:flex}
        .modal-box{background:#fff;border-radius:16px;padding:28px;width:440px;max-width:95vw;box-shadow:0 20px 60px rgba(0,0,0,0.2)}
        .modal-title{font-size:17px;font-weight:800;margin-bottom:8px}
        .modal-sub{font-size:13px;color:#555;margin-bottom:18px;line-height:1.5}
        .form-group{margin-bottom:14px}
        .form-group label{display:block;font-size:13px;font-weight:600;color:#555;margin-bottom:6px}
        .form-control{width:100%;padding:10px 14px;border:1px solid var(--gray-medium);border-radius:8px;font-size:13px;font-family:inherit;outline:none}
        .form-control:focus{border-color:var(--primary)}
        .modal-actions{display:flex;gap:10px;justify-content:flex-end;margin-top:18px}
        .btn-cancel{background:var(--gray-light);color:#555}
        .empty-state{text-align:center;padding:40px;color:#aaa}
        .empty-state .icon{font-size:40px;margin-bottom:12px}
    </style>
</head>
<body>

<div class="topbar">
    <h1>💳 Kelola Pencairan Saldo Nasabah</h1>
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
        <div class="stat-card" style="border-left:4px solid #e65100;">
            <div class="lbl">⏳ Menunggu Persetujuan</div>
            <div class="val" style="color:#e65100;">{{ $menunggu->count() }}</div>
        </div>
        <div class="stat-card" style="border-left:4px solid var(--primary);">
            <div class="lbl">✅ Disetujui</div>
            <div class="val" style="color:var(--primary);">{{ $disetujui->count() }}</div>
        </div>
        <div class="stat-card" style="border-left:4px solid var(--danger);">
            <div class="lbl">❌ Ditolak</div>
            <div class="val" style="color:var(--danger);">{{ $ditolak->count() }}</div>
        </div>
        <div class="stat-card" style="border-left:4px solid var(--info);background:var(--info-light);">
            <div class="lbl">💰 Total Dana Dicairkan</div>
            <div class="val" style="color:var(--info);font-size:17px;">Rp {{ number_format($totalDicairkan,0,',','.') }}</div>
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
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($menunggu as $t)
                        <tr>
                            <td>
                                <b>{{ $t->nasabah->nasabah_nama ?? '-' }}</b><br>
                                <span style="font-size:11px;color:#888;">{{ $t->nasabah->nasabah_username ?? '-' }}</span>
                            </td>
                            <td>{{ \Carbon\Carbon::parse($t->tarik_tanggal)->format('d/m/Y') }}</td>
                            <td style="font-weight:800;color:var(--danger);font-size:15px;">Rp {{ number_format($t->tarik_jumlah,0,',','.') }}</td>
                            <td>
                                @if($t->tarik_bank_tujuan)
                                    <div style="font-weight: 700; color: #1b5e20;">
                                        {{ $t->tarik_bank_tujuan }} - {{ $t->tarik_nomor_rekening }}
                                    </div>
                                    <div style="font-size: 11px; color: #555; margin-top: 2px;">
                                        a/n {{ $t->tarik_atas_nama }}
                                    </div>
                                @else
                                    <span style="color:#aaa; font-style:italic;">Manual / Tunai</span>
                                @endif
                            </td>
                            <td>Rp {{ number_format($t->tarik_sisa_saldo,0,',','.') }}</td>
                            <td>Rp {{ number_format($t->nasabah->nasabah_saldo ?? 0,0,',','.') }}</td>
                            <td>
                                <button class="btn btn-approve" onclick="openApprove({{ $t->id_tarik }}, '{{ $t->nasabah->nasabah_nama ?? '' }}', {{ $t->tarik_jumlah }}, '{{ $t->tarik_bank_tujuan }}', '{{ $t->tarik_nomor_rekening }}', '{{ $t->tarik_atas_nama }}')">✅ Setujui</button>
                                <button class="btn btn-reject" onclick="openReject({{ $t->id_tarik }}, '{{ $t->nasabah->nasabah_nama ?? '' }}')">❌ Tolak</button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7">
                                <div class="empty-state">
                                    <div class="icon">🎉</div>
                                    <p>Tidak ada pengajuan pencairan yang menunggu</p>
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
                            <td><b>{{ $t->nasabah->nasabah_nama ?? '-' }}</b></td>
                            <td>{{ \Carbon\Carbon::parse($t->tarik_tanggal)->format('d/m/Y') }}</td>
                            <td style="font-weight:700;color:var(--primary);">Rp {{ number_format($t->tarik_jumlah,0,',','.') }}</td>
                            <td>
                                @if($t->tarik_bank_tujuan)
                                    <div style="font-weight: 600; color: #333;">
                                        {{ $t->tarik_bank_tujuan }} - {{ $t->tarik_nomor_rekening }}
                                    </div>
                                    <div style="font-size: 11px; color: #666;">
                                        a/n {{ $t->tarik_atas_nama }}
                                    </div>
                                @else
                                    <span style="color:#aaa; font-style:italic;">Manual / Tunai</span>
                                @endif
                            </td>
                            <td>Rp {{ number_format($t->tarik_sisa_saldo,0,',','.') }}</td>
                            <td style="color:#666;font-size:12px;">{{ $t->catatan ?? '-' }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="6"><div class="empty-state"><p>Belum ada pencairan yang disetujui</p></div></td></tr>
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
                            <td><b>{{ $t->nasabah->nasabah_nama ?? '-' }}</b></td>
                            <td>{{ \Carbon\Carbon::parse($t->tarik_tanggal)->format('d/m/Y') }}</td>
                            <td style="color:var(--danger);font-weight:700;">Rp {{ number_format($t->tarik_jumlah,0,',','.') }}</td>
                            <td>
                                @if($t->tarik_bank_tujuan)
                                    <div style="font-weight: 600; color: #333;">
                                        {{ $t->tarik_bank_tujuan }} - {{ $t->tarik_nomor_rekening }}
                                    </div>
                                    <div style="font-size: 11px; color: #666;">
                                        a/n {{ $t->tarik_atas_nama }}
                                    </div>
                                @else
                                    <span style="color:#aaa; font-style:italic;">Manual / Tunai</span>
                                @endif
                            </td>
                            <td style="color:var(--danger);font-size:12px;">{{ $t->catatan ?? '-' }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="5"><div class="empty-state"><p>Tidak ada pencairan yang ditolak</p></div></td></tr>
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
        <form method="POST" id="formApprove">
            @csrf
            <div class="form-group">
                <label>Catatan (opsional)</label>
                <input type="text" name="catatan" class="form-control" placeholder="Catatan persetujuan...">
            </div>
            <div class="modal-actions">
                <button type="button" class="btn btn-cancel" onclick="closeModal('modalApprove')">Batal</button>
                <button type="submit" class="btn btn-approve">✅ Setujui & Proses</button>
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
                <label>Alasan Penolakan <span style="color:red">*</span></label>
                <input type="text" name="catatan" class="form-control" placeholder="Jelaskan alasan penolakan..." required>
            </div>
            <div class="modal-actions">
                <button type="button" class="btn btn-cancel" onclick="closeModal('modalReject')">Batal</button>
                <button type="submit" class="btn btn-reject">❌ Tolak</button>
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
        <div style="background:#e8f5e9; border: 1px solid #c8e6c9; padding: 12px; border-radius: 8px; margin: 15px 0; font-size:13px; text-align:left;">
            <div style="font-weight:800; color:#1b5e20; margin-bottom:5px;">📋 REKENING TUJUAN TRANSFER:</div>
            <div style="display:flex; justify-content:space-between; margin-bottom:3px;">
                <span>Bank/E-Wallet:</span> <b>${bank}</b>
            </div>
            <div style="display:flex; justify-content:space-between; margin-bottom:3px; align-items:center;">
                <span>No. Rekening/HP:</span>
                <span>
                    <b id="copy_norek" style="font-size:14px; color:#1565c0; cursor:pointer;" title="Salin Nomor Rekening">${norek}</b>
                    <span style="font-size:10px; color:#666; margin-left:5px; font-style:italic;">(Klik untuk salin)</span>
                </span>
            </div>
            <div style="display:flex; justify-content:space-between;">
                <span>Atas Nama:</span> <b>${atasnama}</b>
            </div>
        </div>
        `;
    } else {
        bankHtml = `
        <div style="background:#fff3e0; border: 1px solid #ffe0b2; padding: 12px; border-radius: 8px; margin: 15px 0; font-size:13px; text-align:left; color:#e65100;">
            ℹ️ Pencairan dilakukan secara manual / tunai langsung kepada nasabah.
        </div>
        `;
    }
    document.getElementById('approveDesc').innerHTML =
        `Pencairan <b>Rp ${jumlah.toLocaleString('id-ID')}</b> atas nama <b>${nama}</b> akan disetujui dan saldo nasabah akan dikurangi.<br>${bankHtml}`;
    document.getElementById('modalApprove').classList.add('show');
    
    // Tambahkan event listener untuk menyalin no rek
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
    document.getElementById('rejectDesc').innerHTML = `Pengajuan pencairan <b>${nama}</b> akan ditolak. Saldo nasabah tidak berubah.`;
    document.getElementById('modalReject').classList.add('show');
}
function closeModal(id) { document.getElementById(id).classList.remove('show'); }
document.querySelectorAll('.modal-overlay').forEach(m => {
    m.addEventListener('click', e => { if (e.target === m) m.classList.remove('show'); });
});
</script>
</body>
</html>
