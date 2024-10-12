<?php

namespace App\Http\Controllers;

use App\Models\Subscription;
use Illuminate\Http\Request;
use Srmklive\PayPal\Facades\PayPal;
use Stripe\Stripe;
use Stripe\Checkout\Session;

class SubscriptionController extends Controller
{
    public function create(Request $request)
    {
        $paymentMethod = $request->input('payment_method');

        if ($paymentMethod === 'stripe') {
            return $this->createStripeCheckoutSession($request);
        } elseif ($paymentMethod === 'paypal') {
            return $this->subscribeWithPayPal($request);
        }

        return redirect()->back()->with('error', 'Método de pago no válido.');
    }


    public function createTrialSubscription(Request $request)
    {
        $user = $request->user();

        // Verificar si el usuario ya tiene una suscripción activa
        if ($user->subscription && $user->subscription->isActive()) {
            return redirect()->back()->with('error', 'Ya tienes una suscripción activa.');
        }

        // Crear una nueva suscripción de prueba
        Subscription::create([
            'user_id' => $user->id,
            'payment_method' => 'trial',
            'start_date' => now(),
            'end_date' => now()->addDays(7), // Prueba de 7 días
        ]);

        return redirect()->back()->with('success', 'Suscripción de prueba activada por 7 días.');
    }

