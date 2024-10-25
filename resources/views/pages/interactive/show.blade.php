@extends('layouts.applayout')

@section('title', "Art d'Histoire | Video ")

@section('header')

    <style>
        .section-title {
            font-size: 1.5rem;
            margin-bottom: 1rem;
        }

        .image-container {
            position: relative;
            margin-bottom: 1rem;
        }

        .play-button {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 60px;
            height: 60px;
            background-color: rgba(255, 255, 255, 0.7);
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .play-button::after {
            content: "";
            border-left: 20px solid #000;
            border-top: 15px solid transparent;
            border-bottom: 15px solid transparent;
            margin-left: 5px;
        }

        .topic-item {
            margin-bottom: 0rem;
        }

        .topic-item span {
            font-size: 12px;
        }

        .topic-icon {
            width: 20px;
            height: 20px;
            display: inline-block;
            margin-right: 0.5rem;
        }

        .topic-list {
            list-style-type: none;
            padding-left: 0;
            display: flex;
            flex-direction: column;
        }

        .topic {
            width: 100%;
            padding: 5px 0;
            font-size: 14px !important;
        }

        iframe .pushfooter {
            height: 0px !important;
        }

        .topic-list {
            list-style-type: none;
            padding-left: 0;
        }

        .topic-item span {
            font-size: 12px;
        }

        .topic {
            padding: 5px 0;
            font-size: 14px;
        }

        @media screen and (max-width: 768px) {
            .topic {
                width: 100%;
            }
        }
    </style>

@endsection

@section('content')
    @include('partials.slider')
    <div class="container mt-4">
        <h1 class="mb-4">
            {{ app()->getLocale() == 'fr' ? $interactive->name_fr : $interactive->name }}
        </h1>

        @foreach ($interactive->posts as $index => $post)
            <section class="my-5">
                <h2>{{ $index + 1 }}. {{ app()->getLocale() == 'fr' ? $post->title_fr : $post->title }}</h2>
                <h3 class="section-title">{{ app()->getLocale() == 'fr' ? $post->excerpt_fr : $post->excerpt }}</h3>
                <div class="row">
                    <div class="col-md-5">
                        <div class="image-container">
                            <div class="video-wrapper" data-video-url="{{ app()->getLocale() == 'fr' ? $post->hihaho : $post->hihaho_en }}">
                                <img src="/storage/{{ $post->image }}" alt="Imagen de portada" class="cover-image w-100 h-100">
                                <div class="play-button"></div>
                                <div class="iframe-container" style="display: none;">
                                    <iframe src="" frameborder="0" webkitallowfullscreen="true" mozallowfullscreen="true"
                                        allowfullscreen="true" allow="autoplay; fullscreen; clipboard-read; clipboard-write"
                                        style="position: absolute !important; top: 0px !important; left: 0px !important; width: 100% !important; height: 100% !important;"></iframe>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-7">
                        @if (app()->getLocale() == 'fr')
                            <div class="d-flex flex-wrap topic-list">
                                @php
                                    $archives = DB::table('archives')
                                        ->where('post_id', $post->id)
                                        ->orderBy('order', 'ASC')
                                        ->get();
                                @endphp

                                <div class="row">
                                    @foreach ($archives as $archive_index => $archive)
                                        @if ($archive_index % 10 == 0)
                                            {{-- Nueva columna cada 10 ítems --}}
                                            <div class="col-md-4">
                                        @endif

                                        <div class="topic">
                                            <div class="topic-item">
                                                <a href="{{ route('interactive.pdf', ['id' => $archive->id]) }}">
                                                    @if ($archive->type == 'nonDisponible')
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                            height="24" viewBox="0 0 24 24" fill="currentColor"
                                                            style="color: grey;"
                                                            class="icon icon-tabler icons-tabler-filled icon-tabler-file">
                                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                            <path
                                                                d="M12 2l.117 .007a1 1 0 0 1 .876 .876l.007 .117v4l.005 .15a2 2 0 0 0 1.838 1.844l.157 .006h4l.117 .007a1 1 0 0 1 .876 .876l.007 .117v9a3 3 0 0 1 -2.824 2.995l-.176 .005h-10a3 3 0 0 1 -2.995 -2.824l-.005 -.176v-14a3 3 0 0 1 2.824 -2.995l.176 -.005h5z" />
                                                            <path d="M19 7h-4l-.001 -4.001z" />
                                                        </svg>
                                                    @elseif ($archive->type == 'contexto')
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                            height="24" viewBox="0 0 24 24" fill="none"
                                                            stroke="currentColor" stroke-width="2" style="color: #5471f1;"
                                                            stroke-linecap="round" stroke-linejoin="round"
                                                            class="icon icon-tabler icons-tabler-outline icon-tabler-photo">
                                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                            <path d="M15 8h.01" />
                                                            <path
                                                                d="M3 6a3 3 0 0 1 3 -3h12a3 3 0 0 1 3 3v12a3 3 0 0 1 -3 3h-12a3 3 0 0 1 -3 -3v-12z" />
                                                            <path d="M3 16l5 -5c.928 -.893 2.072 -.893 3 0l5 5" />
                                                            <path d="M14 14l1 -1c.928 -.893 2.072 -.893 3 0l3 3" />
                                                        </svg>
                                                    @elseif($archive->type == 'teoria')
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                            height="24" viewBox="0 0 24 24" fill="none"
                                                            stroke="currentColor" stroke-width="2" style="color: #ffb102;"
                                                            stroke-linecap="round" stroke-linejoin="round"
                                                            class="icon icon-tabler icons-tabler-outline icon-tabler-flask">
                                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                            <path d="M9 3l6 0" />
                                                            <path d="M10 9l4 0" />
                                                            <path
                                                                d="M10 3v6l-4 11a.7 .7 0 0 0 .5 1h11a.7 .7 0 0 0 .5 -1l-4 -11v-6" />
                                                        </svg>
                                                    @elseif ($archive->type == 'bio')
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                            height="24" viewBox="0 0 24 24" fill="none"
                                                            stroke="currentColor" stroke-width="2" style="color: #ee1c6b;"
                                                            stroke-linecap="round" stroke-linejoin="round"
                                                            class="icon icon-tabler icons-tabler-outline icon-tabler-pencil">
                                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                            <path
                                                                d="M4 20h4l10.5 -10.5a2.828 2.828 0 1 0 -4 -4l-10.5 10.5v4" />
                                                            <path d="M13.5 6.5l4 4" />
                                                        </svg>
                                                    @elseif ($archive->type == 'social')
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                            height="24" style="color: #4bc538;" viewBox="0 0 24 24"
                                                            fill="none" stroke="currentColor" stroke-width="2"
                                                            stroke-linecap="round" stroke-linejoin="round"
                                                            class="icon icon-tabler icons-tabler-outline icon-tabler-world">
                                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                            <path d="M3 12a9 9 0 1 0 18 0a9 9 0 0 0 -18 0" />
                                                            <path d="M3.6 9h16.8" />
                                                            <path d="M3.6 15h16.8" />
                                                            <path d="M11.5 3a17 17 0 0 0 0 18" />
                                                            <path d="M12.5 3a17 17 0 0 1 0 18" />
                                                        </svg>
                                                    @endif
                                                </a>
                                                <span>{{ $archive->title }}</span>
                                            </div>
                                        </div>

                                        @if ($archive_index % 10 == 9 || $archive_index == $archives->count() - 1)
                                            {{-- Cierra la columna al llegar a 10 o al final de la lista --}}
                                </div>
                        @endif

                        @if ($archive_index % 30 == 29 && $archive_index != $archives->count() - 1)
                            {{-- Nueva fila cada 30 ítems --}}
                    </div>
                    <div class="row">
        @endif
        @endforeach
    </div>
    </div>
@else
    <div class="d-flex flex-wrap topic-list">
        @php
            $archivesEn = DB::table('archives_en')
                ->where('post_id', $post->id)
                ->orderBy('order', 'ASC')
                ->get();
        @endphp

        @foreach ($archivesEn as $archive_index => $archive)
            @if ($archive_index % 10 == 0)
                {{-- Nueva columna cada 10 ítems --}}
                <div class="col-md-4">
            @endif

            <div class="topic">
                <div class="topic-item">
                    <a href="{{ route('interactive.pdf', ['id' => $archive->id]) }}">
                        @if ($archive->type == 'nonDisponible')
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="currentColor" style="color: grey;"
                                class="icon icon-tabler icons-tabler-filled icon-tabler-file">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path
                                    d="M12 2l.117 .007a1 1 0 0 1 .876 .876l.007 .117v4l.005 .15a2 2 0 0 0 1.838 1.844l.157 .006h4l.117 .007a1 1 0 0 1 .876 .876l.007 .117v9a3 3 0 0 1 -2.824 2.995l-.176 .005h-10a3 3 0 0 1 -2.995 -2.824l-.005 -.176v-14a3 3 0 0 1 2.824 -2.995l.176 -.005h5z" />
                                <path d="M19 7h-4l-.001 -4.001z" />
                            </svg>
                        @elseif ($archive->type == 'contexto')
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" style="color: #5471f1;"
                                stroke-linecap="round" stroke-linejoin="round"
                                class="icon icon-tabler icons-tabler-outline icon-tabler-photo">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M15 8h.01" />
                                <path d="M3 6a3 3 0 0 1 3 -3h12a3 3 0 0 1 3 3v12a3 3 0 0 1 -3 3h-12a3 3 0 0 1 -3 -3v-12z" />
                                <path d="M3 16l5 -5c.928 -.893 2.072 -.893 3 0l5 5" />
                                <path d="M14 14l1 -1c.928 -.893 2.072 -.893 3 0l3 3" />
                            </svg>
                        @elseif($archive->type == 'teoria')
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" style="color: #ffb102;"
                                stroke-linecap="round" stroke-linejoin="round"
                                class="icon icon-tabler icons-tabler-outline icon-tabler-flask">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M9 3l6 0" />
                                <path d="M10 9l4 0" />
                                <path d="M10 3v6l-4 11a.7 .7 0 0 0 .5 1h11a.7 .7 0 0 0 .5 -1l-4 -11v-6" />
                            </svg>
                        @elseif ($archive->type == 'bio')
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" style="color: #ee1c6b;"
                                stroke-linecap="round" stroke-linejoin="round"
                                class="icon icon-tabler icons-tabler-outline icon-tabler-pencil">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M4 20h4l10.5 -10.5a2.828 2.828 0 1 0 -4 -4l-10.5 10.5v4" />
                                <path d="M13.5 6.5l4 4" />
                            </svg>
                        @elseif ($archive->type == 'social')
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                style="color: #4bc538;" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                class="icon icon-tabler icons-tabler-outline icon-tabler-world">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M3 12a9 9 0 1 0 18 0a9 9 0 0 0 -18 0" />
                                <path d="M3.6 9h16.8" />
                                <path d="M3.6 15h16.8" />
                                <path d="M11.5 3a17 17 0 0 0 0 18" />
                                <path d="M12.5 3a17 17 0 0 1 0 18" />
                            </svg>
                        @endif
                    </a>
                    <span>{{ $archive->title }}</span>
                </div>
            </div>

            @if ($archivesEn->count() > 0 && ($archive_index % 10 == 9 || $archive_index == $archivesEn->count() - 1))
                {{-- Cierra la columna al llegar a 10 o al final de la lista --}}
    </div>
    @endif

    @if ($archivesEn->count() > 0 && $archive_index % 30 == 29 && $archive_index != $archivesEn->count() - 1)
        {{-- Nueva fila cada 30 ítems --}}
        </div>
        <div class="row">
    @endif
    @endforeach
    </div>
    @endif

    {{-- <ul class="topic-list">
                            <li class="topic-item"><span class="topic-icon bg-primary"></span>Les pavillons Courbet, 1855 et
                                1867</li>
                            <li class="topic-item"><span class="topic-icon bg-danger"></span>Les mariages des
                                impressionnistes
                            </li>
                            <li class="topic-item"><span class="topic-icon bg-primary"></span>Manet, chef de fil absent</li>
                            <li class="topic-item"><span class="topic-icon bg-warning"></span>Mélange optique, théorie
                                divisionniste</li>
                            <li class="topic-item"><span class="topic-icon bg-primary"></span>Les portraits mondains de
                                Trouville, Courbet</li>
                            <li class="topic-item"><span class="topic-icon bg-danger"></span>Courbet - Monet, naissance
                                d'une
                                amitié</li>
                        </ul> --}}
    </div>
    </div>
    </section>
    @endforeach
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const videoWrappers = document.querySelectorAll('.video-wrapper');
            
            videoWrappers.forEach(wrapper => {
                const playButton = wrapper.querySelector('.play-button');
                const coverImage = wrapper.querySelector('.cover-image');
                const iframeContainer = wrapper.querySelector('.iframe-container');
                const iframe = wrapper.querySelector('iframe');
                const videoUrl = wrapper.dataset.videoUrl;
                
                playButton.addEventListener('click', function() {
                    coverImage.style.display = 'none';
                    playButton.style.display = 'none';
                    iframeContainer.style.display = 'block';
                    iframe.src = videoUrl;
                });
            });
        });
        </script>
@endsection
