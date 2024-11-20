@extends('voyager::master')

@php
    $edit = true;
    $dataType = Voyager::model('DataType')->where('model_name', 'App\\Models\\Video')->first();
    $dataTypeContent = $video;
@endphp

@section('page_title', 'Éditer une vidéo')

@section('css')
    <style>
        .panel .mce-panel {
            border-left-color: #fff;
            border-right-color: #fff;
        }

        .panel .mce-toolbar,
        .panel .mce-statusbar {
            padding-left: 20px;
        }

        .panel .mce-edit-area,
        .panel .mce-edit-area iframe,
        .panel .mce-edit-area iframe html {
            padding: 0 10px;
            min-height: 350px;
        }

        .mce-content-body {
            color: #555;
            font-size: 14px;
        }

        .panel.is-fullscreen .mce-statusbar {
            position: absolute;
            bottom: 0;
            width: 100%;
            z-index: 200000;
        }

        .panel.is-fullscreen .mce-tinymce {
            height: 100%;
        }

        .panel.is-fullscreen .mce-edit-area,
        .panel.is-fullscreen .mce-edit-area iframe,
        .panel.is-fullscreen .mce-edit-area iframe html {
            height: 100%;
            position: absolute;
            width: 99%;
            overflow-y: scroll;
            overflow-x: hidden;
            min-height: 100%;
        }

        .progress {
            display: none;
            margin-top: 10px;
        }
    </style>
@stop

@section('page_header')
    <h1 class="page-title">
        <i class="voyager-video"></i> Éditer une vidéo de {{ $videoOnline->title }} (Anglais)
    </h1>
@stop