    private function createStripeCheckoutSession(Request $request)
    {
        // Asegúrate de que la clave API esté configurada correctamente
        Stripe::setApiKey(config('services.stripe.secret'));

        try {
            $session = Session::create([
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price_data' => [
                        'currency' => 'eur',
                        'product_data' => [
                            'name' => 'Suscripción La Saga des impressionnistes',
                        ],
                        'unit_amount' => 4900, // 49 euros en centavos
                    ],
                    'quantity' => 1,
                ]],
                'mode' => 'payment',
                'success_url' => route('subscription.success') . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => route('subscription.cancel'),
            ]);

            return redirect($session->url);
        } catch (\Exception $e) {
            // Registra el error para debugging
            \Log::error('Error de Stripe: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Hubo un problema al procesar el pago con Stripe. Por favor, inténtalo de nuevo.');
        }
    }

    public function subscribeWithStripe(Request $request)
    {
        $user = $request->user();
        $paymentMethod = $request->payment_method;

        $user->createOrGetStripeCustomer();
        $user->addPaymentMethod($paymentMethod);

        $user->newSubscription('default', 'plan-id')->create($paymentMethod);

        // Guardar la suscripción en la base de datos
        Subscription::create([
            'user_id' => $user->id,
            'payment_method' => 'stripe',
            'stripe_subscription_id' => $user->subscription('default')->stripe_id,
            'start_date' => now(),
            'end_date' => now()->addMonth(), // Esto depende de tu plan
        ]);

        return redirect()->route('dashboard')->with('success', 'Suscripción exitosa con Stripe!');
    }

    public function subscribeWithPayPal(Request $request)
    {
        try {
            $provider = PayPal::setProvider();
            $provider->setApiCredentials(config('paypal'));

            $token = $provider->getAccessToken();

            dd($token);

            if (!$token || !isset($token['access_token'])) {
                throw new \Exception('No se pudo obtener el token de acceso de PayPal');
            }

            $provider->setAccessToken($token['access_token']);

            // Configurar los datos de la suscripción
            $subscriptionData = [
                'plan_id' => 'your-paypal-plan-id', // Asegúrate de que este ID de plan sea correcto
                'start_time' => now()->toIso8601String(),
                'subscriber' => [
                    'name' => [
                        'given_name' => $request->user()->name,
                        'surname' => '',
                    ],
                    'email_address' => $request->user()->email,
                ],
                'application_context' => [
                    'brand_name' => 'Tu Marca',
                    'locale' => 'es-ES', // Cambiado a español de España
                    'shipping_preference' => 'NO_SHIPPING',
                    'user_action' => 'SUBSCRIBE_NOW',
                    'return_url' => route('subscription.success'),
                    'cancel_url' => route('subscription.cancel'),
                ],
            ];

            // Crear suscripción en PayPal
            $response = $provider->createSubscription($subscriptionData);

            if (!isset($response['id'])) {
                throw new \Exception('No se pudo crear la suscripción con PayPal.');
            }

            // Guardar el ID de la suscripción de PayPal
            $paypalSubscriptionId = $response['id'];

            // Guardar la suscripción en la base de datos
            Subscription::create([
                'user_id' => $request->user()->id,
                'payment_method' => 'paypal',
                'paypal_subscription_id' => $paypalSubscriptionId,
                'start_date' => now(),
                'end_date' => now()->addMonth(), // Suponiendo que es una suscripción mensual
            ]);

            // Redirigir al usuario a la URL de aprobación de PayPal
            foreach ($response['links'] as $link) {
                if ($link['rel'] === 'approve') {
                    return redirect()->away($link['href']);
                }
            }
        } catch (\Exception $e) {
            \Log::error('Error de PayPal: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Hubo un problema al procesar el pago con PayPal. Por favor, inténtalo de nuevo.');
        }

        return redirect()->route('home')->with('success', 'Suscripción creada. Por favor, confirma en PayPal.');
    }

    public function success(Request $request)
    {
        $sessionId = $request->get('session_id');

        if ($sessionId) {
            // Pago con Stripe
            Stripe::setApiKey(config('services.stripe.secret'));
            $session = Session::retrieve($sessionId);

            if ($session->payment_status === 'paid') {
                $user = auth()->user();
                Subscription::create([
                    'user_id' => $user->id,
                    'payment_method' => 'stripe',
                    'stripe_subscription_id' => $session->subscription,
                    'start_date' => now(),
                    'end_date' => now()->addMonth(),
                ]);

                return redirect()->route('home')->with('success', '¡Suscripción exitosa con Stripe!');
            }
        } else {
            // Pago con PayPal
            $subscription = Subscription::where('user_id', auth()->id())
                ->where('payment_method', 'paypal')
                ->latest()
                ->first();

            if ($subscription) {
                return redirect()->route('home')->with('success', '¡Suscripción exitosa con PayPal!');
            }
        }

        return redirect()->route('home')->with('error', 'No se pudo confirmar la suscripción.');
    }

    public function cancel()
    {
        return redirect()->route('home')->with('info', 'La suscripción ha sido cancelada.');
    }

    public function handleStripeWebhook(Request $request)
    {
        Stripe::setApiKey(config('services.stripe.secret'));
        $endpoint_secret = config('services.stripe.webhook_secret');

        $payload = $request->getContent();
        $sig_header = $request->header('Stripe-Signature');

        try {
            $event = Webhook::constructEvent($payload, $sig_header, $endpoint_secret);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }

        switch ($event->type) {
            case 'customer.subscription.created':
            case 'customer.subscription.updated':
            case 'customer.subscription.deleted':
                $subscription = $event->data->object;
                $this->updateSubscriptionStatus($subscription);
                break;
        }

        return response()->json(['status' => 'success']);
    }

    private function updateSubscriptionStatus($stripeSubscription)
    {
        $subscription = Subscription::where('stripe_subscription_id', $stripeSubscription->id)->first();

        if ($subscription) {
            $subscription->status = $stripeSubscription->status;
            $subscription->end_date = \Carbon\Carbon::createFromTimestamp($stripeSubscription->current_period_end);
            $subscription->save();
        }
    }

    public function handlePayPalWebhook(Request $request)
    {
        $provider = PayPal::setProvider();
        $provider->setApiCredentials(config('paypal'));

        $payload = json_decode($request->getContent(), true);

        try {
            if ($provider->verifyWebHook($payload)) {
                $event = $payload['event_type'];
                $resource = $payload['resource'];

                switch ($event) {
                    case 'BILLING.SUBSCRIPTION.CREATED':
                    case 'BILLING.SUBSCRIPTION.UPDATED':
                    case 'BILLING.SUBSCRIPTION.CANCELLED':
                        $this->updatePayPalSubscriptionStatus($resource);
                        break;
                }
            }
        } catch (\Exception $e) {
            \Log::error('Error en PayPal Webhook: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 400);
        }

        return response()->json(['status' => 'success']);
    }

    private function updatePayPalSubscriptionStatus($paypalSubscription)
    {
        $subscription = Subscription::where('paypal_subscription_id', $paypalSubscription['id'])->first();

        if ($subscription) {
            $subscription->status = $paypalSubscription['status'];
            $subscription->end_date = \Carbon\Carbon::parse($paypalSubscription['billing_info']['next_billing_time']);
            $subscription->save();
        }
    }
}
