<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureSubscribed
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->user()) {
            return redirect()->route('login');
        }

        if (! $request->user()->isSubscribed()) {
            return redirect()->route('subscription.checkout')
                ->with('warning', 'Necesitás una suscripción activa para acceder a StreamVault.');
        }

        return $next($request);
    }
}
