<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Bulanan Bank Sampah - {{ $bulan }}/{{ $tahun }}</title>
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            padding: 30px;
            color: #000;
            background: #fff;
            line-height: 1.5;
        }

        .header {
            text-align: center;
            border-bottom: 3px double #000;
            padding-bottom: 15px;
            margin-bottom: 30px;
        }

        .header h1 {
            font-size: 24px;
            text-transform: uppercase;
            margin-bottom: 5px;
            letter-spacing: 1px;
        }

        .header p {
            font-size: 14px;
            margin: 0;
            font-style: italic;
        }

        .title-block {
            text-align: center;
            margin-bottom: 25px;
        }

        .title-block h2 {
            font-size: 18px;
            text-transform: uppercase;
            text-decoration: underline;
            margin-bottom: 5px;
        }

        .title-block p {
            font-size: 14px;
            margin: 0;
        }

        /* Summary Stats Cards */
        .summary-box {
            display: flex;
            justify-content: space-between;
            gap: 20px;
            margin-bottom: 30px;
        }

        .card {
            border: 1px solid #000;
            padding: 15px;
            flex: 1;
            text-align: center;
        }

        .card h3 {
            font-size: 12px;
            text-transform: uppercase;
            margin-bottom: 10px;
            letter-spacing: 0.5px;
        }

        .card p {
            font-size: 18px;
            font-weight: bold;
            margin: 0;
        }

        /* Table Design */
        h3.section-title {
            font-size: 14px;
            text-transform: uppercase;
            border-bottom: 1px solid #000;
            padding-bottom: 5px;
            margin-top: 30px;
            margin-bottom: 15px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
            margin-bottom: 20px;
        }

        th, td {
            border: 1px solid #000;
            padding: 8px 10px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        /* Signatures block */
        .signature-block {
            margin-top: 50px;
            display: flex;
            justify-content: space-between;
            page-break-inside: avoid;
        }

        .sig-box {
            width: 200px;
            text-align: center;
        }

        .sig-space {
            height: 70px;
        }

        .sig-name {
            font-weight: bold;
            text-decoration: underline;
        }

        /* Print Button for Screen View */
        .no-print-bar {
            background: #f4f4f4;
            padding: 12px;
            text-align: center;
            border-bottom: 1px solid #ddd;
            margin-bottom: 20px;
        }

        .btn-print {
            background: #2d7a4f;
            color: #fff;
            border: none;
            padding: 8px 16px;
            font-size: 14px;
            font-weight: bold;
            cursor: pointer;
            border-radius: 4px;
        }

        .btn-print:hover {
            background: #245f3e;
        }

        @media print {
            .no-print-bar {
                display: none;
            }
            body {
                padding: 0;
            }
        }
    </style>
</head>
<body>

    <div class="no-print-bar">
        <button onclick="window.print()" class="btn-print">🖨️ Cetak Dokumen / Simpan ke PDF</button>
        <button onclick="window.close(); history.back();" class="btn-print" style="background:#666; margin-left:10px;">❌ Tutup Halaman</button>
    </div>

    <div class="header">
        <h1>Bank Sampah Digital</h1>
        <p>Alamat: Jl. Pelestarian Lingkungan No. 100, Jakarta | Telp: (021) 8888-9999 | Email: info@banksampah.org</p>
    </div>

    <div class="title-block">
        <h2>Laporan Performa Operasional Bulanan</h2>
        <p>Periode Bulan: <strong>{{ date('F', mktime(0, 0, 0, $bulan, 10)) }} {{ $tahun }}</strong></p>
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
                        <td>{{ \Carbon\Carbon::parse($setor->setor_tanggal)->format('d/m/Y') }}</td>
                        <td>{{ $setor->nasabah->nasabah_nama }}</td>
                        <td>{{ $setor->sampah->sampah_name }}</td>
                        <td class="text-right">{{ number_format($setor->setor_berat_kg, 2, ',', '.') }}</td>
                        <td class="text-right">Rp {{ number_format($setor->setor_harga_total, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
                <tr style="font-weight: bold; background: #fafafa;">
                    <td colspan="4" class="text-right">TOTAL PENYETORAN:</td>
                    <td class="text-right">{{ number_format($setorans->sum('setor_berat_kg'), 2, ',', '.') }} kg</td>
                    <td class="text-right">Rp {{ number_format($setorans->sum('setor_harga_total'), 0, ',', '.') }}</td>
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
                        <td>{{ \Carbon\Carbon::parse($tarik->tarik_tanggal)->format('d/m/Y') }}</td>
                        <td>{{ $tarik->nasabah->nasabah_nama }}</td>
                        <td class="text-right">Rp {{ number_format($tarik->tarik_jumlah, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
                <tr style="font-weight: bold; background: #fafafa;">
                    <td colspan="3" class="text-right">TOTAL PENARIKAN:</td>
                    <td class="text-right">Rp {{ number_format($penarikans->sum('tarik_jumlah'), 0, ',', '.') }}</td>
                </tr>
            @endif
        </tbody>
    </table>

    <div class="signature-block">
        <div class="sig-box">
            <p>Mengetahui,</p>
            <p>Direktur Bank Sampah</p>
            <div class="sig-space"></div>
            <p class="sig-name">Ir. Green Ranger</p>
            <p>NIP. 198008082010121001</p>
        </div>
        <div class="sig-box">
            <p>Jakarta, {{ date('d F Y') }}</p>
            <p>Petugas Administrasi,</p>
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
