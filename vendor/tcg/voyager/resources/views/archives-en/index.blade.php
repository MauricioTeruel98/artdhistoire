@extends('voyager::master')

@section('page_title', 'Archives du Post')

@section('page_header')
    <h1 class="page-title">
        <i class="voyager-file-text"></i> Archives du Post : {{ $post->title }}
    </h1>
@stop

@section('content')
    <div class="page-content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-bordered">
                    <div class="panel-body">
                        <form action="{{ route('voyager.archives.en.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="post_id" value="{{ $post->id }}">
                            <div class="form-group">
                                <label for="title">Titre (English)</label>
                                <input type="text" name="title" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="file">Fichier PDF (English)</label>
                                <input type="file" name="file" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="type">Type</label>
                                <select name="type" class="form-control" required>
                                    <option value="nonDisponible">Non Disponible</option>
                                    <option value="contexto">Contexte artistique</option>
                                    <option value="teoria">Théorie</option>
                                    <option value="bio">Élément biographique</option>
                                    <option value="social">Contexte social ou scientifique</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary">Télécharger les fichiers</button>
                        </form>

                        <hr>

                        <h3>Fichiers existants</h3>
                        <a href="#" id="reorderArchives" class="btn btn-primary">
                            <span>Enregistrer l'ordre des archives</span>
                        </a>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Ordre</th>
                                    <th>Titre (EN)</th>
                                    <th>Type</th>
                                    <th>Fichier (EN)</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="sortable-archives">
                                @foreach ($archives as $archive)
                                    <tr data-id="{{ $archive->id }}">
                                        <td class="handle"><i class="voyager-handle"></i></td>
                                        <td>{{ $archive->title }}</td>
                                        <td>
                                            @switch($archive->type)
                                                @case('nonDisponible')
                                                    Non Disponible
                                                @break

                                                @case('contexto')
                                                    Contexte artistique
                                                @break

                                                @case('teoria')
                                                    Théorie
                                                @break

                                                @case('bio')
                                                    Élément biographique
                                                @break

                                                @case('social')
                                                    Contexte social ou scientifique
                                                @break

                                                @default
                                                    {{ $archive->type }}
                                            @endswitch
                                        </td>
                                        <td>
                                            <a href="{{ Storage::url($archive->route) }}" target="_blank">Voir le fichier
                                                (EN)
                                            </a>
                                        </td>
                                        <td>
                                            <a href="{{ route('voyager.archives.en.edit.edit', $archive->id) }}"
                                                class="btn btn-sm btn-primary">Modifier</a>
                                            <form action="{{ route('voyager.archives.en.destroy.destroy', $archive->id) }}"
                                                method="POST" style="display: inline-block;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger"
                                                    onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce fichier ?')">Supprimer</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('javascript')
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
    <script>
        $(document).ready(function() {
            $("#sortable-archives").sortable({
                handle: '.handle',
                update: function(event, ui) {
                    // Esta función se llama cuando se cambia el orden
                }
            });

            $("#reorderArchives").on('click', function(e) {
                e.preventDefault();
                var order = $("#sortable-archives").sortable("toArray", {
                    attribute: 'data-id'
                });
                $.post('{{ route('archives.reorder') }}', {
                    order: order,
                    _token: '{{ csrf_token() }}'
                }, function(data) {
                    toastr.success('El orden de los archivos ha sido actualizado.');
                });
            });
        });
    </script>
@stop
