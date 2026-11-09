<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckUnitAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, string $unitCode): Response
    {
        $user = Auth::user();

        if (! $user) {
            return redirect()->route('login');
        }

        // Ketua dan Bendahara memiliki hak akses ke semua unit
        if (in_array($user->role, ['direktur', 'bendahara'])) {
            return $next($request);
        }

        // Jika admin unit mencoba membuka unit usaha lain
        if ($user->role === 'admin_unit' && $user->unit_code !== $unitCode) {
            $unitNames = [
                'cfd' => 'Unit 3: CFD & Pasar UMKM Minggu',
                'pamsimas' => 'Unit 4: Pamsimas Air Bersih',
            ];

            $userUnit = $unitNames[$user->unit_code] ?? $user->unit_code;
            $targetUnit = $unitNames[$unitCode] ?? $unitCode;

            return redirect($user->getHomeRoute())
                ->with('error', "Akses Dibatasi! Anda login sebagai Admin {$userUnit}, sehingga tidak memiliki izin mengakses modul {$targetUnit}.");
        }

        return $next($request);
    }
}
