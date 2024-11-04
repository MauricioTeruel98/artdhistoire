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
            $categoryId = $request->route('id');
        }

        // Verificar si el usuario tiene role_id = 1
        if ($user && $user->role_id === 1) {
            return $next($request);
        }

        // Verificar si la IP está en la lista blanca
        if (Whitelist::where('ip_address', $ipAddress)->exists()) {
            return $next($request);
        }

        // Verificar si el usuario está autenticado y tiene una suscripción activa
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

        return redirect()->route('subscription.required', ['category_id' => $categoryId])
            ->with('error', 'Acceso denegado. Necesitas una suscripción activa para esta saga.');
    }
}
