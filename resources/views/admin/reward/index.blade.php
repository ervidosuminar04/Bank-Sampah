<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Eco Rewards – Bank Sampah Digital</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #1b5e20;
            --primary-light: #e8f5e9;
            --danger: #d32f2f;
            --danger-light: #ffebee;
            --info: #1565c0;
            --info-light: #e3f2fd;
            --accent: #ffb300;
            --white: #ffffff;
            --gray-light: #f5f5f5;
            --gray-medium: #e0e0e0;
            --shadow: 0 4px 20px rgba(0,0,0,0.07);
            --radius: 12px;
            --transition: all 0.3s ease;
        }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Outfit', sans-serif;
            background: #f4f6f5;
            color: #212121;
            min-height: 100vh;
        }
        .topbar {
            background: linear-gradient(135deg, var(--primary), #0c3610);
            color: #fff;
            padding: 16px 32px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 2px 10px rgba(0,0,0,0.15);
        }
        .topbar h1 { font-size: 20px; font-weight: 800; }
        .topbar a {
            color: #fff;
            text-decoration: none;
            font-size: 13px;
            background: rgba(255,255,255,0.15);
            padding: 6px 14px;
            border-radius: 20px;
        }
        .topbar a:hover { background: rgba(255,255,255,0.25); }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 28px 24px;
        }
        .alert {
            padding: 14px 18px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-weight: 600;
            font-size: 14px;
        }
        .alert-success { background: var(--primary-light); color: var(--primary); border: 1px solid #a5d6a7; }
        .alert-error { background: var(--danger-light); color: var(--danger); border: 1px solid #ef9a9a; }
        
        .main-tabs {
            display: flex;
            gap: 10px;
            margin-bottom: 24px;
            border-bottom: 2px solid var(--gray-medium);
            padding-bottom: 8px;
        }
        .main-tab-btn {
            padding: 12px 24px;
            border-radius: 8px 8px 0 0;
            border: none;
            background: none;
            color: #666;
            font-weight: 700;
            font-size: 14.5px;
            cursor: pointer;
            position: relative;
            transition: var(--transition);
        }
        .main-tab-btn.active {
            color: var(--primary);
        }
        .main-tab-btn.active::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 0;
            width: 100%;
            height: 4px;
            background: var(--primary);
            border-radius: 2px;
        }

        .tabs { display: flex; gap: 8px; margin-bottom: 20px; flex-wrap: wrap; }
        .tab-btn {
            padding: 8px 18px;
            border-radius: 8px;
            border: none;
            background: var(--gray-light);
            color: #555;
            font-weight: 700;
            font-size: 12.5px;
            cursor: pointer;
            transition: var(--transition);
        }
        .tab-btn.active { background: var(--primary); color: #fff; }
        .tab-pane { display: none; }
        .tab-pane.active { display: block; }
        .main-pane { display: none; }
        .main-pane.active { display: block; }

        .card { background: var(--white); border-radius: var(--radius); box-shadow: var(--shadow); overflow: hidden; margin-bottom: 25px; }
        .card-head {
            padding: 18px 22px;
            font-size: 15.5px;
            font-weight: 700;
            color: var(--primary);
            border-bottom: 2px solid var(--primary-light);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .table-wrap { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; font-size: 13.5px; }
        th { background: var(--gray-light); padding: 12px 14px; font-weight: 600; color: #666; border-bottom: 2px solid var(--gray-medium); text-align: left; }
        td { padding: 12px 14px; border-bottom: 1px solid var(--gray-light); vertical-align: middle; }
        tr:hover td { background: #fafafa; }
        .badge { display: inline-block; padding: 3px 10px; border-radius: 12px; font-size: 12px; font-weight: 700; }
        .badge-menunggu { background: #fff8e1; color: #e65100; }
        .badge-diambil { background: var(--primary-light); color: var(--primary); }
        .badge-ditolak { background: var(--danger-light); color: var(--danger); }
        
        .btn {
            padding: 7px 15px;
            border-radius: 8px;
            border: none;
            font-weight: 700;
            font-size: 12px;
            cursor: pointer;
            transition: var(--transition);
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        .btn-approve { background: var(--primary); color: #fff; }
        .btn-approve:hover { background: #0c3610; }
        .btn-reject { background: var(--danger-light); color: var(--danger); }
        .btn-reject:hover { background: #ffcdd2; }
        .btn-edit { background: var(--info-light); color: var(--info); }
        .btn-edit:hover { background: #bbdefb; }
        .btn-delete { background: var(--danger-light); color: var(--danger); }
        .btn-delete:hover { background: #ffcdd2; }
        .btn-add { background: var(--primary); color: #white; padding: 10px 18px; font-size: 13.5px; border-radius: 8px; color:#fff; }
        .btn-add:hover { background: #0c3610; }

        .modal-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.45); z-index: 1000; justify-content: center; align-items: center; }
        .modal-overlay.show { display: flex; }
        .modal-box { background: #fff; border-radius: 16px; padding: 28px; width: 450px; max-width: 95vw; box-shadow: 0 20px 60px rgba(0,0,0,0.2); }
        .modal-title { font-size: 17px; font-weight: 800; margin-bottom: 8px; }
        .modal-sub { font-size: 13px; color: #555; margin-bottom: 18px; line-height: 1.5; }
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
    <h1>🎁 Kelola Penukaran & Katalog Hadiah</h1>
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
                                <td>{{ \Carbon\Carbon::parse($t->tanggal_tukar)->format('d/m/Y') }}</td>
                                <td style="font-weight:700;">{{ $t->hadiah->nama_hadiah ?? 'Hadiah Dihapus' }}</td>
                                <td>{{ $t->jumlah }} pcs</td>
                                <td style="font-weight:800;color:#0d47a1;font-size:14px;">{{ $t->total_poin_ditukar }} Poin</td>
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
                                        <p>Tidak ada permohonan penukaran barang yang menunggu</p>
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
                                <td><b>{{ $t->nasabah->nasabah_nama ?? '-' }}</b></td>
                                <td>{{ \Carbon\Carbon::parse($t->tanggal_tukar)->format('d/m/Y') }}</td>
                                <td>{{ $t->hadiah->nama_hadiah ?? 'Hadiah Dihapus' }}</td>
                                <td>{{ $t->jumlah }} pcs</td>
                                <td style="font-weight:700;color:var(--primary);">{{ $t->total_poin_ditukar }} Poin</td>
                                <td><span style="font-size:12px;color:#555;">{{ $t->admin->admin_nama ?? 'Admin' }}</span></td>
                            </tr>
                            @empty
                            <tr><td colspan="6"><div class="empty-state"><p>Belum ada penukaran barang yang diambil</p></div></td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Sub-pane Ditolak -->
        <div class="tab-pane" id="sub-ditolak">
            <div class="card">
                <div class="card-head">Penukaran Hadiah yang Ditolak (Poin & Stok Dikembalikan)</div>
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
                                <td><b>{{ $t->nasabah->nasabah_nama ?? '-' }}</b></td>
                                <td>{{ \Carbon\Carbon::parse($t->tanggal_tukar)->format('d/m/Y') }}</td>
                                <td>{{ $t->hadiah->nama_hadiah ?? 'Hadiah Dihapus' }}</td>
                                <td>{{ $t->jumlah }} pcs</td>
                                <td style="font-weight:700;color:var(--danger);">+ {{ $t->total_poin_ditukar }} Poin</td>
                                <td style="color:var(--danger);font-size:12px;">{{ $t->catatan ?? '-' }}</td>
                            </tr>
                            @empty
                            <tr><td colspan="6"><div class="empty-state"><p>Tidak ada penukaran barang yang ditolak</p></div></td></tr>
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
                <span>🛍️ Daftar Katalog Barang Eco Rewards</span>
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
                            <th style="width:180px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($hadiahs as $h)
                        <tr>
                            <td><strong style="color:#333;">{{ $h->nama_hadiah }}</strong></td>
                            <td style="font-weight:700;color:var(--primary);">{{ $h->poin_butuh }} Eco Poin</td>
                            <td>
                                <strong style="{{ $h->stok > 0 ? 'color:#2e7d32;' : 'color:var(--danger);' }}">
                                    {{ $h->stok }} pcs
                                </strong>
                            </td>
                            <td style="font-size:12px;color:#666;line-height:1.4;">{{ $h->keterangan ?? '-' }}</td>
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
                                <div class="empty-state"><p>Belum ada hadiah di katalog. Klik tombol di kanan atas untuk menambah.</p></div>
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
                <label>Alasan Penolakan <span style="color:red">*</span></label>
                <input type="text" name="catatan" class="form-control" placeholder="Jelaskan alasan penolakan..." required>
            </div>
            <div class="modal-actions">
                <button type="button" class="btn btn-cancel" onclick="closeModal('modalReject')">Batal</button>
                <button type="submit" class="btn btn-reject">❌ Tolak & Refund Poin</button>
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
