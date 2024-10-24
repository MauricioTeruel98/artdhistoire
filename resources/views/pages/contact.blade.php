@extends('layouts.applayout')

@section('title', "Art d'Histoire | About ")

@section('header')

    <style>
        h1 {
            color: #2d2654;
            font-size: 24px;
            margin-bottom: 0;
        }

        h2 {
            color: #4a4a4a;
            font-size: 18px;
            font-weight: normal;
            margin-top: 5px;
        }

        .subtitle {
            color: #6a6a6a;
            font-style: italic;
        }

        .btn-custom {
            background-color: #2d2654;
            color: white;
            border: none;
            padding: 10px 20px;
        }

        .btn-custom:hover {
            background-color: #3a3169;
            color: white;
        }

        .form-control {
            margin-bottom: 10px;
        }

        .highlight {
            color: #2d2654;
            font-weight: bold;
        }

        h1{
            font-weight: bold;
        }

        .green-font{
            color: #204007 !important;
            font-weight: bold;
        }
    </style>

@endsection

@section('content')
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-6">
                {!! app()->getLocale() == 'fr' ? $textos->texto_about_first : $textos->texto_about_first_en !!}

                <h3 class="mt-5 mb-3">{{ app()->getLocale() == 'fr' ? 'Nous contacter' : 'Contact us' }}</h3>
                <form>
                    <input type="text" class="form-control" placeholder="{{ app()->getLocale() == 'fr' ? 'Nom *' : 'Name *' }}" required>
                    <input type="email" class="form-control" placeholder="Email *" required>
                    <textarea class="form-control" rows="3" placeholder="{{ app()->getLocale() == 'fr' ? 'Message' : 'Message' }}"></textarea>
                    <button type="submit" class="btn btn-outline-secondary">{{ app()->getLocale() == 'fr' ? 'Envoyer' : 'Send' }}</button>
                </form>
            </div>

            <div class="col-md-6">
                {!! app()->getLocale() == 'fr' ? $textos->texto_about_second : $textos->texto_about_second_en !!}
            </div>
        </div>
    </div>
@endsection
