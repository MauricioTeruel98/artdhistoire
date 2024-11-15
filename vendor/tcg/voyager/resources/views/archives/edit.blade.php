@extends('voyager::master')

@section('page_title', 'Éditer un Archive')

@section('page_header')
    <h1 class="page-title">
        <i class="voyager-file-text"></i> Éditer un Archive: {{ $archive->title }}
    </h1>
@stop

@section('content')
    <div class="page-content container-fluid">
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-bordered">
                    <div class="panel-body">
                        <form action="{{ route('voyager.archives.update', $archive->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('POST')
                            <input type="hidden" name="post_id" value="{{ $post->id }}">
                            <div class="form-group">
                                <label for="title">Titre (Français)</label>
                                <input type="text" name="title" class="form-control" required value="{{ $archive->title }}">
                            </div>
                            <div class="form-group">
                                <label for="file">Fichier PDF (Français)</label>
                                <input type="file" name="file" class="form-control">
                                <small>Laisser vide pour conserver le fichier actuel</small>
                            </div>
                            <div class="form-group">
                                <label for="type">Type</label>
                                <select name="type" class="form-control" required>
                                    <option value="nonDisponible" {{ $archive->type == 'nonDisponible' ? 'selected' : '' }}>Non Disponible</option>
                                    <option value="contexto" {{ $archive->type == 'contexto' ? 'selected' : '' }}>Contexte artistique</option>
                                    <option value="teoria" {{ $archive->type == 'teoria' ? 'selected' : '' }}>Théorie</option>
                                    <option value="bio" {{ $archive->type == 'bio' ? 'selected' : '' }}>Élément biographique</option>
                                    <option value="social" {{ $archive->type == 'social' ? 'selected' : '' }}>Contexte social ou scientifique</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary">Mettre à jour l'Archive</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop