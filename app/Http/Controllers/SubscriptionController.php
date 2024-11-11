<?php

namespace App\Http\Controllers;

use App\Models\Categories;
use App\Models\Coupon;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Srmklive\PayPal\Facades\PayPal;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use TCG\Voyager\Facades\Voyager;

class SubscriptionController extends Controller
{
    public function create(Request $request)
    {
        $category = Categories::findOrFail($request->input('category_id'));
        $user = auth()->user();
        $isEnglish = app()->getLocale() != 'fr';

        // Verificaciones de estudiante...
        if ($user->is_student && !$user->validated_student && $user->certificate) {
            return redirect()->route('certificate.pending');
        }

        if ($user->is_student && !$user->validated_student && !$user->certificate) {
            return redirect()->route('certificate.upload', ['category_id' => $category->id]);
        }

        $paymentMethod = $request->input('payment_method');

        // Actualizar esta parte para usar los montos en dólares cuando el idioma es inglés
        $amount = $user->is_student && $user->validated_student ?
            ($isEnglish ? Voyager::setting('site.abono_estudiant_DOLARES') : Voyager::setting('site.abono_estudiant')) : ($isEnglish ? Voyager::setting('site.abono_normal_DOLARES') : Voyager::setting('site.abono_normal'));

        // Solo validar el cupón sin marcarlo como usado
        if ($couponCode = $request->input('coupon_code')) {
            $coupon = Coupon::where('code', $couponCode)
                ->where(function ($query) {
                    $query->where(function ($q) {
                        // Cupones de un solo uso
                        $q->where('is_dateable', false)
                            ->where('used', false);
                    })->orWhere(function ($q) {
                        // Cupones con fecha límite
                        $q->where('is_dateable', true)
                            ->where('limit_date', '>', now());
                    });
                })
                ->first();

            if ($coupon) {
                $discount = ($amount * $coupon->discount_percentage) / 100;
                $amount = $amount - $discount;
                // Guardamos el código del cupón en la sesión
                session(['coupon_code' => $couponCode]);
            }
        }

        if ($paymentMethod === 'stripe') {
            return $this->createStripeCheckoutSession($request, $category, $amount);
        } elseif ($paymentMethod === 'paypal') {
            return $this->subscribeWithPayPal($request, $category, $amount);
        }

        return redirect()->back()->with('error', 'Método de pago no válido.');
    }

    public function createTrialSubscription(Request $request)
    {
        $user = auth()->user();

        if (!$user) {
            return redirect()->route('login')->with('error', 'Debes iniciar sesión para activar una suscripción de prueba.');
        }

        $categoryId = $request->input('category_id');
        $category = Categories::findOrFail($categoryId);

        // Verificar si el usuario ya tiene una suscripción activa para esta categoría
        $existingSubscription = $user->subscriptions()
            ->where('status', 'active')
            ->whereHas('categories', function ($query) use ($categoryId) {
                $query->where('categories.id', $categoryId);
            })
            ->first();

        if ($existingSubscription) {
            return redirect()->back()->with('error', 'Ya tienes una suscripción activa para esta saga.');
        }

        // Crear una nueva suscripción de prueba
        $subscription = Subscription::create([
            'user_id' => $user->id,
            'amount' => 0, // Es gratis
            'start_date' => now(),
            'end_date' => now()->addDays(7), // Prueba de 7 días
            'status' => 'active',
        ]);

        $subscription->categories()->attach($category);

        return redirect()->route('home')->with('success', 'Suscripción de prueba activada por 7 días para ' . $category->name);
    }

