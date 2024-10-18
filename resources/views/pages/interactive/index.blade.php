@extends('layouts.applayout')

@section('title', "Art d'Histoire | Videos ")

@section('header')
    <style>
        .card {
            border: 1px solid #ddd;
            padding: 2rem;
        }

        .display-4 {
            font-size: 3rem;
            font-weight: bold;
        }

        .btn-primary, .btn-info, .btn-success {
            border: none;
            font-size: 1.2rem;
            padding: 0.8rem 2rem;
        }

        .btn-primary {
            background-color: #0000FF;
        }

        .btn-primary:hover {
            background-color: #0000cc;
        }

        .btn-success {
            background-color: #28a745;
        }

        .btn-success:hover {
            background-color: #218838;
        }

        .text-muted {
            font-size: 0.9rem;
        }
    </style>
@endsection

@section('content')
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card text-center p-4">
                    <h5 class="font-weight-normal">{{ app()->getLocale() == 'fr' ? 'Adhésion La Saga des impressionnistes' : 'Subscription to La Saga des impressionnistes' }}</h5>
                    <div class="display-4 my-3">€ <strong>49</strong></div>
                    <p class="mb-3">{{ app()->getLocale() == 'fr' ? 'Accès complet aux vidéos et librairies interactives' : 'Complete access to interactive videos and libraries' }}</p>
                    <form action="{{ route('subscription.create') }}" method="POST">
                        @csrf
                        <button type="submit" name="payment_method" value="stripe" class="btn btn-primary btn-lg mb-2">{{ app()->getLocale() == 'fr' ? 'Pagar con Stripe' : 'Pay with Stripe' }}</button>
                        <button type="submit" name="payment_method" value="paypal" class="btn btn-info btn-lg mb-2">{{ app()->getLocale() == 'fr' ? 'Pagar con PayPal' : 'Pay with PayPal' }}</button>
                    </form>
                    <form action="{{ route('subscription.trial') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-success btn-lg mb-4">{{ app()->getLocale() == 'fr' ? 'Activar suscripción de prueba (7 días)' : 'Activate trial subscription (7 days)' }}</button>
                    </form>
                    <hr>
                    <p class="text-muted">{{ app()->getLocale() == 'fr' ? '4 vidéos interactives, 100 articles, 1000 liens sourcés' : '4 interactive videos, 100 articles, 1000 cited links' }}</p>
                </div>
            </div>
        </div>
    </div>
@endsection