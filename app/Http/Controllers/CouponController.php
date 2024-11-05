<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    public function validateCoupon(Request $request)
    {
        $coupon = Coupon::where('code', $request->code)
            ->where('used', false)
            ->first();

        if (!$coupon) {
            return response()->json([
                'valid' => false,
                'message' => app()->getLocale() == 'fr' ? 
                    'Coupon invalide ou déjà utilisé' : 
                    'Invalid or already used coupon'
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