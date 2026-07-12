<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class HandleGuestSession
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check() && !session()->has('guest_id')) {
            session(['guest_id' => 'guest_' . Str::random(32)]);
        }

        return $next($request);
    }
}
