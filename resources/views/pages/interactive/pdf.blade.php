@extends('layouts.applayout')

@section('title', "Art d'Histoire | Visor de PDF")

@section('header')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.9.359/pdf.min.js"></script>
    <style>
        #pdf-viewer {
            width: 100%;
            height: 80vh;
            border: 1px solid #ccc;
            overflow: auto;
        }

        #pdf-viewer canvas {
            max-width: 100%;
            height: auto !important;
        }

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
    </style>
@endsection

@section('content')
    @include('partials.slider')

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <button onclick="history.back()" class="btn btn-outline-secondary mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="icon icon-tabler icons-tabler-outline icon-tabler-chevrons-left">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M11 7l-5 5l5 5" />
                        <path d="M17 7l-5 5l5 5" />
                    </svg>
                    {{ app()->getLocale() == 'fr' ? 'Retour à la bibliothèque' : 'Back to the library' }}
                </button>
                <h2 class="mb-4">{{ app()->getLocale() == 'fr' ? $archive->title_fr : $archive->title }}</h2>
                <div id="pdf-viewer"></div>
            </div>
        </div>
    </div>

    <script>
        // URL del PDF
        var url = '/storage/{{ $archive->route }}';

        // Carga el PDF
        pdfjsLib.getDocument(url).promise.then(function(pdf) {
            var numPages = pdf.numPages;
            var viewer = document.getElementById('pdf-viewer');

            function renderPage(pageNumber) {
                pdf.getPage(pageNumber).then(function(page) {
                    var viewport = page.getViewport({
                        scale: 1
                    });
                    var canvas = document.createElement('canvas');
                    var context = canvas.getContext('2d');

                    // Ajusta la escala para que se adapte al ancho del contenedor
                    var scale = viewer.clientWidth / viewport.width;
                    var scaledViewport = page.getViewport({
                        scale: scale
                    });

                    canvas.height = scaledViewport.height;
                    canvas.width = scaledViewport.width;

                    // Crear un div contenedor para el canvas y los enlaces
                    var wrapper = document.createElement('div');
                    wrapper.style.position = 'relative';
                    viewer.appendChild(wrapper);
                    wrapper.appendChild(canvas);

                    var renderContext = {
                        canvasContext: context,
                        viewport: scaledViewport
                    };

                    // Renderizar la página
                    var renderTask = page.render(renderContext);

                    // Obtener y renderizar los enlaces
                    page.getAnnotations().then(function(annotations) {
                        annotations.forEach(function(annotation) {
                            if (annotation.subtype === 'Link') {
                                var bounds = annotation.rect;
                                var left = bounds[0];
                                var top = bounds[1];
                                var width = bounds[2] - bounds[0];
                                var height = bounds[3] - bounds[1];

                                // Crear elemento de enlace
                                var link = document.createElement('a');
                                link.href = annotation.url || '';
                                link.style.position = 'absolute';
                                link.style.left = (left * scale) + 'px';
                                // Ajustar la posición vertical
                                link.style.top = (canvas.height - (top * scale)) + 'px';
                                link.style.width = (width * scale) + 'px';
                                link.style.height = (height * scale) + 'px';
                                link.style.cursor = 'pointer';
                                link.target = '_blank'; // Abrir en nueva pestaña

                                wrapper.appendChild(link);
                            }
                        });
                    });
                });
            }

            // Renderiza todas las páginas
            for (var i = 1; i <= numPages; i++) {
                renderPage(i);
            }
        });

        // Deshabilitar clic derecho
        document.addEventListener('contextmenu', function(e) {
            e.preventDefault();
        });
    </script>
@endsection