    private function createStripeCheckoutSession(Request $request, Categories $category, $amount)
    {
        Stripe::setApiKey(config('services.stripe.secret'));
        $isEnglish = app()->getLocale() != 'fr';

        try {
            $session = Session::create([
                'payment_method_types' => ['card'],
                'line_items' => [
                    [
                        'price_data' => [
                            'currency' => $isEnglish ? 'usd' : 'eur',
                            'product_data' => [
                                'name' => 'Suscripción anual a ' . $category->name,
                                'description' => $request->input('coupon_code') ?
                                    'Incluye descuento por cupón' : null,
                            ],
                            'unit_amount' => (int)($amount * 100),
                        ],
                        'quantity' => 1,
                    ]
                ],
                'mode' => 'payment',
                'success_url' => route('subscription.success') . '?session_id={CHECKOUT_SESSION_ID}&category_id=' . $category->id . '&amount=' . $amount,
                'cancel_url' => route('subscription.cancel'),
            ]);

            return redirect($session->url);
        } catch (\Exception $e) {
            \Log::error('Error de Stripe: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Hubo un problema al procesar el pago con Stripe. Por favor, inténtalo de nuevo.');
        }
    }

    private function subscribeWithPayPal(Request $request, Categories $category, $amount)
    {
        try {
            $provider = PayPal::setProvider();
            $provider->setApiCredentials(config('paypal'));
            $tokenData = $provider->getAccessToken();
            $isEnglish = app()->getLocale() != 'fr';

            $provider->setAccessToken([
                'access_token' => $tokenData['access_token'],
                'token_type' => 'Bearer'
            ]);

            $order = $provider->createOrder([
                'intent' => 'CAPTURE',
                'purchase_units' => [[
                    'amount' => [
                        'currency_code' => $isEnglish ? 'USD' : 'EUR',
                        'value' => number_format($amount, 2, '.', ''),
                    ],
                    'description' => 'Suscripción anual a ' . $category->name .
                        ($request->input('coupon_code') ? ' (Descuento aplicado)' : '')
                ]],
                'application_context' => [
                    'brand_name' => config('app.name'),
                    'locale' => app()->getLocale() == 'fr' ? 'fr-FR' : 'en-US',
                    'landing_page' => 'LOGIN',
                    'shipping_preference' => 'NO_SHIPPING',
                    'user_action' => 'PAY_NOW',
                    'return_url' => route('subscription.success', [
                        'category_id' => $category->id,
                        'amount' => $amount
                    ]),
                    'cancel_url' => route('subscription.cancel')
                ]
            ]);

            if (isset($order['links'][1]['href'])) {
                return redirect($order['links'][1]['href']);
            }

            \Log::error('PayPal Order Response:', $order);
            return redirect()->back()->with(
                'error',
                app()->getLocale() == 'fr' ?
                    'Erreur lors de la création de la commande PayPal.' :
                    'Error creating PayPal order.'
            );
        } catch (\Exception $e) {
            \Log::error('PayPal Error: ' . $e->getMessage());
            return redirect()->back()->with(
                'error',
                app()->getLocale() == 'fr' ?
                    'Un problème est survenu lors du traitement du paiement PayPal. Veuillez réessayer.' :
                    'There was a problem processing the PayPal payment. Please try again.'
            );
        }
    }

    public function success(Request $request)
    {
        $user = auth()->user();
        $categoryId = $request->get('category_id');
        $amount = $request->get('amount');
        $category = Categories::findOrFail($categoryId);

        // Crear la suscripción
        $subscription = Subscription::create([
            'user_id' => $user->id,
            'amount' => $amount,
            'start_date' => now(),
            'end_date' => now()->addYear(),
            'status' => 'active',
        ]);

        $subscription->categories()->attach($category);

        // Marcar el cupón como usado si existe
        if ($couponCode = session('coupon_code')) {
            $coupon = Coupon::where('code', $couponCode)
                ->where('used_count', '<', DB::raw('max_uses'))
                ->first();

            if ($coupon) {
                $coupon->used_count++;
                $coupon->used = ($coupon->used_count >= $coupon->max_uses);
                $coupon->save();
            }

            // Limpiar el código de cupón de la sesión
            session()->forget('coupon_code');
        }

        return redirect()->route('home')->with('success', '¡Suscripción exitosa a ' . $category->name . '!');
    }

    public function cancel()
    {
        return redirect()->route('home')->with('info', 'La suscripción ha sido cancelada.');
    }
}
