<?php

namespace App\Http\Controllers;

use App\Models\AccessCoupon;
use App\Models\Categories;
use App\Models\Subscription;
use Illuminate\Http\Request;

class AccessCouponController extends Controller
{
    public function showRedeemForm()
    {
        return view('pages.access-coupon.redeem');
    }
    public function validateAccessCoupon(Request $request)
    {
        $coupon = AccessCoupon::where('code', $request->code)->first();

        if (!$coupon) {
            return response()->json([
                'valid' => false,
                'message' => app()->getLocale() == 'fr' ?
                    'Coupon invalide' :
                    'Invalid coupon'
            ]);
        }

        if ($coupon->is_used) {
            return response()->json([
                'valid' => false,
                'message' => app()->getLocale() == 'fr' ?
                    'Ce coupon a déjà été utilisé' :
                    'This coupon has already been used'
            ]);
        }

        // Obtener el nombre de la categoría
        $category = Categories::find($coupon->category_id);
        $categoryName = $category ? (app()->getLocale() == 'fr' ? $category->name_fr : $category->name) : 'Unknown';

        return response()->json([
            'valid' => true,
            'type' => 'access',
            'category_id' => $coupon->category_id,
            'category_name' => $categoryName,
            'duration_days' => $coupon->duration_days,
            'message' => app()->getLocale() == 'fr' ?
                "Coupon valide! Accès temporaire de {$coupon->duration_days} jours" :
                "Valid coupon! {$coupon->duration_days} days temporary access"
        ]);
    }

    public function redeemAccessCoupon(Request $request)
    {
        $coupon = AccessCoupon::where('code', $request->code)
            ->where('is_used', false)
            ->firstOrFail();

        $user = auth()->user();

        // Crear suscripción temporal
        $subscription = Subscription::create([
            'user_id' => $user->id,
            'amount' => 0,
            'start_date' => now(),
            'end_date' => now()->addDays($coupon->duration_days),
            'status' => 'active',
        ]);

        $subscription->categories()->attach($coupon->category_id);

        // Marcar cupón como usado
        $coupon->update([
            'is_used' => true,
            'used_at' => now(),
            'used_by_user_id' => $user->id
        ]);

        return response()->json([
            'success' => true,
            'message' => app()->getLocale() == 'fr' ?
                'Accès temporaire activé avec succès' :
                'Temporary access successfully activated'
        ]);
    }
}
