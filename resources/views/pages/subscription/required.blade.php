@extends('layouts.applayout')

@section('title', "Art d'Histoire | Abonnement Requis")

@section('header')
    <style>
        @font-face {
            font-family: 'Futura';
            src: url('../../fonts/futura-2/Futura\ Book\ font.ttf') format('truetype');
            font-weight: normal;
            font-style: normal;
        }

        @font-face {
            font-family: 'Baskeville Italic';
            src: url('../../fonts/baskeville/LibreBaskerville-Italic.ttf') format('truetype');
            font-weight: normal;
            font-style: normal;
        }

        body {
            font-family: 'Futura', sans-serif !important;
            color: rgb(117, 117, 117) !important;
        }

        .baskeville-italic {
            font-family: 'Baskeville Italic', sans-serif !important;
        }

        .card {
            border: 1px solid #ddd;
            padding: 2rem;
        }

        .display-4 {
            font-size: 2rem;
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

        .btn-outline-primary {
            border: 2px solid #0000FF;
            color: #0000FF;
            padding: 0.5rem 2rem;
            margin: 1rem 0;
        }

        .btn-outline-primary:hover {
            background-color: #0000FF;
            color: white;
        }
    </style>
@endsection

@section('content')
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <h2 class="text-center mb-4">{{ app()->getLocale() == 'fr' ? 'Abonnement Requis' : 'Subscription Required' }}
                </h2>
                <div class="card text-center p-4">
                    <h5 class="font-weight-normal">{{ $category->name }}</h5>
                    <div class="d-flex justify-content-center">
                        <img src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name }}"
                            class="img-fluid mb-3"
                            style="height: 200px;
                                width: 80%;
                                object-fit: cover;">
                    </div>
                    @if (Auth::user()->is_student ?? false)
                        <div class="display-4 my-3">€ <strong>{{ Voyager::setting('site.abono_estudiant') }}</strong></div>
                        <p class="mb-3">
                            {{ app()->getLocale() == 'fr' ? 'Accès complet pour un an' : 'Full access for one year' }}</p>
                        <p class="mb-3">
                            {{ app()->getLocale() == 'fr' ? 'Prix spécial pour les étudiants' : 'Special price for students' }}
                        </p>
                    @else
                        <div class="display-4 my-3">€ <strong>{{ Voyager::setting('site.abono_normal') }}</strong></div>
                        <p class="mb-3">
                            {{ app()->getLocale() == 'fr' ? 'Accès complet pour un an' : 'Full access for one year' }}</p>
                    @endif
                    <form action="{{ route('subscription.create') }}" method="POST" class="mb-2">
                        @csrf
                        <input type="hidden" name="category_id" value="{{ $category->id }}">
                        <button type="submit" name="payment_method" value="stripe" class="btn btn-outline-primary btn-lg mb-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round"
                                class="icon icon-tabler icons-tabler-outline icon-tabler-credit-card">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M3 5m0 3a3 3 0 0 1 3 -3h12a3 3 0 0 1 3 3v8a3 3 0 0 1 -3 3h-12a3 3 0 0 1 -3 -3z" />
                                <path d="M3 10l18 0" />
                                <path d="M7 15l.01 0" />
                                <path d="M11 15l2 0" />
                            </svg>
                            {{ app()->getLocale() == 'fr' ? 'Payer avec une carte de crédit' : 'Pay with credit card' }}
                        </button>
                    </form>
                    @guest
                        <a href="{{ route('login') }}"
                            class="btn btn-secondary btn-lg">{{ app()->getLocale() == 'fr' ? 'Connectez-vous pour continuer' : 'Login to continue' }}</a>
                    @endguest
                </div>
            </div>
        </div>
    </div>
@endsection