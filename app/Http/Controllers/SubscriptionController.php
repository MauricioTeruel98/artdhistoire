<?php

namespace App\Http\Controllers;

use App\Models\Categories;
use App\Models\Subscription;
use Illuminate\Http\Request;
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

        // Si es estudiante y tiene certificado subido pero no validado
        if ($user->is_student && !$user->validated_student && $user->certificate) {
            return redirect()->route('certificate.pending');
        }

        // Si es estudiante pero no ha subido certificado
        if ($user->is_student && !$user->validated_student && !$user->certificate) {
            return redirect()->route('certificate.upload', ['category_id' => $category->id]);
        }

        $paymentMethod = $request->input('payment_method');
        $amount = $user->is_student && $user->validated_student ? Voyager::setting('site.abono_estudiant') : Voyager::setting('site.abono_normal');

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

        try {
            $session = Session::create([
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price_data' => [
                        'currency' => 'eur',
                        'product_data' => [
                            'name' => 'Suscripción anual a ' . $category->name,
                        ],
                        'unit_amount' => $amount * 100,
                    ],
                    'quantity' => 1,
                ]],
                'mode' => 'payment',
                'success_url' => route('subscription.success') . '?session_id={CHECKOUT_SESSION_ID}&category_id=' . $category->id,
                'cancel_url' => route('subscription.cancel'),
            ]);

            return redirect($session->url);
        } catch (\Exception $e) {
            \Log::error('Error de Stripe: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Hubo un problema al procesar el pago con Stripe. Por favor, inténtalo de nuevo.');
        }
    }

    public function subscribeWithPayPal(Request $request, Categories $category, $amount)
    {
        try {
            $provider = PayPal::setProvider();
            $provider->setApiCredentials(config('paypal'));
            $token = $provider->getAccessToken();
            $provider->setAccessToken($token['access_token']);

            $order = $provider->createOrder([
                'intent' => 'CAPTURE',
                'purchase_units' => [
                    [
                        'amount' => [
                            'currency_code' => 'EUR',
                            'value' => $amount
                        ],
                        'description' => 'Suscripción anual a ' . $category->name,
                    ]
                ],
                'application_context' => [
                    'return_url' => route('subscription.success') . '?category_id=' . $category->id,
                    'cancel_url' => route('subscription.cancel'),
                ]
            ]);

            return redirect($order['links'][1]['href']);
        } catch (\Exception $e) {
            \Log::error('Error de PayPal: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Hubo un problema al procesar el pago con PayPal. Por favor, inténtalo de nuevo.');
        }
    }
    public function success(Request $request)
    {
        $user = auth()->user();
        $categoryId = $request->get('category_id');
        $category = Categories::findOrFail($categoryId);

        $subscription = Subscription::create([
            'user_id' => $user->id,
            'amount' => 49.00,
            'start_date' => now(),
            'end_date' => now()->addYear(),
            'status' => 'active',
        ]);

        $subscription->categories()->attach($category);

        return redirect()->route('home')->with('success', '¡Suscripción exitosa a ' . $category->name . '!');
    }

    public function cancel()
    {
        return redirect()->route('home')->with('info', 'La suscripción ha sido cancelada.');
    }
}
