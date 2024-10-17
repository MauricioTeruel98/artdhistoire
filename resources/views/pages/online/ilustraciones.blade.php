@extends('layouts.applayout')

@section('title', "Art d'Histoire | Video ")

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

        h1{
            text-align: center;
            color: {{ $videoOnline->color }};
        }
</style>

@endsection

@section('content')

    <div class="container">
        <div class="container mt-4">
            <h1 class="baskeville-italic">
                {{ app()->getLocale() == 'fr' ? $videoOnline->title_fr : $videoOnline->title }}</h1>
            <h2 class="mb-5">Liste des illustrations</h2>

            {!! $videoOnline->ilustraciones !!}
        </div>
    </div>

@endsection
