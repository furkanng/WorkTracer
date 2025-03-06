<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckRole
{
    public function handle(Request $request, Closure $next, $role)
    {
        if (!$request->user() || !$request->user()->role->name === $role) {
            abort(403, 'Yetkisiz erişim.');
        }

        return $next($request);
    }
} 