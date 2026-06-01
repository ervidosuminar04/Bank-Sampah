<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Bulanan Bank Sampah - {{ $bulan }}/{{ $tahun }}</title>
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

        body {
            font-family: 'Nunito Sans', sans-serif;
            padding: 30px;
            color: var(--color-canopy);
            background: #fff;
            line-height: 1.5;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 3px double var(--color-smoke);
            padding-bottom: 15px;
            margin-bottom: 30px;
        }

        .header-info h1 {
            font-family: 'Nunito', sans-serif;
            font-size: 24px;
            font-weight: 900;
            color: var(--color-forest);
            text-transform: uppercase;
            margin-bottom: 5px;
            letter-spacing: 0.5px;
        }

        .header-info p {
            font-size: 13px;
            margin: 0;
            color: var(--color-canopy);
        }

        .header-logo img {
            max-height: 48px;
            object-fit: contain;
        }

        .title-block {
            text-align: center;
            margin-bottom: 30px;
        }

        .title-block h2 {
            font-family: 'Nunito', sans-serif;
            font-size: 20px;
            font-weight: 800;
            color: var(--color-canopy);
            text-transform: uppercase;
            margin-bottom: 6px;
        }

        .title-block p {
            font-size: 14.5px;
            margin: 0;
            color: var(--color-forest);
            font-weight: 600;
        }

        /* Summary Stats Cards */
        .summary-box {
            display: flex;
            justify-content: space-between;
            gap: 20px;
            margin-bottom: 36px;
        }

        .card {
            border: 1px solid rgba(45, 106, 45, 0.15);
            background: var(--color-mist);
            border-bottom: 4px solid var(--color-sprout);
            padding: 18px;
            flex: 1;
            text-align: center;
            border-radius: 12px;
        }

        .card h3 {
            font-size: 11px;
            text-transform: uppercase;
            margin-bottom: 8px;
            letter-spacing: 0.05em;
            color: var(--color-forest);
            font-weight: 700;
        }

        .card p {
            font-family: 'JetBrains Mono', monospace;
            font-size: 20px;
            font-weight: 800;
            margin: 0;
            color: var(--color-canopy);
        }

        /* Table Design */
        h3.section-title {
            font-family: 'Nunito', sans-serif;
            font-size: 15px;
            font-weight: 800;
            color: var(--color-canopy);
            border-bottom: 2px solid var(--color-sprout);
            padding-bottom: 6px;
            margin-top: 36px;
            margin-bottom: 15px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13.5px;
            margin-bottom: 24px;
        }

        th, td {
            border: 1px solid var(--color-smoke);
            padding: 10px 12px;
            text-align: left;
        }

        th {
            background-color: var(--color-mist);
            font-weight: 700;
            color: var(--color-canopy);
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        td.mono-col {
            font-family: 'JetBrains Mono', monospace;
        }

        /* Signatures block */
        .signature-block {
            margin-top: 60px;
            display: flex;
            justify-content: space-between;
            page-break-inside: avoid;
        }

        .sig-box {
            width: 240px;
            text-align: center;
        }

        .sig-box p {
            font-size: 13.5px;
            color: var(--color-canopy);
        }

        .sig-space {
            height: 70px;
        }

        .sig-name {
            font-weight: 700;
            border-top: 1.5px solid var(--color-canopy);
            padding-top: 6px;
            display: inline-block;
            min-width: 180px;
        }

        /* Print Button for Screen View */
        .no-print-bar {
            background: var(--color-mist);
            padding: 14px;
            text-align: center;
            border-bottom: 1px solid var(--color-smoke);
            margin-bottom: 24px;
            border-radius: 8px;
            display: flex;
            justify-content: center;
            gap: 12px;
        }

        .btn-print {
            background: var(--color-forest);
            color: #fff;
            border: none;
            padding: 9px 18px;
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
            border-radius: 9999px;
            transition: all 0.2s;
        }

        .btn-print:hover {
            background: var(--color-canopy);
        }

        @media print {
            .no-print-bar {
                display: none;
            }
            body {
                padding: 0;
            }
            .card {
                background: #f9f9f9;
                border: 1px solid #ccc;
                border-bottom: 4px solid #333;
            }
            table th {
                background: #eee;
            }
            th, td {
                border: 1px solid #666;
            }
        }
    </style>
</head>
<body>

    <div class="no-print-bar">
        <button onclick="window.print()" class="btn-print">🖨️ Cetak Dokumen / Simpan ke PDF</button>
        <button onclick="window.close(); history.back();" class="btn-print" style="background:var(--color-fog); color: #fff;">❌ Tutup Halaman</button>
    </div>

    <div class="header">
        <div class="header-info">
            <h1>Realive Bank Sampah</h1>
            <p>Alamat: Jl. Pelestarian Lingkungan No. 100, Jakarta | Telp: (021) 8888-9999 | Email: info@realive.org</p>
        </div>
        <div class="header-logo">
            <img src="{{ asset('images/logo Realive@3x.png') }}" alt="Realive Logo">
        </div>
    </div>

    <div class="title-block">
        <h2>Laporan Performa Operasional Bulanan</h2>
        <p>Periode Bulan: <strong>{{ \Carbon\Carbon::create($tahun, $bulan)->locale('id')->isoFormat('MMMM YYYY') }}</strong></p>
    </div>

    <div class="summary-box">
        <div class="card">
            <h3>Total Sampah Terkumpul</h3>
            <p>{{ number_format($setorans->sum('setor_berat_kg'), 2, ',', '.') }} kg</p>
        </div>
        <div class="card">
            <h3>Total Kas Uang Masuk</h3>
            <p>Rp {{ number_format($setorans->sum('setor_harga_total'), 0, ',', '.') }}</p>
        </div>
        <div class="card">
            <h3>Total Saldo Ditarik</h3>
            <p>Rp {{ number_format($penarikans->sum('tarik_jumlah'), 0, ',', '.') }}</p>
        </div>
    </div>

    <h3 class="section-title">I. Rincian Penyetoran Sampah Nasabah</h3>
    <table>
        <thead>
            <tr>
                <th style="width: 5%;" class="text-center">No</th>
                <th style="width: 15%;">Tanggal</th>
                <th style="width: 25%;">Nama Nasabah</th>
                <th style="width: 25%;">Jenis Sampah</th>
                <th style="width: 15%;" class="text-right">Berat (kg)</th>
                <th style="width: 15%;" class="text-right">Nilai Rupiah</th>
            </tr>
        </thead>
        <tbody>
            @if($setorans->isEmpty())
                <tr>
                    <td colspan="6" class="text-center" style="color: #666; font-style: italic;">Tidak ada transaksi penyetoran pada periode ini.</td>
                </tr>
            @else
                @foreach($setorans as $index => $setor)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td class="mono-col">{{ \Carbon\Carbon::parse($setor->setor_tanggal)->format('d/m/Y') }}</td>
                        <td><strong>{{ $setor->nasabah->nasabah_nama }}</strong></td>
                        <td>{{ $setor->sampah->sampah_name }}</td>
                        <td class="text-right mono-col">{{ number_format($setor->setor_berat_kg, 2, ',', '.') }} kg</td>
                        <td class="text-right mono-col">Rp {{ number_format($setor->setor_harga_total, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
                <tr style="font-weight: bold; background: var(--color-mist);">
                    <td colspan="4" class="text-right">TOTAL PENYETORAN:</td>
                    <td class="text-right mono-col">{{ number_format($setorans->sum('setor_berat_kg'), 2, ',', '.') }} kg</td>
                    <td class="text-right mono-col">Rp {{ number_format($setorans->sum('setor_harga_total'), 0, ',', '.') }}</td>
                </tr>
            @endif
        </tbody>
    </table>

    <h3 class="section-title">II. Rincian Penarikan Tabungan Nasabah</h3>
    <table>
        <thead>
            <tr>
                <th style="width: 5%;" class="text-center">No</th>
                <th style="width: 20%;">Tanggal</th>
                <th style="width: 50%;">Nama Nasabah</th>
                <th style="width: 25%;" class="text-right">Jumlah Uang</th>
            </tr>
        </thead>
        <tbody>
            @if($penarikans->isEmpty())
                <tr>
                    <td colspan="4" class="text-center" style="color: #666; font-style: italic;">Tidak ada transaksi penarikan saldo pada periode ini.</td>
                </tr>
            @else
                @foreach($penarikans as $index => $tarik)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td class="mono-col">{{ \Carbon\Carbon::parse($tarik->tarik_tanggal)->format('d/m/Y') }}</td>
                        <td><strong>{{ $tarik->nasabah->nasabah_nama }}</strong></td>
                        <td class="text-right mono-col">Rp {{ number_format($tarik->tarik_jumlah, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
                <tr style="font-weight: bold; background: var(--color-mist);">
                    <td colspan="3" class="text-right">TOTAL PENARIKAN:</td>
                    <td class="text-right mono-col">Rp {{ number_format($penarikans->sum('tarik_jumlah'), 0, ',', '.') }}</td>
                </tr>
            @endif
        </tbody>
    </table>

    <div class="signature-block">
        <div class="sig-box">
            <p>Mengetahui,</p>
            <p><strong>Direktur Bank Sampah</strong></p>
            <div class="sig-space"></div>
            <p class="sig-name">Ir. Green Ranger</p>
            <p>NIP. 198008082010121001</p>
        </div>
        <div class="sig-box">
            <p>Jakarta, {{ \Carbon\Carbon::now()->locale('id')->isoFormat('D MMMM YYYY') }}</p>
            <p><strong>Petugas Administrasi,</strong></p>
            <div class="sig-space"></div>
            <p class="sig-name">{{ session('user_type') === 'admin' ? Auth::user() ? Auth::user()->name : 'Petugas Admin' : 'Petugas Admin' }}</p>
            <p>Staff Operasional</p>
        </div>
    </div>

    <script>
        // Auto trigger print dialog on page load
        window.addEventListener('DOMContentLoaded', (event) => {
            setTimeout(function() {
                window.print();
            }, 500);
        });
    </script>

</body>
</html>
