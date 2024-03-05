<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ManajerAuth
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
            if ($user && $role == 'manajer') {
                return $next($request);
            }
            return redirect()->route('login');
        }
        return redirect()->route('login');
    }
}
