@extends('layouts.applayout')

@section('title', "Art d'Histoire | Home")

@section('header')

    <style>
        .img-modal {
            padding: 100px;
        }

        .text-video {
            padding: 100px;
        }

        /* Estilos para el formulario de búsqueda */
        #searchForm {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        #searchInput {
            flex: 1;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 0;
            /* Sin bordes redondeados */
            outline: none;
            transition: border-color 0.3s;
        }

        #searchInput:focus {
            border-color: #666;
        }

        #searchForm button {
            padding: 10px 20px;
            border: none;
            background-color: #333;
            color: #fff;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        #searchForm button:hover {
            background-color: #555;
        }

        /* Estilos para el acordeón */
        .accordion {
            border: 1px solid #ccc;
            border-radius: 0;
            /* Sin bordes redondeados */
        }

        .accordion-item {
            border-bottom: 1px solid #ccc;
        }

        .accordion-header {
            background-color: #f9f9f9;
            padding: 10px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .accordion-header:hover {
            background-color: #e9e9e9;
        }

        .accordion-button {
            background: none;
            border: none;
            padding: 0;
            font-size: 16px;
            text-align: left;
            width: 100%;
            cursor: pointer;
        }

        .accordion-body {
            padding: 10px;
            background-color: #fff;
        }

        .accordion-item:first-of-type>.accordion-header .accordion-button {
            border-top-left-radius: 0px;
            border-top-right-radius: 0px;
        }

        .accordion-button:focus {
            box-shadow: none;
        }

        .accordion-button:not(.collapsed) {
            color: initial;
            background-color: initial;
            box-shadow: none;
        }

        /* Estilos para la lista de resultados */
        .list-group-item {
            border: none;
            padding: 10px;
            border-bottom: 1px solid #eee;
        }

        .list-group-item a {
            text-decoration: none;
            color: #333;
            transition: color 0.3s;
        }

        .list-group-item a:hover {
            color: #007bff;
        }

        /* Estilos para el paginador */
        .pagination {
            display: flex;
            list-style: none;
            padding: 0;
        }

        .pagination li {
            margin: 0 5px;
        }

        .pagination a,
        .pagination span {
            display: block;
            padding: 8px 12px;
            border: 1px solid #ccc;
            border-radius: 0;
            /* Sin bordes redondeados */
            text-decoration: none;
            color: #333;
            transition: background-color 0.3s, color 0.3s;
        }

        .pagination a:hover {
            background-color: #f0f0f0;
            color: #007bff;
        }

        .pagination .active span {
            background-color: #333;
            color: #fff;
            border-color: #333;
        }

        .pagination .disabled span {
            color: #999;
            border-color: #ccc;
        }

        .video-link {
            position: relative;
            display: block;
            overflow: hidden;
        }

        .video-link img {
            width: 100%;
            height: auto;
            display: block;
        }

        .video-overlay {
            position: absolute;
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            bottom: 0;
            left: 0;
            right: 0;
            background-color: rgba(0, 0, 0, 0.5);
            /* Color negro con opacidad del 50% */
            color: white;
            text-align: center;
            padding: 10px;
            opacity: 0;
            transition: opacity 0.3s ease, transform 0.3s ease;
            transform: translateY(100%);
        }

        .video-link:hover .video-overlay {
            opacity: 1;
            transform: translateY(0);
        }

        /* Modificar los estilos existentes del modal */
        .img-modal {
            padding: 0;
            /* Eliminamos el padding de 100px */
            height: 600px;
            /* Altura fija para todas las imágenes */
            width: 100%;
            object-fit: cover;
            /* Asegura que la imagen cubra todo el espacio */
            display: block;
        }

        /* Ajustar los botones de navegación */
        #prevVideo,
        #nextVideo {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            z-index: 10;
            background: rgba(255, 255, 255, 0.8);
            border-radius: 50%;
            padding: 10px;
            margin: 0 15px;
        }

        #prevVideo {
            left: 0;
        }

        #nextVideo {
            right: 0;
        }

        /* Ajustar el contenedor de la imagen */
        .modal-image-container {
            position: relative;
            height: 600px;
            /* Misma altura que la imagen */
            overflow: hidden;
        }

        /* Ajustes para el modal */
        .modal-content {
            min-height: 100vh;
            /* Asegura que el contenido cubra toda la altura */
            background: white;
            /* Fondo blanco explícito */
        }

        .modal-body {
            padding: 0;
            height: 100vh;
            overflow-y: auto;
        }

        header h1{
            font-family: 'Playfair Display', serif !important;
            font-size: 25px !important;
            font-weight: bold !important;
            color: #000000 !important;
        }

        /* De header selecciona el primer elemento p, solo el primero */
        header p:first-of-type{
            /* Coloca estos estilos Futura Light - 17px - Gras+ Italique #0000 */
            font-family: 'Futura Light', sans-serif !important;
            font-style: italic !important;
            font-size: 17px !important;
            font-weight: bold !important;
            color: #000000 !important;
        }

        /* A las demas etiquetas p de header aplicales estos estilos: Futura Light - 16px #757575 */
        header p:not(:first-of-type){
            font-family: 'Futura Light', sans-serif !important;
            font-size: 16px !important;
            color: #757575 !important;
        }

        - @media (max-width: 768px) {
            #searchForm {
                flex-direction: column;
            }

            .img-modal {
                padding: 50px;
            }

            .text-video {
                padding: 50px;
            }

            .modal-image-container {
                height: 300px;
                /* Altura más pequeña para móvil */
            }

            .img-modal {
                height: 300px;
            }

            .text-video {
                padding: 20px;
                /* Menos padding en móvil */
                background: white;
                /* Asegura fondo blanco */
            }

            .modal-content {
                display: flex;
                flex-direction: column;
            }

            .modal-body .row {
                flex-direction: column;
                height: auto;
            }

            .col-md-5 {
                flex-grow: 1;
                background: white;
            }
        }
    </style>

