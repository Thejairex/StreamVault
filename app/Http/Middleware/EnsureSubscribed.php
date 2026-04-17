<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureSubscribed
{
    /**
     * Rutas que se excluyen del chequeo de suscripción.
     * El usuario puede acceder a estas rutas aunque no tenga suscripción activa.
     */
    protected array $except = [
        'subscription/checkout',
        'logout',
    ];

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($this->shouldPassThrough($request)) {
            return $next($request);
        }

        /** @var \App\Models\User|null $user */
        $user = $request->user();

        if (! $user || ! $user->isSubscribed()) {
            return redirect()->route('subscription.checkout');
        }

        return $next($request);
    }

    /**
     * Determina si la request debe saltear el chequeo.
     */
    protected function shouldPassThrough(Request $request): bool
    {
        foreach ($this->except as $pattern) {
            if ($request->is($pattern)) {
                return true;
            }
        }

        return false;
    }
}
