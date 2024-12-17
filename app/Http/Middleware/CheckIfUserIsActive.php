<?php

namespace App\Http\Middleware;

use Auth;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckIfUserIsActive
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {

        // Pengecualian untuk rute tertentu
        if (
            $request->routeIs('verification.*') ||
            $request->routeIs('filament.merchant.auth.email-verification.*')
        ) {
            return $next($request);
        }
        // Tunggu sampai sesi dimulai dan autentikasi selesai
        if (Auth::check()) {
            $user = Auth::user();
            // Pastikan user tidak null dan memiliki properti is_active
            if (!$user->is_active) {
                // Logout user
                Auth::logout();

                // Redirect ke halaman error dengan pesan
                return redirect()->route('inactive')->withErrors([
                    'message' => 'Akun Anda tidak aktif. Silakan hubungi admin.',
                ]);
            }
        }

        // Lanjutkan ke request berikutnya
        return $next($request);
    }
}
