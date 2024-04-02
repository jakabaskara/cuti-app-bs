<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfExpired
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            // Pengguna masih terautentikasi, alihkan ke dashboard sesuai peran (role) mereka
            $karyawan = Auth::user()->karyawan;
            $role = $karyawan->posisi->role->nama_role;

            switch ($role) {
                case 'admin':
                    return redirect()->route('admin.index');
                    break;
                case 'kerani':
                    return redirect()->route('kerani.index');
                    break;
                case 'user':
                    return redirect()->route('user.dashboard');
                    break;
                case 'asisten':
                    return redirect()->route('asisten.index');
                    break;
                case 'manajer':
                    return redirect()->route('manajer.index');
                    break;
                case 'kabag':
                    return redirect()->route('kabag.index');
                    break;
                case 'gm':
                    return redirect()->route('gm.index');
                    break;
                case 'brm':
                    return redirect()->route('sevp.index');
                    break;
                default:
                    // Jika tidak ada peran yang cocok, alihkan ke halaman default
                    return redirect()->route('login');
            }
        }

        $response = $next($request);

        if ($response->getStatusCode() == Response::HTTP_UNPROCESSABLE_ENTITY) {
            // Ini adalah status code 419 (Page Expired)
            return redirect()->route('login')->with('message', 'Session Expired');
        }

        return $response;
    }
}
