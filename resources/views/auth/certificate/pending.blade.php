@extends('layouts.applayout')

@section('title', "Art d'Histoire | Certificate in Validation")

@section('header')
<style>
    .pending-container {
        background-color: #fff;
        padding: 3rem;
        border-radius: 10px;
        box-shadow: 0 0 20px rgba(0,0,0,0.1);
        margin-top: 2rem;
        text-align: center;
    }

    .pending-icon {
        color: #322668;
        font-size: 4rem;
        margin-bottom: 2rem;
    }

    .pending-title {
        color: #322668;
        font-size: 2rem;
        margin-bottom: 1rem;
    }

    .pending-message {
        color: #757575;
        font-size: 1.1rem;
        margin-bottom: 2rem;
    }

    .home-button {
        background-color: #322668;
        color: white;
        padding: 0.8rem 2rem;
        border-radius: 50px;
        text-decoration: none;
        transition: all 0.3s ease;
    }

    .home-button:hover {
        background-color: #3F7652;
        color: white;
        text-decoration: none;
    }
</style>
@endsection

@section('content')
<div class="container pending-container">
    <div class="pending-icon">
        <i class="fas fa-clock"></i>
    </div>
    <h2 class="pending-title">
        {{ app()->getLocale() == 'fr' ? 'Certificate in Validation' : 'Certificate in Validation' }}
    </h2>
    <p class="pending-message">
        {{ app()->getLocale() == 'fr' ? 'Your certificate is being validated. You will soon receive access to purchase the content at student rate.' : 'Your certificate is being validated. You will soon receive access to purchase the content at student rate.' }}
    </p>
    <a href="{{ route('home') }}" class="home-button">
        {{ app()->getLocale() == 'fr' ? 'Back to Home' : 'Back to Home' }}
    </a>
</div>
@endsection