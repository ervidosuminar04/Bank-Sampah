<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Bulanan Pengepul – {{ $pengepul->nama }}</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Nunito:wght@700;800;900&family=Nunito+Sans:wght@400;600;700&family=JetBrains+Mono:wght@400&display=swap');

        :root {
            --color-solar:      #FFD700;
            --color-sunburst:   #FFA500;
            --color-lime:       #C8E000;
            --color-sprout:     #7DB825;
            --color-forest:     #2D6A2D;
            --color-canopy:     #1A3A1A;
            --color-white:      #FFFFFF;
            --color-mist:       #F4F7F0;
            --color-fog:        #8A9E8A;
            --color-smoke:      #D4DDD4;
            --shadow-md:        0 4px 16px rgba(26, 58, 26, 0.08);
            --radius-md:        16px;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Nunito Sans', sans-serif;
            background: var(--color-mist);
            color: var(--color-canopy);
            padding: 30px;
        }

        /* ── Tombol aksi – tidak ikut tercetak ── */
        .no-print {
            margin-bottom: 24px;
            display: flex;
            gap: 12px;
            align-items: center;
            flex-wrap: wrap;
            max-width: 900px;
            margin-left: auto;
            margin-right: auto;
        }
        .btn-back {
            background: var(--color-white);
            color: var(--color-forest);
            border: 2px solid var(--color-forest);
            padding: 10px 20px;
            border-radius: 9999px;
            font-size: 13.5px;
            font-weight: 700;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            cursor: pointer;
            transition: all 0.2s;
        }
        .btn-back:hover { background: var(--color-mist); border-color: var(--color-sprout); }

        .btn-cetak {
            background: var(--color-solar);
            color: var(--color-canopy);
            border: none;
            padding: 10px 24px;
            border-radius: 9999px;
            font-size: 13.5px;
            font-weight: 700;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: all 0.2s;
            box-shadow: var(--shadow-md);
        }
        .btn-cetak:hover { background: var(--color-sunburst); transform: translateY(-1px); }

        /* ── Kertas Laporan ── */
        .laporan-wrap {
            background: var(--color-white);
            max-width: 900px;
            margin: 0 auto;
            border-radius: 24px 24px 16px 16px;
            box-shadow: var(--shadow-md);
            padding: 50px;
            border: 1px solid rgba(125, 184, 37, 0.15);
            position: relative;
        }

        /* ── Header Laporan ── */
        .laporan-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 32px;
            padding-bottom: 24px;
            border-bottom: 3px double var(--color-smoke);
        }

        .laporan-kop h2 {
            font-family: 'Nunito', sans-serif;
            font-size: 24px;
            font-weight: 900;
            color: var(--color-forest);
            margin-bottom: 6px;
        }
        .laporan-kop p {
            font-size: 13.5px;
            color: var(--color-canopy);
            margin-bottom: 2px;
        }

        .laporan-logo img {
            max-height: 48px;
            object-fit: contain;
        }

        .laporan-meta {
            margin-bottom: 28px;
            background: var(--color-mist);
            padding: 20px;
            border-radius: 12px;
            border: 1px solid rgba(45, 106, 45, 0.08);
        }
        .laporan-meta table {
            border: none;
            box-shadow: none;
            width: auto;
        }
        .laporan-meta td {
            padding: 4px 16px 4px 0;
            font-size: 14px;
            border: none;
            color: var(--color-canopy);
        }
        .laporan-meta tr td:first-child {
            font-weight: 700;
            color: var(--color-forest);
            white-space: nowrap;
            width: 140px;
        }

        /* ── Ringkasan ── */
        .ringkasan-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin-bottom: 36px;
        }
        .ringkasan-box {
            background: var(--color-mist);
            border-radius: 12px;
            padding: 18px;
            text-align: center;
            border-bottom: 4px solid var(--color-sprout);
        }
        .ringkasan-box h4 {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.08px;
            color: var(--color-forest);
            font-weight: 700;
            margin-bottom: 6px;
        }
        .ringkasan-box p {
            font-family: 'JetBrains Mono', monospace;
            font-size: 22px;
            font-weight: 800;
            color: var(--color-canopy);
        }

        /* ── Tabel Data ── */
        .section-title {
            font-family: 'Nunito', sans-serif;
            font-size: 16px;
            font-weight: 800;
            color: var(--color-canopy);
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        table.data-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
        }
        table.data-table th {
            background-color: var(--color-mist);
            color: var(--color-canopy);
            padding: 12px 14px;
            text-align: left;
            font-weight: 700;
            border-bottom: 2px solid var(--color-smoke);
            text-transform: uppercase;
            font-size: 11.5px;
            letter-spacing: 0.05em;
        }
        table.data-table td {
            padding: 12px 14px;
            border-bottom: 1px solid var(--color-mist);
            color: var(--color-canopy);
        }
        table.data-table tr:nth-child(even) td { background: rgba(244, 247, 240, 0.3); }
        table.data-table tfoot td {
            font-weight: 800;
            background: var(--color-mist);
            color: var(--color-forest);
            border-top: 2px solid var(--color-sprout);
        }
        
        td.mono-col { font-family: 'JetBrains Mono', monospace; }

        /* ── Footer Laporan ── */
        .laporan-footer {
            margin-top: 48px;
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
        }
        .ttd-box { text-align: center; }
        .ttd-box p { font-size: 13px; color: var(--color-canopy); margin-bottom: 60px; }
        .ttd-nama { font-size: 14px; font-weight: 700; border-top: 1.5px solid var(--color-canopy); padding-top: 6px; display: inline-block; min-width: 180px; color: var(--color-canopy); }

        /* ── Print Media ── */
        @media print {
            body { background: #fff; padding: 0; }
            .no-print { display: none !important; }
            .laporan-wrap { box-shadow: none; border-radius: 0; padding: 0; border: none; }
            .laporan-meta { background: #f9f9f9; border: 1px solid #ddd; }
            .ringkasan-box { background: #f9f9f9; border-bottom: 4px solid #333; }
            table.data-table th { background: #eee; border-bottom: 2px solid #333; }
            table.data-table tfoot td { background: #eee; border-top: 2px solid #333; }
        }
    </style>
</head>
<body>

    <!-- Tombol aksi – tidak tampil saat print -->
    <div class="no-print">
        <a href="{{ route('pengepul.dashboard') }}" class="btn-back">← Kembali ke Dashboard</a>
        <button class="btn-cetak" onclick="window.print()">🖨️ Cetak / Simpan PDF</button>
    </div>

    <div class="laporan-wrap" id="laporan-cetak">

        <!-- Header -->
        <div class="laporan-header">
            <div class="laporan-kop">
                <h2>Realive Bank Sampah</h2>
                <p><strong>Laporan Bulanan Performa Pengepul</strong></p>
                <p>Dicetak pada: {{ \Carbon\Carbon::now()->locale('id')->isoFormat('dddd, D MMMM YYYY') }}</p>
            </div>
            <div class="laporan-logo">
                <img src="{{ asset('images/logo Realive@3x.png') }}" alt="Realive Logo">
            </div>
        </div>

        <!-- Meta Info -->
        <div class="laporan-meta">
            <table>
                <tr>
                    <td>Pengepul</td>
                    <td>: <strong>{{ $pengepul->nama }}</strong></td>
                </tr>
                <tr>
                    <td>Alamat Stasiun</td>
                    <td>: {{ $pengepul->alamat }}</td>
                </tr>
                <tr>
                    <td>Telepon</td>
                    <td>: {{ $pengepul->telepon ?? '-' }}</td>
                </tr>
                <tr>
                    <td>Periode Laporan</td>
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
        <div class="section-title">📋 Rincian Transaksi Penyetoran</div>

        @if($transaksi->isEmpty())
            <p style="text-align:center;color:var(--color-fog);padding:30px 0;font-style:italic;">
                Tidak ada transaksi pada periode ini.
            </p>
        @else
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width: 50px;">No</th>
                    <th>Tanggal</th>
                    <th>Nasabah</th>
                    <th>Jenis Sampah</th>
                    <th style="text-align:right;">Berat (kg)</th>
                    <th style="text-align:right;">Nilai (Rp)</th>
                    <th>Keterangan</th>
                </tr>
            </thead>
            <tbody>
                @foreach($transaksi as $i => $t)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td class="mono-col">{{ \Carbon\Carbon::parse($t->transaksi_pengepul_tanggal)->format('d/m/Y') }}</td>
                    <td><strong>{{ $t->nasabah->nasabah_nama ?? '-' }}</strong></td>
                    <td>{{ $t->sampah->sampah_nama ?? '-' }}</td>
                    <td class="mono-col" style="text-align:right;">{{ number_format($t->berat_kg, 2) }}</td>
                    <td class="mono-col" style="text-align:right;">Rp {{ number_format($t->nilai_idr, 0, ',', '.') }}</td>
                    <td>{{ $t->transaksi_pengepul_keterangan ?? '-' }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="4" style="text-align:right;">TOTAL</td>
                    <td class="mono-col" style="text-align:right;">{{ number_format($totalBerat, 2) }} kg</td>
                    <td class="mono-col" style="text-align:right;">Rp {{ number_format($totalNilai, 0, ',', '.') }}</td>
                    <td></td>
                </tr>
            </tfoot>
        </table>
        @endif

        <!-- Footer TTD -->
        <div class="laporan-footer">
            <div>
                <p style="font-size:12px;color:var(--color-fog);">Laporan ini digenerate otomatis oleh sistem Realive.</p>
            </div>
            <div class="ttd-box">
                <p>{{ \Carbon\Carbon::now()->locale('id')->isoFormat('D MMMM YYYY') }}</p>
                <span class="ttd-nama">{{ $pengepul->nama }}</span>
                <br><small style="color:var(--color-forest);font-weight:700;font-size:11px;text-transform:uppercase;">Pengepul Stasiun</small>
            </div>
        </div>

    </div>
</body>
</html>
