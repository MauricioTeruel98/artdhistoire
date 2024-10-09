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
                    <a href="/video-online/{{$videoOnline->id}}">
                        <div class="card text-white rounded-0">
                            <img src="/storage/{{$videoOnline->image}}" class="card-img rounded-0" alt="Le Réalisme au XIXe">
                            <div class="card-img-overlay rounded-0">
                                <div class="badge badge-danger">Conférence n°{{ $index + 1 }}</div>
                                <h5 class="card-title baskeville-italic">{{ $videoOnline->title }}</h5>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>

@endsection
