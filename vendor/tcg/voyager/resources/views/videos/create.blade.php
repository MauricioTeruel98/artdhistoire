@extends('voyager::master')

@section('page_title', 'Ajouter une vidéo')

@php
    $edit = false;
    $dataType = Voyager::model('DataType')->where('model_name', 'App\\Models\\Video')->first();
    $dataTypeContent = new App\Models\Video();
@endphp

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
        <i class="voyager-video"></i> Ajouter une vidéo à {{ $videoOnline->title }} (Français)
    </h1>
@stop

@section('content')
    <div class="page-content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-bordered">
                    <form class="form-edit-add" role="form"
                        action="{{ route('videoonline.videos.store', $videoOnline->id) }}" method="POST"
                        enctype="multipart/form-data" id="videoForm">
                        @csrf
                        <div class="panel-body">

                            <div class="alert alert-info">
                                <strong>Instructions:</strong> Tout d'abord, téléchargez la vidéo, puis complétez les autres
                                champs.
                            </div>

                            <div class="form-group">
                                <label for="videoFile">Vidéo</label>
                                <div class="mb-3">
                                    <input type="file" id="videoFile" accept="video/*">
                                    <input type="hidden" name="videoUrl" id="videoUrl">
                                    <button type="button" class="btn btn-success" onclick="uploadFile()"
                                        id="uploadBtn">Télécharger la vidéo</button>
                                    <button type="button" class="btn btn-danger" onclick="cancelUpload()" id="cancelBtn"
                                        style="display: none;">Annuler</button>
                                </div>

                                <hr>

                                <div class="mb-3">
                                    <label for="directVideoUrl">Ou entrez directement l'URL de la vidéo (Remplacer "/view"
                                        par "/preview"):</label>
                                    <label for="directVideoUrl" style="font-size: 12px;">Pour copier l'URL de la vidéo, vous
                                        devez faire un clic droit sur la vidéo, dans le dossier google drive, et cliquer sur
                                        l'option d'affichage dans une nouvelle fenêtre.</label>
                                    <input type="text" class="form-control" id="directVideoUrl"
                                        placeholder="https://drive.google.com/file/d/...">
                                    <button type="button" class="btn btn-primary mt-2" onclick="useDirectUrl()">Utiliser
                                        cette
                                        URL</button>
                                </div>

                                <hr>

                                <div class="form-group">
                                    <label for="iframe">Extrait de code d'une vidéo Youtube (Laisser vide en cas de
                                        téléchargement local de la vidéo)</label>
                                    <textarea class="form-control" id="iframe" name="iframe" rows="3" placeholder="Lien vers une vidéo YouTube">{{ old('iframe') }}</textarea>
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
                                    placeholder="Titre de la vidéo en Français" value="{{ old('title') }}" disabled>
                            </div>

                            <div class="form-group">
                                <label for="text">Texte</label>
                                @php
                                    $dataTypeRows = $dataType->{$edit ? 'editRows' : 'addRows'};
                                    $row = $dataTypeRows->where('field', 'text')->first();
                                @endphp
                                {!! app('voyager')->formField($row, $dataType, $dataTypeContent, ['disabled' => true]) !!}
                            </div>

                            <div class="form-group">
                                <label for="imagen">Image (1280 x 720)</label>
                                @if (isset($dataTypeContent->imagen))
                                    <img src="{{ filter_var($dataTypeContent->imagen, FILTER_VALIDATE_URL) ? $dataTypeContent->imagen : Voyager::image($dataTypeContent->imagen) }}"
                                        style="width:200px; height:auto; clear:both; display:block; padding:2px; border:1px solid #ddd; margin-bottom:10px;" />
                                @endif
                                <input type="file" name="imagen" accept="image/*" disabled>
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

                            fetch('/upload-chunk', {
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
                                        document.getElementById('submitBtn').disabled = false;

                                        // Habilitar campos
                                        document.querySelectorAll('input:not([type="file"]), textarea').forEach(el => {
                                            if (el.id !== 'videoFile') {
                                                el.disabled = false;
                                            }
                                        });

                                        // Verificar si TinyMCE está inicializado antes de intentar usarlo
                                        if (typeof tinymce !== 'undefined' && tinymce.get('text')) {
                                            tinymce.get('text').setMode('design');
                                        }

                                        setTimeout(() => {
                                            alert(
                                                'Vidéo téléchargée avec succès. Vous pouvez maintenant remplir le formulaire.'
                                            );
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

                            // Habilitar los campos del formulario
                            document.getElementById('submitBtn').disabled = false;
                            document.querySelectorAll('input:not([type="file"]), textarea').forEach(el => {
                                if (el.id !== 'videoFile') {
                                    el.disabled = false;
                                }
                            });

                            // Habilitar TinyMCE si está presente
                            if (typeof tinymce !== 'undefined' && tinymce.get('text')) {
                                tinymce.get('text').setMode('design');
                            }

                            alert('Code iframe YouTube accepté. Vous pouvez maintenant continuer à remplir le formulaire.');
                        }

                        function useDirectUrl() {
                            const directUrl = document.getElementById('directVideoUrl').value;
                            if (!directUrl) {
                                alert('Veuillez entrer une URL valide');
                                return;
                            }

                            // Validar URL de Google Drive
                            if (!directUrl.includes('drive.google.com')) {
                                alert('Veuillez entrer une URL valide de Google Drive');
                                return;
                            }

                            // Convertir l'URL au format de prévisualisation si nécessaire
                            let previewUrl = directUrl;
                            if (directUrl.includes('/view?usp=sharing')) {
                                previewUrl = directUrl.replace('/view?usp=sharing', '/preview');
                            } else if (!directUrl.includes('/preview')) {
                                previewUrl = directUrl.replace(/\/[^\/]*$/, '/preview');
                            }

                            document.getElementById('videoUrl').value = previewUrl;
                            document.getElementById('submitBtn').disabled = false;

                            // Activer les champs
                            document.querySelectorAll('input:not([type="file"]), textarea').forEach(el => {
                                if (el.id !== 'videoFile') {
                                    el.disabled = false;
                                }
                            });

                            // Vérifier si TinyMCE est initialisé avant de tenter de l'utiliser
                            if (typeof tinymce !== 'undefined' && tinymce.get('text')) {
                                tinymce.get('text').setMode('design');
                            }

                            alert('URL de la vidéo établie correctement. Vous pouvez maintenant continuer à remplir le formulaire.');
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
                        };
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
