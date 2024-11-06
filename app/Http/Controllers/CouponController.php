<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    public function validateCoupon(Request $request)
    {
        $coupon = Coupon::where('code', $request->code)->first();

        if (!$coupon) {
            return response()->json([
                'valid' => false,
                'message' => app()->getLocale() == 'fr' ?
                    'Coupon invalide' :
                    'Invalid coupon'
            ]);
        }

        // Validación para cupones con fecha límite
        if ($coupon->is_dateable) {
            if (now() > $coupon->limit_date) {
                return response()->json([
                    'valid' => false,
                    'message' => app()->getLocale() == 'fr' ?
                        'Ce coupon a expiré' :
                        'This coupon has expired'
                ]);
            }
        }
        // Validación para cupones de un solo uso
        else if ($coupon->used) {
            return response()->json([
                'valid' => false,
                'message' => app()->getLocale() == 'fr' ?
                    'Ce coupon a déjà été utilisé' :
                    'This coupon has already been used'
            ]);
        }

        return response()->json([
            'valid' => true,
            'discount_percentage' => $coupon->discount_percentage,
            'message' => app()->getLocale() == 'fr' ?
                "Coupon valide! {$coupon->discount_percentage}% de réduction" :
                "Valid coupon! {$coupon->discount_percentage}% discount"
        ]);
    }
}
