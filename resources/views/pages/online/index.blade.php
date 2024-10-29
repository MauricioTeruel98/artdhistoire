@extends('layouts.applayout')

@section('title', "Art d'Histoire | Video ")

@section('header')

    <style>
        .card-img-overlay {
            background: rgba(0, 0, 0, 0.5);
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            padding: 20px;
        }

        .card-title {
            font-size: 1.25rem;
            font-weight: bold;
        }

        .badge {
            font-size: 0.9rem;
            margin-bottom: 10px;
        }

        .card-img {
            height: 250px;
            object-fit: cover;
        }

        .card-img-overlay {
            background: linear-gradient(to bottom, rgba(0, 0, 0, 0.1), rgba(0, 0, 0, 0.7));
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
            padding: 15px;
        }

        .card-title {
            font-size: 2rem;
            font-weight: bold;
            color: white;
            margin-top: auto;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
        }

        .badge {
            font-size: 15px;
            padding: 8px 15px;
            border-radius: 0;
            align-self: flex-end;
            font-weight: bold;
        }

        .card-img {
            height: 300px;
            object-fit: cover;
        }

        .title-overlay {
            height: 80px;
            padding: 20px;
        }

        .baskeville-italic{
            font-size: 28px !important;
            font-weight: normal !important;
        }

        @media (max-width: 768px) {
            .card-img {
                height: 250px;
            }

            .card-title {
                font-size: 1.5rem;
            }
        }

        @media (max-width: 768px) {
            .card-img {
                height: 200px;
            }
        }
    </style>

@endsection

@section('content')

    <div class="container mt-5">
        <div class="row">
            <!-- Tarjeta 1 -->
            @foreach ($videosOnline as $index => $videoOnline)
                <div class="col-md-6 mb-4">
                    <a href="/video-online/{{ $videoOnline->id }}">
                        <div class="card text-white rounded-0 position-relative">
                            <img src="/storage/{{ $videoOnline->home_image }}" class="card-img rounded-0"
                                alt="Le Réalisme au XIXe">
                            <div class="card-img-overlay rounded-0 p-0">
                                <div class="badge badge-danger arial" style="background-color: {{ $videoOnline->color }};">
                                    {{ app()->getLocale() == 'fr' ? 'Conférence n°' : 'Conference n°' }}{{ $index + 1 }}
                                </div>
                                <div class="position-absolute bottom-0 left-0 w-100 title-overlay"
                                    style="background-color: {{ $videoOnline->color }}8a;">
                                    <h5 class="card-title baskeville-italic">
                                        {{ app()->getLocale() == 'fr' ? $videoOnline->title_fr : $videoOnline->title }}</h5>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>

@endsection
