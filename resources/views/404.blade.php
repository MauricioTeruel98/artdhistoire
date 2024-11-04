@extends('layouts.applayout')

@section('title', "Art d'Histoire | Error 404")

@section('header')
<style>
    .error-container {
        min-height: 80vh;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        text-align: center;
        padding: 20px;
    }

    .error-code {
        font-size: 120px;
        font-weight: bold;
        color: #322668;
        margin-bottom: 20px;
        font-family: 'Playfair Display', serif;
    }

    .error-message {
        font-size: 24px;
        color: #666;
        margin-bottom: 30px;
        font-family: 'Futura Light', sans-serif;
    }

    .error-description {
        font-size: 18px;
        color: #757575;
        margin-bottom: 40px;
        max-width: 600px;
        line-height: 1.6;
        font-family: 'Futura Light', sans-serif;
    }

    .back-button {
        display: inline-block;
        padding: 15px 40px;
        background-color: #322668;
        color: white;
        text-decoration: none;
        border-radius: 50px;
        transition: background-color 0.3s ease;
        font-family: 'Futura', sans-serif;
        letter-spacing: 2px;
    }

    .back-button:hover {
        background-color: #3F7652;
        color: white;
    }
</style>
@endsection

@section('content')
<div class="error-container">
    <div class="error-code">404</div>
    <h1 class="error-message">
        {{ app()->getLocale() == 'fr' ? 'Page non trouvée' : 'Page Not Found' }}
    </h1>
    <p class="error-description">
        {{ app()->getLocale() == 'fr' 
            ? "Désolé, la page que vous recherchez n'existe pas ou a été déplacée. Veuillez vérifier l'URL ou retourner à la page d'accueil."
            : "Sorry, the page you are looking for doesn't exist or has been moved. Please check the URL or return to the homepage." 
        }}
    </p>
    <a href="{{ route('home') }}" class="back-button">
        {{ app()->getLocale() == 'fr' ? 'Retour à l\'accueil' : 'Back to Home' }}
    </a>
</div>
@endsection