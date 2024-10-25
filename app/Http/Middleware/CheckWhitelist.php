<?php

namespace App\Http\Middleware;

use App\Helpers\IpHelper;
use Closure;
use Illuminate\Http\Request;
use App\Models\Whitelist;

class CheckWhitelist
{
    public function handle(Request $request, Closure $next)
    {
        // Obtener la IP pública del usuario
        $ipAddress = IpHelper::getPublicIp();

        // Verificar si la IP está en la whitelist
        if (Whitelist::where('ip_address', $ipAddress)->exists()) {
            return $next($request);
        }

        // Si no está en la whitelist, redirigir o mostrar un mensaje
        return redirect()->route('subscription.required')->with('error', 'Acceso denegado. Necesitas una suscripción activa.');
    }
}