@endsection

@section('content')
    @include('partials.banner')
    @include('partials.slider')

    <div class="container mx-auto py-5">

        <!-- Acordeón para el filtro de búsqueda -->
        {{-- <div class="accordion my-4" id="searchAccordion">
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingOne">
                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne"
                        aria-expanded="true" aria-controls="collapseOne">
                        {{ app()->getLocale() == 'fr' ? 'Recherche de contenu' : 'Search for content' }}
                    </button>
                </h2>
                <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne"
                    data-bs-parent="#searchAccordion">
                    <div class="accordion-body">
                        <!-- Formulario de búsqueda -->
                        <form id="searchForm" class="mb-4">
                            <input type="text" name="search" id="searchInput" placeholder="{{ app()->getLocale() == 'fr' ? 'Recherche de contenu' : 'Search for content' }}"
                                class="form-control">
                            <button type="submit" class="btn btn-primary">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round"
                                    class="icon icon-tabler icons-tabler-outline icon-tabler-search">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0" />
                                    <path d="M21 21l-6 -6" />
                                </svg>
                                {{ app()->getLocale() == 'fr' ? 'Rechercher' : 'Search' }}
                            </button>
                            <button type="button" id="listAllBtn" class="btn btn-secondary">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-list">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M9 6l11 0" />
                                    <path d="M9 12l11 0" />
                                    <path d="M9 18l11 0" />
                                    <path d="M5 6l0 .01" />
                                    <path d="M5 12l0 .01" />
                                    <path d="M5 18l0 .01" />
                                </svg>
                                {{ app()->getLocale() == 'fr' ? 'Listar Todos' : 'List All' }}
                            </button>
                        </form>

                        <!-- Resultados de la búsqueda -->
                        <div id="searchResults">
                            @if ($pdfs->isNotEmpty())
                                <h3>{{ app()->getLocale() == 'fr' ? 'Résultats de la recherche :' : 'Search results:' }}
                                </h3>
                                <ul class="list-group mb-4">
                                    @foreach ($pdfs as $pdf)
                                        <li class="list-group-item">
                                            <a href="{{ route('interactive.pdf', ['id' => $pdf->id]) }}">
                                                {{ app()->getLocale() == 'fr' ? $pdf->title_fr : $pdf->title }}
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                                {{ $pdfs->links('vendor.pagination.simple-bootstrap-5') }}
                            @else
                                <p>{{ app()->getLocale() == 'fr' ? 'Aucun résultat n\'a été trouvé pour "' . request('search') . '".' : 'No results found for "' . request('search') . '".' }}
                                </p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div> --}}

        <header class="text-center mb-5">
            {!! app()->getLocale() == 'fr' ? $textos->texto_home : $textos->texto_home_en !!}
            <a href="/interactive" class="btn btn-outline-secondary">
                {{ app()->getLocale() == 'fr' ? 'Essayez LISA' : 'Try LISA' }}
            </a>
        </header>

        <div class="col-md-10 mx-auto">
            <div class="row" style="justify-content: center;">
                @foreach ($videos as $index => $video)
                    <div class="col-md-4 mb-5">
                        <div class="mx-auto" style="max-width: 320px;"> <!-- Contenedor con ancho máximo más pequeño -->
                            <a href="#" class="video-link" data-video-id="{{ $video->id }}" data-index="{{ $index }}">
                                <div class="position-relative" style="padding-bottom: 100%;">
                                    <img src="/storage/{{ $video->image }}"
                                        alt="{{ app()->getLocale() == 'fr' ? $video->title_fr : $video->title }}"
                                        class="position-absolute w-100 h-100 object-fit-cover">
                                    <div class="video-overlay">
                                        <h2 class="linotype" style="font-size: 24px !important;">
                                            {{ app()->getLocale() == 'fr' ? $video->title_fr : $video->title }}</h2>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="videoModal" tabindex="-1" aria-labelledby="videoModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-fullscreen">
            <div class="modal-content">
                <div class="container h-100">
                    <div class="modal-body">
                        <button type="button" class="btn-close position-absolute top-0 end-0 m-4 z-3"
                            data-bs-dismiss="modal" aria-label="Close"></button>
                        <div class="row g-0 h-100">
                            <div class="col-md-7 position-relative mt-5">
                                <div class="modal-image-container">
                                    <img src="" alt="Video thumbnail" class="img-modal" id="modalImage">
                                    <button class="btn btn-link text-black" id="prevVideo">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="35" height="35"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round"
                                            class="icon icon-tabler icons-tabler-outline icon-tabler-chevron-left">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path d="M15 6l-6 6l6 6" />
                                        </svg>
                                    </button>
                                    <button class="btn btn-link text-black" id="nextVideo">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="35" height="35"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round"
                                            class="icon icon-tabler icons-tabler-outline icon-tabler-chevron-right">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path d="M9 6l6 6l-6 6" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            <div class="col-md-5 d-flex flex-column text-video">
                                <h2 id="modalTitle" class="fs-1 mb-4"></h2>
                                <div class="flex-grow-1">
                                    <p id="modalDescription"></p>
                                </div>
                                <a href="#" class="btn btn-link text-black align-self-start mt-4" id="watchVideoBtn">
                                    {{ app()->getLocale() == 'fr' ? 'Aller à la page' : 'Go to the page' }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    {{--
    <script>
        $(document).ready(function() {
            function fetchContent(searchQuery = '', page = 1) {
                $.ajax({
                    url: '{{ route('search.content') }}',
                    method: 'GET',
                    data: {
                        search: searchQuery,
                        page: page
                    },
                    success: function(response) {
                        let resultsHtml = '';
                        if (response.data.length > 0) {
                            resultsHtml +=
                                '<h3>Résultats de la recherche :</h3><ul class="list-group mb-4">';
                            response.data.forEach(function(item) {
                                resultsHtml += `<li class="list-group-item">
                                    <a href="/interactive/pdf/${item.id}">
                                        ${item.title || item.title_fr || item.name || item.name_fr} <span class="badge bg-secondary">${item.type}</span>
                                    </a>
                                </li>`;
                            });
                            resultsHtml += '</ul>';
                            resultsHtml += response.links; // Añadir los enlaces de paginación
                        } else {
                            resultsHtml =
                            `<p>Aucun résultat trouvé pour "${searchQuery}".</p>`;
                        }
                        $('#searchResults').html(resultsHtml);
                    }
                });
            }
    
            $('#searchForm').on('submit', function(e) {
                e.preventDefault();
                const searchQuery = $('#searchInput').val();
                fetchContent(searchQuery);
            });
    
            $('#listAllBtn').on('click', function() {
                fetchContent();
            });
    
            $(document).on('click', '.pagination a', function(e) {
                e.preventDefault();
                const url = $(this).attr('href');
                const page = url.split('page=')[1];
                const searchQuery = $('#searchInput').val();
                fetchContent(searchQuery, page);
            });
        });
    </script> --}}

    <script>
        $(document).ready(function() {
            $('#searchToggle').on('click', function() {
                $('#searchBar').slideToggle();
            });

            function getItemUrl(item) {
                console.log('Generando URL para:', item);
                switch (item.type) {
                    case 'Saga':
                        return `/interactive/${item.id}`;
                    case 'Interactive':
                        return `/interactive/${item.id}`;
                    case 'PDF':
                        return `/interactive/pdf/${item.id}`; // /interactive/pdf/${item.id}/${category.id}
                    case 'Video Online':
                        return `/video-online/${item.id}`;
                    default:
                        console.log('Tipo desconocido:', item.type);
                        return '#';
                }
            }

            function fetchContent(searchQuery = '', page = 1) {
                $.ajax({
                    url: '{{ route('search.content') }}',
                    method: 'GET',
                    data: {
                        search: searchQuery,
                        page: page
                    },
                    success: function(response) {
                        let $resultsList = $('<ul class="list-group"></ul>');
                        if (response.data.length > 0) {
                            response.data.forEach(function(item) {
                                const itemUrl = getItemUrl(item);
                                console.log('URL generada:', itemUrl);
                                let $listItem = $('<li class="list-group-item"></li>');
                                let $link = $('<a></a>')
                                    .attr('href', itemUrl)
                                    .text(item.title || item.title_fr || item.name || item
                                        .name_fr);
                                let $badge = $('<span class="badge bg-secondary"></span>').text(
                                    item.type);
                                $link.append($badge);
                                $listItem.append($link);
                                $resultsList.append($listItem);
                            });
                        } else {
                            let $noResults = $('<p></p>').text(
                                `{{ app()->getLocale() == 'fr' ? 'Aucun résultat trouvé pour' : 'No results found for' }} "${searchQuery}".`
                            );
                            $resultsList.append($noResults);
                        }

                        $('#searchResults').empty().append($resultsList);

                        if (response.links) {
                            $('#searchResults').append(response.links);
                        }

                        // Verificar el contenido del DOM después de la inserción
                        console.log('Contenido del DOM después de la inserción:', $('#searchResults')
                            .html());
                    }
                });
            }

            $('#searchResults a').each(function() {
                console.log('URL en el DOM:', $(this).attr('href'));
            });

            $('#searchForm').on('submit', function(e) {
                e.preventDefault();
                const searchQuery = $('#searchInput').val();
                fetchContent(searchQuery);
            });

            $(document).on('click', '.pagination a', function(e) {
                e.preventDefault();
                const url = $(this).attr('href');
                const page = url.split('page=')[1];
                const searchQuery = $('#searchInput').val();
                fetchContent(searchQuery, page);
            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const videos = @json($videos);
            let currentIndex = 0;

            const videoLinks = document.querySelectorAll('.video-link');
            const modal = new bootstrap.Modal(document.getElementById('videoModal'));
            const modalImage = document.getElementById('modalImage');
            const modalTitle = document.getElementById('modalTitle');
            const modalDescription = document.getElementById('modalDescription');
            const watchVideoBtn = document.getElementById('watchVideoBtn');
            const prevVideoBtn = document.getElementById('prevVideo');
            const nextVideoBtn = document.getElementById('nextVideo');

            function updateModal(index) {
                const video = videos[index];
                const locale = '{{ app()->getLocale() }}'; // Obtener el idioma actual
                modalImage.src = `/storage/${video.image}`;
                modalTitle.textContent = locale === 'fr' ? video.title_fr : video.title;
                modalDescription.innerHTML = locale === 'fr' ? (video.text_fr || 'Pas de description disponible.') :
                    (video.text || 'No description available.');
                watchVideoBtn.href = `/video-online/${video.id}`;
                currentIndex = index;
            }

            videoLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    const index = parseInt(this.dataset.index);
                    updateModal(index);
                    modal.show();
                });
            });

            prevVideoBtn.addEventListener('click', function() {
                currentIndex = (currentIndex - 1 + videos.length) % videos.length;
                updateModal(currentIndex);
            });

            nextVideoBtn.addEventListener('click', function() {
                currentIndex = (currentIndex + 1) % videos.length;
                updateModal(currentIndex);
            });
        });
    </script>
@endsection
