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


        /* Ahora puedes usar la fuente en tu CSS */
        body {
            font-family: 'Futura', sans-serif !important;
            /* Aplica la fuente a todo el cuerpo de la página */
            color: rgb(117, 117, 117) !important;
        }

        .baskeville-italic {
            font-family: 'Baskeville Italic', sans-serif !important;
        }
    </style>

@endsection

@section('content')
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ app()->getLocale() == 'fr' ? 'Abonnement Requis' : 'Subscription Required' }}
                    </div>
                    <div class="card-body">
                        <h2 class="mb-4">{{ app()->getLocale() == 'fr' ? 'Accès Restreint' : 'Access Restricted' }}</h2>
                        <p>{{ app()->getLocale() == 'fr' ? 'Pour accéder à ce contenu, vous avez besoin d\'un abonnement actif.' : 'To access this content, you need an active subscription.' }}
                        </p>
                        <p>{{ app()->getLocale() == 'fr' ? 'Choisissez l\'une de nos options d\'abonnement pour profiter de tout le contenu :' : 'Choose one of our subscription options to enjoy all the content:' }}
                        </p>
                        <div class="mt-4">
                            <a href="{{ route('interactive.index') }}"
                                class="btn btn-primary">{{ app()->getLocale() == 'fr' ? 'Voir les Plans d\'Abonnement' : 'View Subscription Plans' }}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
