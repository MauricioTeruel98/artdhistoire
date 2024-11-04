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

        .iframe-container {
            min-height: 500px;
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
            margin-bottom: 0.5rem;
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
            padding: 2px 0px;
            font-size: 14px !important;
        }

        .topic-list {
            list-style-type: none;
            padding-left: 0;
        }

        .topic-item {
            margin-bottom: 0rem;
        }

        .topic-item span {
            font-size: 12px;
        }

        .topic {
            padding: 2px 0px;
            font-size: 14px;
        }

        .texte h3 {
            font-size: 19px !important;
        }

        .texte p {
            font-size: 16px !important;
        }

        .archive-section span {
            font-family: 'Futura Light', sans-serif !important;
            font-size: 12px !important;
        }

        iframe .pushfooter {
            height: 0px !important;
        }

        @media screen and (max-width: 768px) {
            .topic {
                width: 100%;
            }
        }

        @media (min-width: 1400px) {

            .container,
            .container-lg,
            .container-md,
            .container-sm,
            .container-xl,
            .container-xxl {
                max-width: 1400px;
            }
        }
    </style>

@endsection

@section('content')
    @include('partials.slider')
    <div class="container mt-4">
        {{-- <h1 class="text-center mb-4">{{ app()->getLocale() == 'fr' ? 'Accédez à LISA via la vidéo ou via la librairie' : 'Access LISA via the video or the library' }}</h1> --}}
        <h1 class="text-center mb-4 futura-light" style="font-size: 22px !important; font-weight: bold !important;">
            {{ app()->getLocale() == 'fr' ? $textosPiloto->title : $textosPiloto->title_en ?? '' }}
        </h1>
        <div class="row mb-4">
            <div class="col-md-6 texte">
                {!! app()->getLocale() == 'fr' ? $textosPiloto->text_1 : $textosPiloto->text_1_en ?? '' !!}
                {{--
                <h2>{{ app()->getLocale() == 'fr' ? 'Vidéo interactive' : 'Interactive video' }}</h2>
                <ul>
                    <li>{{ app()->getLocale() == 'fr' ? 'Visionnez la vidéo (Chapitre 4 de la conférence Manet et Seurat)' : 'Watch the video (Chapter 4 of the Manet and Seurat conference)' }}</li>
                    <li>{{ app()->getLocale() == 'fr' ? 'Cliquez sur les pop-up en haut à gauche pour ouvrir les articles interactifs de LISA. Pour cette version pilote, les fiches grises sont désactivées' : 'Click on the pop-ups on the top left to open the LISA interactive articles. For this pilot version, the gray cards are disabled' }}</li>
                </ul> --}}
            </div>
            <div class="col-md-6 texte">

                {!! app()->getLocale() == 'fr' ? $textosPiloto->text_2 : $textosPiloto->text_2_en ?? '' !!}

                {{-- <h2>{{ app()->getLocale() == 'fr' ? 'Librairie interactive' : 'Interactive library' }}</h2>
                <ul>
                    <li>{{ app()->getLocale() == 'fr' ? "Cliquez sur un icône fiche pour accéder à l'article LISA, renseigné par son code couleur" : "Click on a color code icon to access the LISA article, filled in with its color code" }}</li>
                    <li>{{ app()->getLocale() == 'fr' ? "Cliquez sur l'horloge en haut à droite de l'article pour revenir à la vidéo" : "Click on the clock in the top right of the article to return to the video" }}</li>
                </ul> --}}
            </div>
        </div>

        <div class="text-center mb-4">
            <a class="btn btn-outline-secondary"
                href="{{ route('tutorial') }}">{{ app()->getLocale() == 'fr' ? 'Tutoriel de navigation ➤' : 'Navigation tutorial ➤' }}</a>
        </div>

        @if ($pilote && $pilote->posts)
            @foreach ($pilote->posts as $index => $post)
                <section class="my-5">
                    <h2 class="futura-light" style="font-size: 20px !important;">{{ $index + 1 }}.
                        {{ app()->getLocale() == 'fr' ? $post->title_fr : $post->title }}</h2>
                    <h3 class="section-title futura-light" style="font-size: 16px !important;">
                        {{ app()->getLocale() == 'fr' ? $post->excerpt_fr : $post->excerpt }}</h3>
                    <div class="row">
                        <div class="col-md-5">
                            <div class="image-container" style="height: 100%;">
                                <div class="video-wrapper"
                                    data-video-url="{{ app()->getLocale() == 'fr' ? $post->hihaho : $post->hihaho_en }}">
                                    {{-- Eliminamos la imagen y el botón de play --}}
                                    <div class="iframe-container">
                                        <iframe src="{{ app()->getLocale() == 'fr' ? $post->hihaho : $post->hihaho_en }}"
                                            frameborder="0" webkitallowfullscreen="true" mozallowfullscreen="true"
                                            allowfullscreen="true"
                                            allow="autoplay; fullscreen; clipboard-read; clipboard-write"
                                            style="position: absolute !important; top: 0px !important; left: 0px !important; width: 100% !important; height: 100% !important;">
                                        </iframe>
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

                                        // Calcular el número de elementos por columna
                                        $totalItems = $archives->count();
                                        $columnsCount = 3; // Número deseado de columnas
                                        $itemsPerColumn = ceil($totalItems / $columnsCount);
                                    @endphp

                                    <div class="row w-100">
                                        @for ($col = 0; $col < $columnsCount; $col++)
                                            <div class="col-md-4">
                                                @foreach ($archives->slice($col * $itemsPerColumn, $itemsPerColumn) as $archive)
                                                    <div class="topic">
                                                        <div class="topic-item">
                                                            <a
                                                                href="{{ route('interactive.pdf.pilote', ['id' => $archive->id]) }}">
                                                                @if ($archive->type == 'nonDisponible')
                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                                        height="24" viewBox="0 0 24 24"
                                                                        fill="currentColor" style="color: grey;"
                                                                        class="icon icon-tabler icons-tabler-filled icon-tabler-file">
                                                                        <path stroke="none" d="M0 0h24v24H0z"
                                                                            fill="none" />
                                                                        <path
                                                                            d="M12 2l.117 .007a1 1 0 0 1 .876 .876l.007 .117v4l.005 .15a2 2 0 0 0 1.838 1.844l.157 .006h4l.117 .007a1 1 0 0 1 .876 .876l.007 .117v9a3 3 0 0 1 -2.824 2.995l-.176 .005h-10a3 3 0 0 1 -2.995 -2.824l-.005 -.176v-14a3 3 0 0 1 2.824 -2.995l.176 -.005h5z" />
                                                                        <path d="M19 7h-4l-.001 -4.001z" />
                                                                    </svg>
                                                                @elseif ($archive->type == 'contexto')
                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                                        height="24" viewBox="0 0 24 24" fill="none"
                                                                        stroke="currentColor" stroke-width="2"
                                                                        style="color: #5471f1;" stroke-linecap="round"
                                                                        stroke-linejoin="round"
                                                                        class="icon icon-tabler icons-tabler-outline icon-tabler-photo">
                                                                        <path stroke="none" d="M0 0h24v24H0z"
                                                                            fill="none" />
                                                                        <path d="M15 8h.01" />
                                                                        <path
                                                                            d="M3 6a3 3 0 0 1 3 -3h12a3 3 0 0 1 3 3v12a3 3 0 0 1 -3 3h-12a3 3 0 0 1 -3 -3v-12z" />
                                                                        <path
                                                                            d="M3 16l5 -5c.928 -.893 2.072 -.893 3 0l5 5" />
                                                                        <path
                                                                            d="M14 14l1 -1c.928 -.893 2.072 -.893 3 0l3 3" />
                                                                    </svg>
                                                                @elseif($archive->type == 'teoria')
                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                                        height="24" viewBox="0 0 24 24" fill="none"
                                                                        stroke="currentColor" stroke-width="2"
                                                                        style="color: #ffb102;" stroke-linecap="round"
                                                                        stroke-linejoin="round"
                                                                        class="icon icon-tabler icons-tabler-outline icon-tabler-flask">
                                                                        <path stroke="none" d="M0 0h24v24H0z"
                                                                            fill="none" />
                                                                        <path d="M9 3l6 0" />
                                                                        <path d="M10 9l4 0" />
                                                                        <path
                                                                            d="M10 3v6l-4 11a.7 .7 0 0 0 .5 1h11a.7 .7 0 0 0 .5 -1l-4 -11v-6" />
                                                                    </svg>
                                                                @elseif ($archive->type == 'bio')
                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                                        height="24" viewBox="0 0 24 24" fill="none"
                                                                        stroke="currentColor" stroke-width="2"
                                                                        style="color: #ee1c6b;" stroke-linecap="round"
                                                                        stroke-linejoin="round"
                                                                        class="icon icon-tabler icons-tabler-outline icon-tabler-pencil">
                                                                        <path stroke="none" d="M0 0h24v24H0z"
                                                                            fill="none" />
                                                                        <path
                                                                            d="M4 20h4l10.5 -10.5a2.828 2.828 0 1 0 -4 -4l-10.5 10.5v4" />
                                                                        <path d="M13.5 6.5l4 4" />
                                                                    </svg>
                                                                @elseif ($archive->type == 'social')
                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                                        height="24" style="color: #4bc538;"
                                                                        viewBox="0 0 24 24" fill="none"
                                                                        stroke="currentColor" stroke-width="2"
                                                                        stroke-linecap="round" stroke-linejoin="round"
                                                                        class="icon icon-tabler icons-tabler-outline icon-tabler-world">
                                                                        <path stroke="none" d="M0 0h24v24H0z"
                                                                            fill="none" />
                                                                        <path d="M3 12a9 9 0 1 0 18 0a9 9 0 0 0 -18 0" />
                                                                        <path d="M3.6 9h16.8" />
                                                                        <path d="M3.6 15h16.8" />
                                                                        <path d="M11.5 3a17 17 0 0 0 0 18" />
                                                                        <path d="M12.5 3a17 17 0 0 1 0 18" />
                                                                    </svg>
                                                                @endif
                                                            </a>
                                                            <span class="futura-light"
                                                                style="font-size: 13px !important;">{{ $archive->title }}</span>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endfor
                                    </div>
                                </div>
                            @else
                                <div class="d-flex flex-wrap topic-list">
                                    @php
                                        $archivesEn = DB::table('archives_en')
                                            ->where('post_id', $post->id)
                                            ->orderBy('order', 'ASC')
                                            ->get();

                                        $totalItems = $archivesEn->count();
                                        $columnsCount = 3;
                                        $itemsPerColumn = ceil($totalItems / $columnsCount);
                                    @endphp

                                    <div class="row w-100">
                                        @for ($col = 0; $col < $columnsCount; $col++)
                                            <div class="col-md-4">
                                                @foreach ($archivesEn->slice($col * $itemsPerColumn, $itemsPerColumn) as $archive)
                                                    <div class="topic">
                                                        <div class="topic-item">
                                                            <a
                                                                href="{{ route('interactive.pdf.pilote', ['id' => $archive->id]) }}">
                                                                @if ($archive->type == 'nonDisponible')
                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                                        height="24" viewBox="0 0 24 24"
                                                                        fill="currentColor" style="color: grey;"
                                                                        class="icon icon-tabler icons-tabler-filled icon-tabler-file">
                                                                        <path stroke="none" d="M0 0h24v24H0z"
                                                                            fill="none" />
                                                                        <path
                                                                            d="M12 2l.117 .007a1 1 0 0 1 .876 .876l.007 .117v4l.005 .15a2 2 0 0 0 1.838 1.844l.157 .006h4l.117 .007a1 1 0 0 1 .876 .876l.007 .117v9a3 3 0 0 1 -2.824 2.995l-.176 .005h-10a3 3 0 0 1 -2.995 -2.824l-.005 -.176v-14a3 3 0 0 1 2.824 -2.995l.176 -.005h5z" />
                                                                        <path d="M19 7h-4l-.001 -4.001z" />
                                                                    </svg>
                                                                @elseif ($archive->type == 'contexto')
                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                                        height="24" viewBox="0 0 24 24" fill="none"
                                                                        stroke="currentColor" stroke-width="2"
                                                                        style="color: #5471f1;" stroke-linecap="round"
                                                                        stroke-linejoin="round"
                                                                        class="icon icon-tabler icons-tabler-outline icon-tabler-photo">
                                                                        <path stroke="none" d="M0 0h24v24H0z"
                                                                            fill="none" />
                                                                        <path d="M15 8h.01" />
                                                                        <path
                                                                            d="M3 6a3 3 0 0 1 3 -3h12a3 3 0 0 1 3 3v12a3 3 0 0 1 -3 3h-12a3 3 0 0 1 -3 -3v-12z" />
                                                                        <path
                                                                            d="M3 16l5 -5c.928 -.893 2.072 -.893 3 0l5 5" />
                                                                        <path
                                                                            d="M14 14l1 -1c.928 -.893 2.072 -.893 3 0l3 3" />
                                                                    </svg>
                                                                @elseif($archive->type == 'teoria')
                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                                        height="24" viewBox="0 0 24 24" fill="none"
                                                                        stroke="currentColor" stroke-width="2"
                                                                        style="color: #ffb102;" stroke-linecap="round"
                                                                        stroke-linejoin="round"
                                                                        class="icon icon-tabler icons-tabler-outline icon-tabler-flask">
                                                                        <path stroke="none" d="M0 0h24v24H0z"
                                                                            fill="none" />
                                                                        <path d="M9 3l6 0" />
                                                                        <path d="M10 9l4 0" />
                                                                        <path
                                                                            d="M10 3v6l-4 11a.7 .7 0 0 0 .5 1h11a.7 .7 0 0 0 .5 -1l-4 -11v-6" />
                                                                    </svg>
                                                                @elseif ($archive->type == 'bio')
                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                                        height="24" viewBox="0 0 24 24" fill="none"
                                                                        stroke="currentColor" stroke-width="2"
                                                                        style="color: #ee1c6b;" stroke-linecap="round"
                                                                        stroke-linejoin="round"
                                                                        class="icon icon-tabler icons-tabler-outline icon-tabler-pencil">
                                                                        <path stroke="none" d="M0 0h24v24H0z"
                                                                            fill="none" />
                                                                        <path
                                                                            d="M4 20h4l10.5 -10.5a2.828 2.828 0 1 0 -4 -4l-10.5 10.5v4" />
                                                                        <path d="M13.5 6.5l4 4" />
                                                                    </svg>
                                                                @elseif ($archive->type == 'social')
                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                                        height="24" style="color: #4bc538;"
                                                                        viewBox="0 0 24 24" fill="none"
                                                                        stroke="currentColor" stroke-width="2"
                                                                        stroke-linecap="round" stroke-linejoin="round"
                                                                        class="icon icon-tabler icons-tabler-outline icon-tabler-world">
                                                                        <path stroke="none" d="M0 0h24v24H0z"
                                                                            fill="none" />
                                                                        <path d="M3 12a9 9 0 1 0 18 0a9 9 0 0 0 -18 0" />
                                                                        <path d="M3.6 9h16.8" />
                                                                        <path d="M3.6 15h16.8" />
                                                                        <path d="M11.5 3a17 17 0 0 0 0 18" />
                                                                        <path d="M12.5 3a17 17 0 0 1 0 18" />
                                                                    </svg>
                                                                @endif
                                                            </a>
                                                            <span class="futura-light"
                                                                style="font-size: 13px !important;">{{ $archive->title }}</span>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endfor
                                    </div>
                                </div>
                            @endif

                            @if ($index === 0)
                                <div class="row mt-4 archive-section">
                                    <div class="col-12">
                                        <div class="d-flex flex-wrap gap-1 justify-content-center">
                                            <div class="d-flex align-items-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="currentColor" style="color: grey;"
                                                    class="icon icon-tabler icons-tabler-filled icon-tabler-file">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                    <path
                                                        d="M12 2l.117 .007a1 1 0 0 1 .876 .876l.007 .117v4l.005 .15a2 2 0 0 0 1.838 1.844l.157 .006h4l.117 .007a1 1 0 0 1 .876 .876l.007 .117v9a3 3 0 0 1 -2.824 2.995l-.176 .005h-10a3 3 0 0 1 -2.995 -2.824l-.005 -.176v-14a3 3 0 0 1 2.824 -2.995l.176 -.005h5z" />
                                                    <path d="M19 7h-4l-.001 -4.001z" />
                                                </svg>
                                                <span>{{ app()->getLocale() == 'fr' ? 'Non disponible' : 'Not available' }}</span>
                                            </div>
                                            <div class="d-flex align-items-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" style="color: #5471f1;" stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    class="icon icon-tabler icons-tabler-outline icon-tabler-photo me-2">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                    <path d="M15 8h.01" />
                                                    <path
                                                        d="M3 6a3 3 0 0 1 3 -3h12a3 3 0 0 1 3 3v12a3 3 0 0 1 -3 3h-12a3 3 0 0 1 -3 -3v-12z" />
                                                    <path d="M3 16l5 -5c.928 -.893 2.072 -.893 3 0l5 5" />
                                                    <path d="M14 14l1 -1c.928 -.893 2.072 -.893 3 0l3 3" />
                                                </svg>
                                                <span>{{ app()->getLocale() == 'fr' ? 'Contexte artistique' : 'Artistic context' }}</span>
                                            </div>
                                            <div class="d-flex align-items-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" style="color: #ffb102;" stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    class="icon icon-tabler icons-tabler-outline icon-tabler-flask me-2">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                    <path d="M9 3l6 0" />
                                                    <path d="M10 9l4 0" />
                                                    <path
                                                        d="M10 3v6l-4 11a.7 .7 0 0 0 .5 1h11a.7 .7 0 0 0 .5 -1l-4 -11v-6" />
                                                </svg>
                                                <span>{{ app()->getLocale() == 'fr' ? 'Théorie' : 'Theory' }}</span>
                                            </div>
                                            <div class="d-flex align-items-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" style="color: #ee1c6b;" stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    class="icon icon-tabler icons-tabler-outline icon-tabler-pencil me-2">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                    <path d="M4 20h4l10.5 -10.5a2.828 2.828 0 1 0 -4 -4l-10.5 10.5v4" />
                                                    <path d="M13.5 6.5l4 4" />
                                                </svg>
                                                <span>{{ app()->getLocale() == 'fr' ? 'Élément biographique' : 'Biographical element' }}</span>
                                            </div>
                                            <div class="d-flex align-items-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    style="color: #4bc538;" viewBox="0 0 24 24" fill="none"
                                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    class="icon icon-tabler icons-tabler-outline icon-tabler-world me-2">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                    <path d="M3 12a9 9 0 1 0 18 0a9 9 0 0 0 -18 0" />
                                                    <path d="M3.6 9h16.8" />
                                                    <path d="M3.6 15h16.8" />
                                                    <path d="M11.5 3a17 17 0 0 0 0 18" />
                                                    <path d="M12.5 3a17 17 0 0 1 0 18" />
                                                </svg>
                                                <span>{{ app()->getLocale() == 'fr' ? 'Contexte sociale ou scientifique' : 'Social or scientific context' }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </section>
            @endforeach
        @else
            <p>No se encontraron posts para la categoría pilote.</p>
        @endif
    </div>
@endsection
