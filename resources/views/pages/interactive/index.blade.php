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

        .price-tag {
            font-size: 2rem;
            margin: 1.5rem 0;
        }

        .price-tag .display-4 {
            font-size: 3.5rem;
            font-weight: bold;
        }

        .small {
            font-size: 0.9rem;
            line-height: 1.4;
            padding: 0 1rem;
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

        .highlighted-plan {
            border: 3px solid #0000FF;
            box-shadow: 0 0 15px rgba(0, 0, 255, 0.1);
            transform: scale(1.02);
            transition: all 0.3s ease;
        }

        .recommended-badge {
            background-color: #0000FF;
            color: white;
            padding: 0.5rem;
            position: absolute;
            top: 0;
            right: 0;
            border-bottom-left-radius: 8px;
            font-size: 0.9rem;
        }

        .card-abono {
            position: relative;
            transition: all 0.3s ease;
        }

        .card-abono:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
    </style>
@endsection

@section('content')
    <div class="container mt-5">
        <h2 class="text-center mb-4">{{ app()->getLocale() == 'fr' ? 'Nos Formules' : 'Our Plans' }}</h2>

        <div class="row justify-content-center mb-5">
            {{-- Plan Particulier --}}
            <div class="col-md-4 mb-4">
                <div class="card text-center">
                    <h5>{{ app()->getLocale() == 'fr' ? 'Vous êtes un particulier ?' : 'Are you an individual?' }}</h5>
                    <div class="price-tag">€ <span class="display-4">49</span></div>
                    <p class="small">
                        {{ app()->getLocale() == 'fr' ? 'Tarif par conférence, pour l\'accès aux 4 vidéos interactives accompagnées de leur corpus de texte' : 'Price per conference, for access to 4 interactive videos with their text corpus' }}
                    </p>
                </div>
            </div>

            {{-- Plan Estudiante --}}
            <div class="col-md-4 mb-4">
                <div class="card text-center">
                    <h5>{{ app()->getLocale() == 'fr' ? 'Vous êtes étudiants ?' : 'Are you a student?' }}</h5>
                    <div class="price-tag">€ <span class="display-4">19</span></div>
                    <p class="small">
                        {{ app()->getLocale() == 'fr' ? 'Tarif par conférence, pour l\'accès aux 4 vidéos interactives accompagnées de leur corpus de texte' : 'Price per conference, for access to 4 interactive videos with their text corpus' }}
                    </p>
                    <p class="text-muted small">*
                        {{ app()->getLocale() == 'fr' ? 'Sous présentation de justificatif' : 'With proof of student status' }}
                    </p>
                </div>
            </div>

            {{-- Plan Empresa/Socio --}}
            <div class="col-md-4 mb-4">
                <div class="card text-center">
                    <h5>{{ app()->getLocale() == 'fr' ? 'Vous êtes partenaire ou souhaitez devenir partenaire ?' : 'Are you a partner or wish to become a partner?' }}
                    </h5>
                    <p>{{ app()->getLocale() == 'fr' ? 'Vous êtes étudiant, indépendant ou entreprise' : 'You are a student, independent or company' }}
                    </p>
                    <a href="/about" class="btn btn-outline-primary">
                        {{ app()->getLocale() == 'fr' ? 'Contactez - nous' : 'Contact us' }}
                    </a>
                </div>
            </div>
        </div>
        <h2 class="text-center mb-4">
            {{ app()->getLocale() == 'fr' ? 'Choisissez une saga pour vous abonner' : 'Choose a saga to subscribe' }}</h2>
        <div class="row justify-content-center">
            @foreach ($categories as $category)
                <div class="col-md-4 mb-4">
                    <div class="card text-center p-4">
                        <h5 class="font-weight-normal">{{ $category->name }}</h5>
                        <div class="img-container">
                            <img src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name }}"
                                class="img-fluid mb-3">
                        </div>
                        {{-- Quiero mostrar un mensaje si el usuario es estudiante, en ambos idiomas --}}
                        @if (Auth::user()->is_student ?? false)
                            <p class="mt-3">
                                <strong>
                                    {{ app()->getLocale() == 'fr' ? 'Prix spécial pour les étudiants' : 'Special price for students' }}
                                </strong>
                            </p>
                            <div class="display-4 my-3">€ <strong>19</strong></div>
                        @else
                            <div class="display-4 my-3">€ <strong>49</strong></div>
                        @endif
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

        <div class="row justify-content-center mt-5">
            {{-- Plan Regular --}}
            <div class="col-md-6 mb-4">
                <div class="card card-abono text-center {{ !Auth::user()?->is_student ? 'highlighted-plan' : '' }}">
                    <h5>{{ app()->getLocale() == 'fr' ? 'Adhésion à une conférence' : 'Conference Subscription' }}</h5>
                    <div class="price-tag">€ <span class="display-4">49</span></div>
                    <p class="small">
                        {{ app()->getLocale() == 'fr' ? 'Accès complet aux vidéos de la conférence et librairies interactives' : 'Full access to conference videos and interactive libraries' }}
                    </p>
                    <a href="#" class="btn btn-primary mb-4">
                        {{ app()->getLocale() == 'fr' ? 'Sélectionner' : 'Select' }}
                    </a>
                    <p class="small text-muted">
                        {{ app()->getLocale() == 'fr' ? '4 vidéos interactives, 100 articles, 1000 liens sourcés' : '4 interactive videos, 100 articles, 1000 sourced links' }}
                    </p>
                    @if(!Auth::user()?->is_student)
                        <div class="recommended-badge">
                            {{ app()->getLocale() == 'fr' ? 'Plan recommandé pour vous' : 'Recommended plan for you' }}
                        </div>
                    @endif
                </div>
            </div>
        
            {{-- Plan Estudiante --}}
            <div class="col-md-6 mb-4">
                <div class="card card-abono text-center {{ Auth::user()?->is_student ? 'highlighted-plan' : '' }}">
                    <h5>{{ app()->getLocale() == 'fr' ? 'Adhésion à une conférence, étudiant' : 'Conference Subscription, student' }}</h5>
                    <div class="price-tag">€ <span class="display-4">19</span></div>
                    <p class="small">
                        {{ app()->getLocale() == 'fr' ? 'Accès complet aux vidéos et librairies interactives' : 'Full access to videos and interactive libraries' }}
                    </p>
                    <a href="#" class="btn btn-primary mb-4">
                        {{ app()->getLocale() == 'fr' ? 'Sélectionner' : 'Select' }}
                    </a>
                    <p class="small text-muted">
                        {{ app()->getLocale() == 'fr' ? 'Tarif étudiant sous présentation de justificatif' : 'Student rate with proof of status' }}
                    </p>
                    @if(Auth::user()?->is_student)
                        <div class="recommended-badge">
                            {{ app()->getLocale() == 'fr' ? 'Plan recommandé pour vous' : 'Recommended plan for you' }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
