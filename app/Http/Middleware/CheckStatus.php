<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckStatus
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // kalau belum login, biarkan middleware auth yang handle,
        // tapi ini mencegah error null->status
        if (!$user) {
            abort(401);
        }

        $status = strtolower((string) $user->status);

        if ($status === 'active') {
            return $next($request);
        }

        if ($status === 'verify') {
            return redirect('/verify');
        }

        // status lain (blocked / inactive / dll) => tolak
        abort(403);
    }
}
