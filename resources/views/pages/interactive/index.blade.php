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

        .btn-primary,
        .btn-info,
        .btn-success {
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

        .img-container {
            width: 100%;
            height: 200px;
            display: flex;
            justify-content: center;
            align-items: center;
            overflow: hidden;
        }
    </style>
@endsection

@section('content')
    <div class="container mt-5">
        <h2 class="text-center mb-4">
            {{ app()->getLocale() == 'fr' ? 'Choisissez une saga pour vous abonner' : 'Choose a saga to subscribe' }}</h2>
        <div class="row justify-content-center">
            @foreach ($categories as $category)
                <div class="col-md-4 mb-4">
                    <div class="card text-center p-4">
                        <h5 class="font-weight-normal">{{ $category->name }}</h5>
                        <div class="img-container">
                            <img src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name }}" class="img-fluid mb-3">
                        </div>
                        {{-- Quiero mostrar un mensaje si el usuario es estudiante, en ambos idiomas --}}
                        @if (Auth::user()->is_student)
                            <p class="mt-3">
                                <strong>
                                    {{ app()->getLocale() == 'fr' ? 'Prix spécial pour les étudiants' : 'Special price for students' }}
                                </strong>
                            </p>
                        @endif
                        <div class="display-4 my-3">€ <strong>
                            {{Auth::user()->is_student ? '19' : '49'}}
                        </strong></div>
                        <p class="mb-3">
                            {{ app()->getLocale() == 'fr' ? 'Accès complet pour un an' : 'Full access for one year' }}</p>
                        <form action="{{ route('subscription.create') }}" method="POST" class="mb-2">
                            @csrf
                            <input type="hidden" name="category_id" value="{{ $category->id }}">
                            <button type="submit" name="payment_method" value="stripe"
                                class="btn btn-primary btn-lg mb-2">{{ app()->getLocale() == 'fr' ? 'Payer avec Stripe' : 'Pay with Stripe' }}</button>
                            <button type="submit" name="payment_method" value="paypal"
                                class="btn btn-info btn-lg mb-2">{{ app()->getLocale() == 'fr' ? 'Payer avec PayPal' : 'Pay with PayPal' }}</button>
                        </form>
                        @auth
                            <form action="{{ route('subscription.trial') }}" method="POST">
                                @csrf
                                <input type="hidden" name="category_id" value="{{ $category->id }}">
                                <button type="submit"
                                    class="btn btn-success btn-lg">{{ app()->getLocale() == 'fr' ? 'Essai gratuit (7 jours)' : 'Free trial (7 days)' }}</button>
                            </form>
                        @else
                            <a href="{{ route('login') }}"
                                class="btn btn-secondary btn-lg">{{ app()->getLocale() == 'fr' ? 'Connectez-vous pour l\'essai gratuit' : 'Login for free trial' }}</a>
                        @endauth
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
