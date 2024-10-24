<?php

namespace App\Http\Middleware;

use App\Helpers\IpHelper;
use Closure;
use Illuminate\Http\Request;
use App\Models\Whitelist;

class CheckSubscriptionOrWhitelist
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();
        $ipAddress = IpHelper::getPublicIp();

        // Verificar si el usuario está suscripto o si la IP está en la whitelist
        if (($user && $user->subscription && $user->subscription->isActive()) || 
            Whitelist::where('ip_address', $ipAddress)->exists()) {
            return $next($request);
        }

        // Si no cumple ninguna de las condiciones, redirigir
        return redirect()->route('subscription.required')->with('error', 'Acceso denegado. Necesitas una suscripción activa o estar en la whitelist.');
    }
}