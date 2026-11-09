<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        if (!in_array($user->role, $roles)) {
            return redirect($user->getHomeRoute())
                ->with('error', 'Halaman ini khusus untuk wewenang pimpinan (' . implode(' / ', array_map('ucfirst', $roles)) . ').');
        }

        return $next($request);
    }
}
