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
</style>
@endsection

@section('content')
    @include('partials.slider')
    
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-12">
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
                    var viewport = page.getViewport({scale: 1});
                    var canvas = document.createElement('canvas');
                    var context = canvas.getContext('2d');
                    
                    // Ajusta la escala para que se adapte al ancho del contenedor
                    var scale = viewer.clientWidth / viewport.width;
                    var scaledViewport = page.getViewport({scale: scale});

                    canvas.height = scaledViewport.height;
                    canvas.width = scaledViewport.width;

                    var renderContext = {
                        canvasContext: context,
                        viewport: scaledViewport
                    };

                    page.render(renderContext);
                    viewer.appendChild(canvas);
                });
            }

            // Renderiza todas las páginas
            for(var i = 1; i <= numPages; i++) {
                renderPage(i);
            }
        });

        // Deshabilitar clic derecho
        document.addEventListener('contextmenu', function(e) {
            e.preventDefault();
        });
    </script>
@endsection