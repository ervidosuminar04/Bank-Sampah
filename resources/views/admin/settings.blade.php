<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengaturan Admin - Bank Sampah</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f4f7f6;
            color: #333;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .card {
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            padding: 40px;
            width: 100%;
            max-width: 600px;
            border-top: 5px solid #2d7a4f;
        }

        .role-badge {
            display: inline-block;
            background: #e8f5e9;
            color: #2d7a4f;
            font-size: 11px;
            font-weight: 700;
            padding: 5px 12px;
            border-radius: 20px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 20px;
        }

        h1 {
            font-size: 22px;
            color: #2d7a4f;
            margin-bottom: 12px;
        }

        p {
            font-size: 14px;
            line-height: 1.6;
            color: #555;
            margin-bottom: 24px;
        }

        .info-box {
            background: #f9fbf9;
            border: 1px solid #e2ece2;
            border-radius: 6px;
            padding: 16px;
            font-size: 13px;
            color: #444;
            margin-bottom: 24px;
        }

        .info-box ul {
            padding-left: 20px;
            margin-top: 8px;
        }

        .btn-back {
            display: inline-block;
            background: #2d7a4f;
            color: white;
            padding: 10px 20px;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            transition: background 0.2s;
        }

        .btn-back:hover { background: #245f3e; }
    </style>
</head>
<body>

    <div class="card">
        <span class="role-badge">Halaman Khusus Admin</span>
        <h1>⚙️ Panel Pengaturan Admin</h1>
        <p>
            Halaman ini terproteksi oleh <code>role:admin</code> middleware. Anda berhasil mengakses halaman ini karena masuk sebagai pengguna dengan tipe <strong>Admin</strong>.
        </p>

        <div class="info-box">
            <strong>Detail Akun Anda:</strong>
            <ul>
                <li>Nama: {{ $admin->admin_nama }}</li>
                <li>Username: <code>{{ $admin->admin_username }}</code></li>
                <li>Level Jabatan: <code>{{ $admin->admin_level }}</code></li>
                <li>Status: <strong>{{ ucfirst($admin->admin_status) }}</strong></li>
            </ul>
        </div>

        <a href="{{ url('/dashboard') }}" class="btn-back">Kembali ke Dashboard</a>
    </div>

</body>
</html>
