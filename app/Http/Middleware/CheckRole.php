<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  $role
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // 1. Periksa apakah user sudah login (memiliki session 'user_id')
        if (!session()->has('user_id')) {
            if ($role === 'pengepul') {
                return redirect()->route('pengepul.pilih');
            }
            return redirect()->route('login')->withErrors(['username' => 'Silakan masuk terlebih dahulu untuk mengakses halaman ini.']);
        }

        // 2. Periksa apakah 'user_type' sesuai dengan role yang dibutuhkan
        if (session('user_type') !== $role) {
            if ($role === 'pengepul') {
                return redirect()->route('pengepul.pilih');
            }
            abort(403, 'Akses ditolak. Halaman ini khusus untuk pengguna dengan peran: ' . ucfirst($role));
        }

        // 3. Verifikasi apakah data user tersebut benar-benar ada di database (mencegah error jika DB di-reset/reseed)
        $userId = session('user_id');
        $userExists = false;

        if ($role === 'admin') {
            $userExists = \App\Models\Admin::where('id_admin', $userId)->exists();
        } elseif ($role === 'nasabah') {
            $userExists = \App\Models\Nasabah::where('id_nasabah', $userId)->exists();
        } elseif ($role === 'pengepul') {
            $userExists = \App\Models\Pengepul::where('id_pengepul', $userId)->exists();
        }

        if (!$userExists) {
            session()->flush();
            if ($role === 'pengepul') {
                return redirect()->route('pengepul.pilih');
            }
            return redirect()->route('login')->withErrors(['username' => 'Sesi Anda telah kedaluwarsa atau tidak valid. Silakan masuk kembali.']);
        }

        return $next($request);
    }
}
