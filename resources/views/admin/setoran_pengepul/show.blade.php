<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Setoran – Bank Sampah Digital</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root { --primary:#1b5e20;--primary-light:#e8f5e9;--danger:#d32f2f;--danger-light:#ffebee;--info:#1565c0;--info-light:#e3f2fd;--white:#ffffff;--gray-light:#f5f5f5;--gray-medium:#e0e0e0;--shadow:0 4px 20px rgba(0,0,0,0.07);--radius:12px; }
        *{box-sizing:border-box;margin:0;padding:0}
        body{font-family:'Outfit',sans-serif;background:#f4f6f5;color:#212121;min-height:100vh}
        .topbar{background:linear-gradient(135deg,var(--primary),#0c3610);color:#fff;padding:16px 32px;display:flex;align-items:center;justify-content:space-between;box-shadow:0 2px 10px rgba(0,0,0,0.15)}
        .topbar h1{font-size:19px;font-weight:800}
        .topbar a{color:#fff;text-decoration:none;font-size:13px;background:rgba(255,255,255,0.15);padding:6px 14px;border-radius:20px}
        .topbar a:hover{background:rgba(255,255,255,0.25)}
        .container{max-width:1100px;margin:0 auto;padding:28px 24px}
        .alert{padding:14px 18px;border-radius:10px;margin-bottom:20px;font-weight:600;font-size:14px}
        .alert-success{background:var(--primary-light);color:var(--primary);border:1px solid #a5d6a7}
        .alert-error{background:var(--danger-light);color:var(--danger);border:1px solid #ef9a9a}
        .summary-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:16px;margin-bottom:28px}
        .sum-card{background:var(--white);border-radius:var(--radius);padding:18px;box-shadow:var(--shadow)}
        .sum-card .lbl{font-size:12px;color:#888;font-weight:600;margin-bottom:6px}
        .sum-card .val{font-size:20px;font-weight:800}
        .card{background:var(--white);border-radius:var(--radius);box-shadow:var(--shadow);overflow:hidden;margin-bottom:24px}
        .card-head{padding:18px 22px;font-size:15px;font-weight:700;color:var(--primary);border-bottom:2px solid var(--primary-light);display:flex;justify-content:space-between;align-items:center}
        .table-wrap{overflow-x:auto}
        table{width:100%;border-collapse:collapse;font-size:13.5px}
        th{background:var(--gray-light);padding:12px 14px;font-weight:600;color:#666;border-bottom:2px solid var(--gray-medium);text-align:left}
        td{padding:12px 14px;border-bottom:1px solid var(--gray-light);vertical-align:middle}
        tr:hover td{background:#fafafa}
        .badge{display:inline-block;padding:4px 12px;border-radius:12px;font-size:12px;font-weight:700}
        .badge-menunggu{background:#fff8e1;color:#e65100}
        .badge-terverifikasi{background:var(--primary-light);color:var(--primary)}
        .badge-ditolak{background:var(--danger-light);color:var(--danger)}
        .info-grid{display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-bottom:24px}
        .info-box{background:var(--white);border-radius:var(--radius);padding:20px;box-shadow:var(--shadow)}
        .info-box h3{font-size:14px;font-weight:700;color:var(--primary);margin-bottom:12px;padding-bottom:8px;border-bottom:1px solid var(--primary-light)}
        .info-row{display:flex;justify-content:space-between;padding:6px 0;font-size:13.5px;border-bottom:1px solid #f5f5f5}
        .info-row:last-child{border-bottom:none}
        .info-row .lbl{color:#666}
        .info-row .val{font-weight:600}
        .actions{display:flex;gap:12px;margin-bottom:24px}
        .btn{padding:10px 22px;border-radius:8px;border:none;font-weight:700;font-size:13px;cursor:pointer;transition:all 0.2s}
        .btn-verify{background:var(--primary);color:#fff}
        .btn-verify:hover{background:#0c3610}
        .btn-reject{background:var(--danger-light);color:var(--danger)}
        .btn-reject:hover{background:#ffcdd2}
        .btn-back{background:var(--gray-light);color:#555;text-decoration:none;display:inline-flex;align-items:center;gap:6px}
        .modal-overlay{display:none;position:fixed;inset:0;background:rgba(0,0,0,0.45);z-index:1000;justify-content:center;align-items:center}
        .modal-overlay.show{display:flex}
        .modal-box{background:#fff;border-radius:16px;padding:28px;width:440px;max-width:95vw;box-shadow:0 20px 60px rgba(0,0,0,0.2)}
        .modal-title{font-size:17px;font-weight:800;margin-bottom:16px}
        .form-group{margin-bottom:14px}
        .form-group label{display:block;font-size:13px;font-weight:600;color:#555;margin-bottom:6px}
        .form-control{width:100%;padding:10px 14px;border:1px solid var(--gray-medium);border-radius:8px;font-size:13px;font-family:inherit;outline:none}
        .form-control:focus{border-color:var(--primary)}
        .modal-actions{display:flex;gap:10px;justify-content:flex-end;margin-top:18px}
        .btn-cancel{background:var(--gray-light);color:#555}
        @media(max-width:640px){.info-grid{grid-template-columns:1fr}}
    </style>
</head>
<body>
<div class="topbar">
    <h1>🔍 Detail Setoran #{{ $setoran->id }}</h1>
    <a href="{{ route('admin.setoran.index') }}">← Kembali ke Daftar Setoran</a>
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
            <div class="info-row"><span class="lbl">Tanggal Setoran</span><span class="val">{{ $setoran->created_at->format('d/m/Y H:i') }}</span></div>
            <div class="info-row"><span class="lbl">Jumlah Transaksi</span><span class="val">{{ count($setoran->transaksi_ids) }} transaksi</span></div>
            <div class="info-row"><span class="lbl">Status</span>
                <span class="badge badge-{{ $setoran->status }}">
                    @if($setoran->status==='terverifikasi') ✅ Terverifikasi
                    @elseif($setoran->status==='menunggu') ⏳ Menunggu
                    @else ❌ Ditolak @endif
                </span>
            </div>
            @if($setoran->catatan)
            <div class="info-row"><span class="lbl">Catatan Admin</span><span class="val" style="color:#666;">{{ $setoran->catatan }}</span></div>
            @endif
        </div>
    </div>

    {{-- Ringkasan Finansial --}}
    <div class="summary-grid">
        <div class="sum-card">
            <div class="lbl">💚 Nilai ke Nasabah</div>
            <div class="val" style="color:var(--primary);">Rp {{ number_format($setoran->total_nilai_nasabah,0,',','.') }}</div>
        </div>
        <div class="sum-card">
            <div class="lbl">📊 Total Selisih</div>
            <div class="val">Rp {{ number_format($setoran->total_selisih,0,',','.') }}</div>
        </div>
        <div class="sum-card">
            <div class="lbl">🤑 Komisi Pengepul (50%)</div>
            <div class="val" style="color:#e65100;">Rp {{ number_format($setoran->total_komisi_pengepul,0,',','.') }}</div>
        </div>
        <div class="sum-card" style="border-left:3px solid var(--info);">
            <div class="lbl">🏛️ Bagian Admin (50%)</div>
            <div class="val" style="color:var(--info);">Rp {{ number_format($setoran->total_bagian_admin,0,',','.') }}</div>
        </div>
        <div class="sum-card" style="border:2px solid var(--info);background:var(--info-light);">
            <div class="lbl">💸 Total Disetor ke Admin</div>
            <div class="val" style="color:var(--info);">Rp {{ number_format($setoran->total_disetor,0,',','.') }}</div>
            <div style="font-size:11px;color:#777;margin-top:4px;">(nilai nasabah + bagian admin)</div>
        </div>
    </div>

    {{-- Tombol Aksi (hanya jika menunggu) --}}
    @if($setoran->status === 'menunggu')
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
                        <td>{{ \Carbon\Carbon::parse($t->tanggal)->format('d/m/Y') }}</td>
                        <td>{{ $t->nasabah->nasabah_nama ?? '-' }}</td>
                        <td>{{ $t->sampah->sampah_name ?? '-' }}</td>
                        <td>{{ number_format($t->berat_kg,2) }}</td>
                        <td>Rp {{ number_format($t->harga_beli_kg,0,',','.') }}</td>
                        <td>Rp {{ number_format($t->harga_pasar_kg,0,',','.') }}</td>
                        <td>Rp {{ number_format($t->nilai_idr,0,',','.') }}</td>
                        <td style="color:#e65100;font-weight:600;">Rp {{ number_format($t->komisi_pengepul,0,',','.') }}</td>
                        <td style="color:var(--info);font-weight:600;">Rp {{ number_format($t->bagian_admin,0,',','.') }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="10" style="text-align:center;color:#aaa;padding:24px;">Tidak ada transaksi ditemukan</td></tr>
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
        <p style="font-size:13px;color:#555;margin-bottom:16px;">Setoran sebesar <b>Rp {{ number_format($setoran->total_disetor,0,',','.') }}</b> akan ditandai terverifikasi.</p>
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
        <p style="font-size:13px;color:#555;margin-bottom:16px;">Transaksi terkait akan dapat disetor ulang oleh pengepul.</p>
        <form method="POST" action="{{ route('admin.setoran.reject', $setoran->id) }}">
            @csrf
            <div class="form-group">
                <label>Alasan Penolakan <span style="color:red">*</span></label>
                <input type="text" name="catatan" class="form-control" placeholder="Jelaskan alasan..." required>
            </div>
            <div class="modal-actions">
                <button type="button" class="btn btn-cancel" onclick="document.getElementById('modalReject').classList.remove('show')">Batal</button>
                <button type="submit" class="btn btn-reject">❌ Tolak</button>
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
