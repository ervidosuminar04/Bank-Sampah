<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Transaksi Sampah - Realive</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Nunito:wght@700;800;900&family=Nunito+Sans:wght@400;600;700&display=swap');

        :root {
            --color-solar:      #FFD700;
            --color-sunburst:   #FFA500;
            --color-ember:      #F5511E;
            --color-flame:      #E63946;

            --color-lime:       #C8E000;
            --color-sprout:     #7DB825;
            --color-forest:     #2D6A2D;
            --color-canopy:     #1A3A1A;

            --color-black:      #0A0A0A;
            --color-white:      #FFFFFF;
            --color-mist:       #F4F7F0;
            --color-fog:        #8A9E8A;
            --color-smoke:      #D4DDD4;

            --gradient-brand: linear-gradient(135deg, #FFD700 0%, #7DB825 50%, #2D6A2D 100%);

            --bg-page:          var(--color-mist);
            --bg-surface:       var(--color-white);
            --text-primary:     var(--color-canopy);
            --text-secondary:   var(--color-forest);
            --text-muted:       var(--color-fog);
            --border-default:   var(--color-smoke);
            --border-focus:     var(--color-sprout);

            --shadow-md:    0 4px 16px rgba(26, 58, 26, 0.12);
            --shadow-xl:    0 16px 56px rgba(26, 58, 26, 0.24);
            --shadow-focus: 0 0 0 3px rgba(125, 184, 37, 0.45);
            --radius-sm:   8px;
            --radius-md:   16px;
            --radius-full: 9999px;
            
            --ease-spring: cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Nunito Sans', sans-serif;
            background-color: var(--color-mist);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-primary);
            padding: 20px;
            position: relative;
            overflow-x: hidden;
        }

        body::before {
            content: "";
            position: absolute;
            top: 0; left: 0; width: 100%; height: 100%;
            z-index: -1;
            background-image: url('{{ asset('images/Pattern 1@3x.png') }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            opacity: 0.3;
        }

        .card {
            background: var(--bg-surface);
            border-radius: 24px;
            box-shadow: var(--shadow-xl);
            padding: 40px 32px;
            width: 100%;
            max-width: 600px;
            animation: fadeSlideUp 0.6s var(--ease-spring) forwards;
        }

        @keyframes fadeSlideUp {
            from { opacity: 0; transform: translateY(20px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        h2 {
            font-family: 'Nunito', sans-serif;
            font-size: 22px;
            font-weight: 800;
            color: var(--text-primary);
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .subtitle {
            font-size: 14px;
            color: var(--text-muted);
            margin-bottom: 28px;
            font-weight: 600;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group.full-width {
            grid-column: span 2;
        }

        label {
            display: block;
            font-size: 14px;
            color: var(--text-primary);
            margin-bottom: 8px;
            font-weight: 700;
        }

        .form-control-static {
            display: flex;
            align-items: center;
            height: 48px;
            padding: 0 16px;
            background-color: var(--color-mist);
            border: 2px solid var(--border-default);
            border-radius: var(--radius-sm);
            font-size: 15px;
            font-weight: 700;
            color: var(--color-forest);
        }

        .form-control {
            width: 100%;
            height: 48px;
            padding: 0 16px;
            border: 2px solid var(--border-default);
            border-radius: var(--radius-sm);
            font-family: 'Nunito Sans', sans-serif;
            font-size: 15px;
            color: var(--text-primary);
            transition: all 0.2s ease;
            outline: none;
            background: var(--bg-surface);
        }

        .form-control:focus {
            border-color: var(--border-focus);
            box-shadow: var(--shadow-focus);
        }

        .nilai-preview {
            background: var(--color-mist);
            border-radius: var(--radius-sm);
            padding: 12px 16px;
            margin-top: 10px;
            font-size: 15px;
            font-weight: 700;
            color: var(--color-forest);
            display: flex;
            align-items: center;
            gap: 8px;
            border: 1px solid rgba(125, 184, 37, 0.15);
        }

        .button-group {
            display: flex;
            gap: 16px;
            margin-top: 28px;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex: 1;
            height: 48px;
            padding: 0 24px;
            font-family: 'Nunito Sans', sans-serif;
            font-size: 15px;
            font-weight: 700;
            border: none;
            border-radius: var(--radius-full);
            cursor: pointer;
            transition: all 0.2s var(--ease-spring);
            text-decoration: none;
        }

        .btn-primary {
            background: var(--gradient-brand);
            color: var(--color-white);
            box-shadow: var(--shadow-md);
        }

        .btn-primary:hover {
            transform: scale(1.02);
            box-shadow: var(--shadow-xl);
        }

        .btn-secondary {
            background: var(--color-mist);
            color: var(--color-forest);
            border: 2px solid var(--color-smoke);
        }

        .btn-secondary:hover {
            background: var(--color-smoke);
            transform: scale(1.02);
        }

        .alert-error {
            background: rgba(230, 57, 70, 0.1);
            border: 1px solid var(--color-flame);
            color: var(--color-flame);
            padding: 12px 16px;
            border-radius: var(--radius-sm);
            margin-bottom: 24px;
            font-size: 14px;
            font-weight: 600;
        }

        .alert-error ul { padding-left: 16px; }

        @media (max-width: 576px) {
            .form-row {
                grid-template-columns: 1fr;
            }
            .form-group.full-width {
                grid-column: span 1;
            }
        }
    </style>
</head>
<body>
    <div class="card">
        <h2>✏️ Edit Transaksi Timbangan</h2>
        <p class="subtitle">Sesuaikan data timbangan sampah nasabah di bawah ini</p>

        @if ($errors->any())
            <div class="alert-error">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('pengepul.transaksi.update', $transaksi->id) }}" enctype="multipart/form-data">
            @csrf

            <div class="form-row">
                <div class="form-group">
                    <label>Nama Nasabah</label>
                    <div class="form-control-static">
                        👤 {{ $transaksi->nasabah->nasabah_nama ?? '-' }}
                    </div>
                </div>

                <div class="form-group">
                    <label>Jenis Sampah</label>
                    <select name="id_sampah" id="id_sampah" class="form-control" required>
                        @foreach($sampahs as $s)
                            <option value="{{ $s->id_sampah }}" data-harga="{{ $s->sampah_harga_kg }}"
                                {{ $transaksi->id_sampah == $s->id_sampah ? 'selected' : '' }}>
                                {{ $s->sampah_name }} – Rp {{ number_format($s->sampah_harga_kg, 0, ',', '.') }}/kg
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Berat (kg)</label>
                    <input type="number" name="berat_kg" id="berat_kg"
                           class="form-control" step="0.01" min="0.01"
                           value="{{ old('berat_kg', $transaksi->berat_kg) }}" required
                           oninput="hitungNilai()">
                    <div class="nilai-preview">
                        💰 Nilai: <span id="nilaiText" style="font-family: 'JetBrains Mono', monospace; font-weight: 800;">Rp {{ number_format($transaksi->nilai_idr, 0, ',', '.') }}</span>
                    </div>
                </div>

                <div class="form-group">
                    <label>Gambar Dokumentasi Baru (Opsional)</label>
                    <input type="file" name="foto_dokumentasi" class="form-control" accept="image/*" style="padding-top:10px;">
                    @if($transaksi->transaksi_pengepul_gambar)
                        <div style="margin-top: 8px; font-size:12px; color:var(--color-forest); font-weight: 600;">
                            📄 Sudah ada file terunggah. <a href="{{ asset('storage/' . $transaksi->transaksi_pengepul_gambar) }}" target="_blank" style="color:var(--color-forest); text-decoration: underline;">Lihat Gambar</a>
                        </div>
                    @endif
                </div>
            </div>

            <div class="form-group full-width">
                <label>Keterangan (Opsional)</label>
                <input type="text" name="keterangan" class="form-control"
                       value="{{ old('keterangan', $transaksi->keterangan) }}" placeholder="Catatan tambahan...">
            </div>

            <div class="button-group">
                <a href="{{ route('pengepul.dashboard') }}" class="btn btn-secondary">
                    ❌ Batal
                </a>
                <button type="submit" class="btn btn-primary">
                    💾 Simpan Perubahan
                </button>
            </div>
        </form>
    </div>

    <script>
        function hitungNilai() {
            const select = document.getElementById('id_sampah');
            const beratInput = document.getElementById('berat_kg');
            const nilaiText = document.getElementById('nilaiText');

            const selectedOption = select.options[select.selectedIndex];
            const harga = parseFloat(selectedOption?.dataset?.harga || 0);
            const berat = parseFloat(beratInput.value || 0);
            const nilai = harga * berat;

            nilaiText.textContent = 'Rp ' + nilai.toLocaleString('id-ID', {minimumFractionDigits: 0});
        }

        document.getElementById('id_sampah').addEventListener('change', hitungNilai);
    </script>
</body>
</html>
