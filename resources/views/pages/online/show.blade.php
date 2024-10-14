@extends('layouts.applayout')

@section('title', "Art d'Histoire | Video ")

@section('header')

    <style>
        .header {
            background-color: #fff;
            border-right: 3px solid {{ $videoOnline->color }};
            padding: 20px;
        }

        h1,
        h2 {
            color: {{ $videoOnline->color }};
        }

        .btn-custom {
            background-color: {{ $videoOnline->color }};
            color: white;
        }

        .video-section {
            background-color: {{ $videoOnline->color }};
            color: white;
            padding: 10px;
            margin-bottom: 20px;
        }

        .video-container {
            position: relative;
            width: 100%;
        }

        .video-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
        }

        .monde-label {
            position: absolute;
            background-color: rgba(255, 255, 255, 0.7);
            padding: 2px 5px;
            border-radius: 3px;
            font-size: 0.8rem;
        }

        .play-button {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: rgba(255, 255, 255, 0.7);
            border-radius: 50%;
            width: 60px;
            height: 60px;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 2rem;
        }

        .video-title {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background-color: {{ $videoOnline->color }};
            color: white;
            padding: 5px 10px;
        }

        .video-container-iframe {
            position: relative;
            width: 100%;
            /*padding-top: 177.78%;   */
            /* 9:16 Aspect Ratio (16 / 9 = 1.7778) */

            padding-top: 56.25%;
            /* 16:9 Aspect Ratio (9 / 16 = 0.5625) */
            overflow: hidden;
        }

        .video-container-iframe iframe {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
        }
    </style>

@endsection

@section('content')

    <div class="container">
        <div class="container mt-4">
            <div class="row">
                <div class="col-md-6 header text-end">
                    <h1 class="baskeville-italic">{{ app()->getLocale() == 'fr' ? $videoOnline->title_fr : $videoOnline->title }}</h1>
                    <h2 class="h5 baskeville-italic">{{ app()->getLocale() == 'fr' ? $videoOnline->subtitle_fr : $videoOnline->subtitle }}</h2>
                </div>
                <div class="col-md-6 text-start">
                    {!! app()->getLocale() == 'fr' ? $videoOnline->text_fr : $videoOnline->text !!}
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-12">
                    {!! app()->getLocale() == 'fr' ? $videoOnline->texto_secondary_fr : $videoOnline->texto_secondary !!}
                </div>
            </div>

            @php
                $sortedVideos = collect($videoOnline->videos)->sortBy('order');
            @endphp

            @foreach ($sortedVideos as $video)
                <div class="row mt-4">
                    <div class="row">
                        <div class="col-md-6">
                            @if ($video['iframe'])
                                @if (Str::contains(strtolower($video['iframe']), '<iframe'))
                                    <div class="video-container-iframe">
                                        {!! $video['iframe'] !!}
                                        <div class="video-title">{{ app()->getLocale() == 'fr' ? $video['title_fr'] : $video['title'] }}</div>
                                    </div>
                                @else
                                    <a href="{{ $video['iframe'] }}" target="_blank">
                                        <div class="video-container">
                                            <img src="{{ $video['imagen'] }}" alt="Vidéo éducative" class="w-100">
                                            <div class="video-overlay">
                                                <div class="play-button text-white">▶</div>
                                                <div class="video-title">{{ app()->getLocale() == 'fr' ? $video['title_fr'] : $video['title'] }}</div>
                                            </div>
                                        </div>
                                    </a>
                                @endif
                            @elseif ($video['video'])
                                <div class="video-container">
                                    <video controls class="w-100">
                                        <source src="{{ $video['video'] }}" type="video/mp4"> {{ app()->getLocale() == 'fr' ? 'Tu navegador no soporta el elemento de video.' : 'Your browser does not support the video element.' }}
                                    </video>
                                    <div class="video-title">{{ app()->getLocale() == 'fr' ? $video['title_fr'] : $video['title'] }}</div>
                                </div>
                            @endif
                        </div>
                        <div class="col-md-6">
                            {!! app()->getLocale() == 'fr' ? $video['text_fr'] : $video['text'] !!}
                        </div>
                    </div>
                </div>
            @endforeach
            <div class="row align-items-center mt-5">
                <div class="col-md-3">
                    @if ($previousVideo)
                        <a href="{{ route('video.show', $previousVideo->id) }}" class="text-decoration-none text-dark">
                            <div class="d-flex align-items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="me-2">
                                    <polyline points="15 18 9 12 15 6"></polyline>
                                </svg>
                                <span>{{ app()->getLocale() == 'fr' ? $previousVideo->title_fr : $previousVideo->title }}</span>
                            </div>
                        </a>
                    @endif
                </div>
                <div class="col-md-2 text-center">
                    <a href="" class="btn btn-outline-secondary btn-sm">{{ app()->getLocale() == 'fr' ? 'Bibliographie' : 'Bibliography' }}</a>
                </div>
                <div class="col-md-2 text-center">
                    <a href="" class="btn btn-outline-secondary btn-sm">{{ app()->getLocale() == 'fr' ? 'Liste des illustrations' : 'List of illustrations' }}</a>
                </div>
                <div class="col-md-3 text-end">
                    @if ($nextVideo)
                        <a href="{{ route('video.show', $nextVideo->id) }}" class="text-decoration-none text-dark">
                            <div class="d-flex align-items-center justify-content-end">
                                <span>{{ app()->getLocale() == 'fr' ? $nextVideo->title_fr : $nextVideo->title }}</span>
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="ms-2">
                                    <polyline points="9 18 15 12 9 6"></polyline>
                                </svg>
                            </div>
                        </a>
                    @endif
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-12 text-center">
                    <h2 class="mb-3">{{ app()->getLocale() == 'fr' ? $videoOnline->title_fr : $videoOnline->title }}</h2>
                    <a href="{{ route('video.show', $videoOnline->id) }}" class="btn btn-primary">{{ app()->getLocale() == 'fr' ? 'découvrir' : 'discover' }}</a>
                </div>
            </div>
        </div>
    </div>

@endsection
