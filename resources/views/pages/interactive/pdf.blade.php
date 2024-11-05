@extends('layouts.applayout')

@section('title', "Art d'Histoire | Visor de PDF")

@section('header')
    <script src="https://documentcloud.adobe.com/view-sdk/main.js"></script>
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

        body {
            font-family: 'Futura', sans-serif !important;
            color: rgb(117, 117, 117) !important;
        }

        .baskeville-italic {
            font-family: 'Baskeville Italic', sans-serif !important;
        }

        .pdf-container {
            width: 100%;
            height: 90vh;
            margin: 20px 0;
            background-color: #f8f9fa;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        #adobe-dc-view {
            width: 100%;
            height: 100%;
        }

        .loading-message {
            text-align: center;
            padding: 20px;
            font-size: 1.2em;
            color: #666;
        }

        .error-message {
            text-align: center;
            padding: 20px;
            color: #dc3545;
        }

        .italic{
            font-style: italic;
        }

        @media (max-width: 768px) {
            .pdf-container {
                height: 80vh;
            }
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
                <h2 class="mb-4 italic">{{ $archive->title }}</h2>
                <div class="pdf-container">
                    <div id="adobe-dc-view"></div>
                    <div class="loading-message">
                        {{ app()->getLocale() == 'fr' ? 'Chargement du PDF...' : 'Loading PDF...' }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("adobe_dc_view_sdk.ready", function() {
            document.querySelector('.loading-message').style.display = 'none';

            try {
                var adobeDCView = new AdobeDC.View({
                    clientId: "ab9d6fe153f547109b7ff1fce07cf913", // Reemplaza con tu Client ID de Adobe
                    divId: "adobe-dc-view"
                });

                adobeDCView.previewFile({
                    content: {
                        location: {
                            url: "{{ asset('storage/' . $archive->route) }}"
                        }
                    },
                    metaData: {
                        fileName: "{{ $archive->title }}.pdf",
                        id: "{{ $archive->id }}"
                    }
                }, {
                    embedMode: "FULL_WINDOW",
                    showDownloadPDF: false,
                    showPrintPDF: false,
                    showLeftHandPanel: true,
                    showAnnotationTools: false,
                    enableFormFilling: false,
                    showZoomControl: true,
                    showToolbar: true, // Asegura que la barra de herramientas esté visible
                    toolbarControlsToShow: ['zoom', 'pageControls', 'fitWidth',
                    'fitPage'], // Especifica los controles a mostrar
                    defaultViewMode: "FIT_WIDTH",
                    zoom: {
                        default: 1.0, // Zoom inicial
                        minimum: 0.5, // Zoom mínimo permitido
                        maximum: 4.0 // Zoom máximo permitido
                    },
                    enableLinearization: true,
                    dockPageControls: true,
                    showPageControls: true,
                    enablePageScrolling: true
                });

                // Prevenir el menú contextual (clic derecho)
                document.getElementById('adobe-dc-view').addEventListener('contextmenu', function(e) {
                    e.preventDefault();
                });

                // Configuración adicional de seguridad
                adobeDCView.registerCallback(
                    AdobeDC.View.Enum.CallbackType.GET_USER_PROFILE_API,
                    function() {
                        return {
                            userProfile: {
                                name: "{{ auth()->user()->name ?? 'Guest' }}",
                            }
                        };
                    }
                );

                // Prevenir acciones de descarga
                adobeDCView.registerCallback(
                    AdobeDC.View.Enum.CallbackType.SAVE_API,
                    function(metaData) {
                        return false; // Previene la descarga
                    }
                );

            } catch (error) {
                console.error("Error loading Adobe DC View:", error);
                document.querySelector('.loading-message').innerHTML = `
                    <div class="error-message">
                        ${app.getLocale() == 'fr' 
                            ? 'Erreur lors du chargement du PDF.' 
                            : 'Error loading PDF.'}
                    </div>
                `;
            }
        });
    </script>
@endsection
