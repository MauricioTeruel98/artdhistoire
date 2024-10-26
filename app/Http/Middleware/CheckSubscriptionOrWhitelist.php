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
        if ($request->route('category_id')) {
            $categoryId = $request->route('category_id');
        } else {
            $categoryId = $request->route('id'); // Asumiendo que el ID de la categoría está en la ruta
        }

        // Verificar si la IP está en la lista blanca
        if (Whitelist::where('ip_address', $ipAddress)->exists()) {
            return $next($request);
        }

        // Verificar si el usuario está autenticado y tiene una suscripción activa para esta categoría
        if ($user) {
            $hasActiveSubscription = $user->subscriptions()
                ->where('status', 'active')
                ->whereHas('categories', function ($query) use ($categoryId) {
                    $query->where('categories.id', $categoryId);
                })
                ->where('end_date', '>', now())
                ->exists();

            if ($hasActiveSubscription) {
                return $next($request);
            }
        }

        // Si no tiene suscripción, redirigir a la página de suscripción requerida
        return redirect()->route('subscription.required', ['category_id' => $categoryId])
            ->with('error', 'Acceso denegado. Necesitas una suscripción activa para esta saga.');
    }
}