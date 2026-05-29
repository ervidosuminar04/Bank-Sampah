<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Bulanan Pengepul – {{ $pengepul->nama }}</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background: #f4f6f5;
            color: #212121;
            padding: 30px;
        }

        /* ── Tombol aksi – tidak ikut tercetak ── */
        .no-print {
            margin-bottom: 24px;
            display: flex;
            gap: 12px;
            align-items: center;
            flex-wrap: wrap;
        }
        .btn-back {
            background: #e8f5e9;
            color: #1b5e20;
            border: 1px solid #a5d6a7;
            padding: 9px 18px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 700;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            cursor: pointer;
            transition: all 0.2s;
        }
        .btn-back:hover { background: #c8e6c9; }

        .btn-cetak {
            background: #1b5e20;
            color: #fff;
            border: none;
            padding: 9px 18px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 700;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: all 0.2s;
        }
        .btn-cetak:hover { background: #2e7d32; }

        /* ── Kertas Laporan ── */
        .laporan-wrap {
            background: #fff;
            max-width: 900px;
            margin: 0 auto;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            padding: 40px;
        }

        /* ── Header Laporan ── */
        .laporan-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 28px;
            padding-bottom: 20px;
            border-bottom: 2px solid #e8f5e9;
        }

        .laporan-kop h2 {
            font-size: 20px;
            font-weight: 800;
            color: #1b5e20;
            margin-bottom: 4px;
        }
        .laporan-kop p {
            font-size: 13px;
            color: #555;
            margin-bottom: 2px;
        }

        .laporan-logo {
            font-size: 48px;
            line-height: 1;
        }

        .laporan-meta {
            margin-bottom: 24px;
        }
        .laporan-meta table {
            border: none;
            box-shadow: none;
        }
        .laporan-meta td {
            padding: 4px 12px 4px 0;
            font-size: 13.5px;
            border: none;
            color: #444;
        }
        .laporan-meta td:first-child {
            font-weight: 700;
            color: #1b5e20;
            white-space: nowrap;
            width: 160px;
        }

        /* ── Ringkasan ── */
        .ringkasan-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 16px;
            margin-bottom: 28px;
        }
        .ringkasan-box {
            background: #e8f5e9;
            border-radius: 10px;
            padding: 16px;
            text-align: center;
        }
        .ringkasan-box h4 {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            color: #437046;
            font-weight: 700;
            margin-bottom: 6px;
        }
        .ringkasan-box p {
            font-size: 20px;
            font-weight: 800;
            color: #1b5e20;
        }

        /* ── Tabel Data ── */
        .section-title {
            font-size: 14px;
            font-weight: 700;
            color: #1b5e20;
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        table.data-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
        }
        table.data-table th {
            background-color: #1b5e20;
            color: #fff;
            padding: 11px 12px;
            text-align: left;
            font-weight: 600;
        }
        table.data-table td {
            padding: 10px 12px;
            border-bottom: 1px solid #f0f0f0;
            color: #333;
        }
        table.data-table tr:nth-child(even) td { background: #fafafa; }
        table.data-table tfoot td {
            font-weight: 800;
            background: #e8f5e9;
            color: #1b5e20;
            border-top: 2px solid #a5d6a7;
        }

        /* ── Footer Laporan ── */
        .laporan-footer {
            margin-top: 36px;
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
        }
        .ttd-box { text-align: center; }
        .ttd-box p { font-size: 12.5px; color: #555; }
        .ttd-space { height: 60px; }
        .ttd-nama { font-size: 13px; font-weight: 700; border-top: 1px solid #555; padding-top: 4px; display: inline-block; min-width: 160px; }

        /* ── Print Media ── */
        @media print {
            body { background: #fff; padding: 0; }
            .no-print { display: none !important; }
            .laporan-wrap { box-shadow: none; border-radius: 0; padding: 20px; }
        }
    </style>
</head>
<body>

    <!-- Tombol aksi – tidak tampil saat print -->
    <div class="no-print">
        <a href="{{ route('pengepul.dashboard') }}" class="btn-back">← Kembali ke Dashboard</a>
        <button class="btn-cetak" onclick="window.print()">🖨️ Cetak / Print</button>
    </div>

    <div class="laporan-wrap" id="laporan-cetak">

        <!-- Header -->
        <div class="laporan-header">
            <div class="laporan-kop">
                <h2>🌿 Bank Sampah Digital</h2>
                <p><strong>Laporan Bulanan Pengepul</strong></p>
                <p>Dicetak pada: {{ \Carbon\Carbon::now()->isoFormat('dddd, D MMMM YYYY') }}</p>
            </div>
            <div class="laporan-logo">🗂️</div>
        </div>

        <!-- Meta Info -->
        <div class="laporan-meta">
            <table>
                <tr>
                    <td>Nama Pengepul</td>
                    <td>: {{ $pengepul->nama }}</td>
                </tr>
                <tr>
                    <td>Alamat</td>
                    <td>: {{ $pengepul->alamat }}</td>
                </tr>
                <tr>
                    <td>Telepon</td>
                    <td>: {{ $pengepul->telepon ?? '-' }}</td>
                </tr>
                <tr>
                    <td>Periode</td>
                    <td>: {{ \Carbon\Carbon::create($tahun, $bulan)->locale('id')->isoFormat('MMMM YYYY') }}</td>
                </tr>
            </table>
        </div>

        <!-- Ringkasan -->
        <div class="ringkasan-grid">
            <div class="ringkasan-box">
                <h4>Total Transaksi</h4>
                <p>{{ $transaksi->count() }}</p>
            </div>
            <div class="ringkasan-box">
                <h4>Total Berat</h4>
                <p>{{ number_format($totalBerat, 2) }} kg</p>
            </div>
            <div class="ringkasan-box">
                <h4>Total Nilai</h4>
                <p>Rp {{ number_format($totalNilai, 0, ',', '.') }}</p>
            </div>
        </div>

        <!-- Tabel Transaksi -->
        <div class="section-title">📋 Rincian Transaksi</div>

        @if($transaksi->isEmpty())
            <p style="text-align:center;color:#aaa;padding:30px 0;">
                Tidak ada transaksi pada periode ini.
            </p>
        @else
        <table class="data-table">
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
                @foreach($transaksi as $i => $t)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ \Carbon\Carbon::parse($t->tanggal)->format('d/m/Y') }}</td>
                    <td>{{ $t->nasabah->nasabah_nama ?? '-' }}</td>
                    <td>{{ $t->sampah->sampah_name ?? '-' }}</td>
                    <td style="text-align:right;">{{ number_format($t->berat_kg, 2) }}</td>
                    <td style="text-align:right;">{{ number_format($t->nilai_idr, 0, ',', '.') }}</td>
                    <td>{{ $t->keterangan ?? '-' }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="4" style="text-align:right;">TOTAL</td>
                    <td style="text-align:right;">{{ number_format($totalBerat, 2) }}</td>
                    <td style="text-align:right;">{{ number_format($totalNilai, 0, ',', '.') }}</td>
                    <td></td>
                </tr>
            </tfoot>
        </table>
        @endif

        <!-- Footer TTD -->
        <div class="laporan-footer">
            <div>
                <p style="font-size:12px;color:#777;">Laporan ini digenerate otomatis oleh sistem Bank Sampah Digital.</p>
            </div>
            <div class="ttd-box">
                <p>{{ \Carbon\Carbon::now()->isoFormat('D MMMM YYYY') }}</p>
                <div class="ttd-space"></div>
                <span class="ttd-nama">{{ $pengepul->nama }}</span>
                <br><small style="color:#777;font-size:11px;">Pengepul</small>
            </div>
        </div>

    </div>
</body>
</html>
