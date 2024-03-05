<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $username = Auth::user();
        if ($username) {
            $user = User::where('username', $username->username)->get()->first();
            $role = $user->karyawan->posisi->role->nama_role;
            if ($user && $role == 'admin') {
                return $next($request);
            }
            return redirect()->route('login');
        }
        return $next($request);
    }
}