@section('content')
    <div class="page-content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-bordered">
                    <form class="form-edit-add" role="form" action="{{ route('videoonline.en.videos.update', $video->id) }}"
                        method="POST" enctype="multipart/form-data" id="videoForm">
                        @csrf
                        @method('PUT')
                        <div class="panel-body">
                            <div class="alert alert-info">
                                <strong>Instructions:</strong> Tout d'abord, téléchargez la vidéo, puis complétez les autres
                                champs.
                            </div>

                            <div class="form-group">
                                <label for="videoFile">Vidéo actuelle</label>
                                @if ($video->video)
                                    <div class="mb-3">
                                        <iframe src="{{ $video->video }}" width="100%" height="315" allow="autoplay"
                                            frameborder="0" allowfullscreen>
                                        </iframe>
                                        <button type="button" class="btn btn-danger mt-2" onclick="removeVideo()">
                                            Eliminar vídeo actual
                                        </button>
                                    </div>
                                @endif

                                <input type="hidden" name="videoUrl" id="videoUrl" value="{{ $video->video }}">

                                <div class="mb-3">
                                    <label for="directVideoUrl">Ou entrez directement l'URL de la vidéo (Remplacer "/view"
                                        par "/preview"):</label>
                                    <label for="directVideoUrl" style="font-size: 12px;">Pour copier l'URL de la vidéo, vous
                                        devez faire un clic droit sur la vidéo, dans le dossier google drive, et cliquer sur
                                        l'option d'affichage dans une nouvelle fenêtre.</label>
                                    <input type="text" class="form-control" id="directVideoUrl"
                                        placeholder="https://drive.google.com/file/d/...">
                                    <button type="button" class="btn btn-primary mt-2" onclick="useDirectUrl()">Utiliser
                                        cette URL</button>
                                </div>

                                <hr>

                                <div class="form-group">
                                    <label for="iframe">Extrait de code d'une vidéo Youtube</label>
                                    <textarea class="form-control" id="iframe" name="iframe" rows="3" placeholder="Lien vers une vidéo YouTube">{{ old('iframe', $video->iframe) }}</textarea>
                                    <button type="button" class="btn btn-primary mt-2" onclick="useIframe()">Utiliser
                                        l'iframe YouTube</button>
                                </div>

                                <div class="progress" style="margin-top: 10px;">
                                    <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar"
                                        style="width: 0%">0%</div>
                                </div>
                            </div>

                            <hr>

                            <div class="form-group">
                                <label for="title">Titre</label>
                                <input type="text" class="form-control" id="title" name="title"
                                    placeholder="Titre de la vidéo en Français" value="{{ old('title', $video->title) }}">
                            </div>

                            {{-- <div class="form-group">
                                <label for="iframe">Extrait de code d'une vidéo Youtube (Laisser vide en cas de
                                    téléchargement local de la vidéo)</label>
                                <textarea class="form-control" id="iframe" name="iframe" rows="3" placeholder="Lien vers une vidéo YouTube">{{ old('iframe', $video->iframe) }}</textarea>
                            </div> --}}

                            <div class="form-group">
                                <label for="text">Texte</label>
                                @php
                                    $dataTypeRows = $dataType->{$edit ? 'editRows' : 'addRows'};
                                    $row = $dataTypeRows->where('field', 'text')->first();
                                @endphp
                                {!! app('voyager')->formField($row, $dataType, $dataTypeContent) !!}
                            </div>

                            <div class="form-group">
                                <label for="imagen">Image (1280 x 720)</label>
                                @if ($video->imagen)
                                    <img src="{{ $video->imagen }}"
                                        style="width:200px; height:auto; clear:both; display:block; padding:2px; border:1px solid #ddd; margin-bottom:10px;" />
                                @endif
                                <input type="file" name="imagen" accept="image/*">
                            </div>
                        </div>

                        <div class="panel-footer">
                            <button type="submit" class="btn btn-primary save" id="submitBtn" disabled>Enregistrer la
                                vidéo</button>
                        </div>
                    </form>

                    <script>
                        let uploadCancelled = false;

                        function uploadFile() {
                            uploadCancelled = false;
                            const file = document.getElementById('videoFile').files[0];
                            if (!file) {
                                alert('Veuillez sélectionner un fichier vidéo');
                                return;
                            }

                            // Mostrar/ocultar botones
                            document.getElementById('uploadBtn').style.display = 'none';
                            document.getElementById('cancelBtn').style.display = 'inline-block';

                            const formData = new FormData();
                            formData.append('video', file);
                            formData.append('fileName', file.name);
                            formData.append('_token', '{{ csrf_token() }}');

                            const progressBar = document.querySelector('.progress-bar');
                            const progress = document.querySelector('.progress');
                            progress.style.display = 'block';

                            // Simulación de progreso mientras se procesa
                            let width = 0;
                            const simulateProgress = setInterval(() => {
                                if (uploadCancelled) {
                                    clearInterval(simulateProgress);
                                    return;
                                }
                                if (width < 90) {
                                    width += Math.random() * 2;
                                    progressBar.style.width = width + '%';
                                    progressBar.textContent = Math.round(width) + '%';
                                }
                            }, 200);

                            fetch('/upload-chunk-en', {
                                    method: 'POST',
                                    body: formData,
                                })
                                .then(response => response.json())
                                .then(data => {
                                    clearInterval(simulateProgress);

                                    if (data.success) {
                                        progressBar.style.width = '100%';
                                        progressBar.textContent = '100%';
                                        document.getElementById('videoUrl').value = data.videoUrl;

                                        setTimeout(() => {
                                            alert('Vidéo téléchargée avec succès.');
                                            document.getElementById('uploadBtn').style.display = 'inline-block';
                                            document.getElementById('cancelBtn').style.display = 'none';
                                        }, 500);
                                    } else if (data.needsAuth) {
                                        alert('Authentification requise. Vous allez être redirigé vers la page de connexion Google.');
                                        window.location.href = data.authUrl;
                                    } else {
                                        handleUploadError('Erreur lors du téléchargement: ' + (data.error || 'Erreur inconnue'));
                                    }
                                })
                                .catch(error => {
                                    clearInterval(simulateProgress);
                                    handleUploadError('Erreur lors du téléchargement: ' + error.message);
                                });
                        }

                        function useIframe() {
                            const iframeContent = document.getElementById('iframe').value;
                            if (!iframeContent) {
                                alert('Veuillez entrer un code iframe YouTube valide');
                                return;
                            }

                            // Habilitar el botón de envío
                            document.getElementById('submitBtn').disabled = false;

                            // Limpiar el campo de URL de video si existe
                            if (document.getElementById('videoUrl')) {
                                document.getElementById('videoUrl').value = '';
                            }

                            alert('Code iframe YouTube accepté. Vous pouvez maintenant sauvegarder les modifications.');
                        }

                        function useDirectUrl() {
                            const directUrl = document.getElementById('directVideoUrl').value;
                            if (!directUrl) {
                                alert('Veuillez entrer une URL valide');
                                return;
                            }

                            if (!directUrl.includes('drive.google.com')) {
                                alert('Veuillez entrer une URL valide de Google Drive');
                                return;
                            }

                            let previewUrl = directUrl;
                            if (directUrl.includes('/view?usp=sharing')) {
                                previewUrl = directUrl.replace('/view?usp=sharing', '/preview');
                            } else if (!directUrl.includes('/preview')) {
                                previewUrl = directUrl.replace(/\/[^\/]*$/, '/preview');
                            }

                            // Limpiar el iframe si existe
                            document.getElementById('iframe').value = '';

                            document.getElementById('videoUrl').value = previewUrl;
                            document.getElementById('submitBtn').disabled = false;

                            // Actualizar la vista previa del iframe
                            const existingIframe = document.querySelector('iframe');
                            if (existingIframe) {
                                existingIframe.src = previewUrl;
                            } else {
                                const newIframe = document.createElement('iframe');
                                newIframe.src = previewUrl;
                                newIframe.width = '100%';
                                newIframe.height = '315';
                                newIframe.allow = 'autoplay';
                                newIframe.frameBorder = '0';
                                newIframe.allowFullscreen = true;

                                const container = document.querySelector('.form-group');
                                container.insertBefore(newIframe, container.firstChild);
                            }

                            alert('URL de la vidéo établie correctement. Vous pouvez maintenant sauvegarder les modifications.');
                        }

                        function handleUploadError(message) {
                            console.error('Error:', message);
                            alert(message);
                            const progressBar = document.querySelector('.progress-bar');
                            progressBar.style.width = '0%';
                            progressBar.textContent = '0%';
                            document.querySelector('.progress').style.display = 'none';
                            document.getElementById('uploadBtn').style.display = 'inline-block';
                            document.getElementById('cancelBtn').style.display = 'none';
                        }

                        function cancelUpload() {
                            uploadCancelled = true;
                            const progressBar = document.querySelector('.progress-bar');
                            progressBar.style.width = '0%';
                            progressBar.textContent = '0%';
                            document.querySelector('.progress').style.display = 'none';
                            document.getElementById('uploadBtn').style.display = 'inline-block';
                            document.getElementById('cancelBtn').style.display = 'none';
                        }
                        // Manejar el retorno de la autenticación de Google
                        window.onload = function() {
                            @if (session('success'))
                                alert('{{ session('success') }}');
                            @endif

                            @if (session('error'))
                                alert('{{ session('error') }}');
                            @endif

                            const urlParams = new URLSearchParams(window.location.search);
                            const code = urlParams.get('code');

                            if (code) {
                                fetch('/google-drive/callback?code=' + code)
                                    .then(response => response.json())
                                    .then(data => {
                                        if (data.success) {
                                            alert('Authentification réussie. Vous pouvez maintenant télécharger votre vidéo.');
                                        } else {
                                            alert('Erreur d\'authentification: ' + (data.error || 'Erreur inconnue'));
                                        }
                                    })
                                    .catch(error => {
                                        console.error('Error:', error);
                                        alert('Erreur d\'authentification: ' + error.message);
                                    });
                            }

                            const videoUrl = document.getElementById('videoUrl').value;
                            const iframe = document.getElementById('iframe').value;
                            if (videoUrl || iframe) {
                                document.getElementById('submitBtn').disabled = false;
                            }
                        };

                        function removeVideo() {
                            if (confirm('¿Está seguro que desea eliminar el vídeo actual?')) {
                                // Limpiar el campo oculto
                                document.getElementById('videoUrl').value = '';
                                
                                // Remover el iframe
                                const iframe = document.querySelector('iframe');
                                if (iframe) {
                                    iframe.parentElement.remove(); // Removemos todo el contenedor
                                }
                                
                                // Habilitar el botón de guardar
                                document.getElementById('submitBtn').disabled = false;
                                
                                // Mostrar mensaje al usuario
                                alert('Por favor, guarde los cambios para confirmar la eliminación del video.');
                            }
                        }
                    </script>
                </div>
            </div>
        </div>
    </div>
@stop

@section('javascript')
    <script>
        $('document').ready(function() {
            $('.toggleswitch').bootstrapToggle();

            $('.side-body input[data-slug-origin]').each(function(i, el) {
                $(el).slugify();
            });
        });
    </script>
@stop
