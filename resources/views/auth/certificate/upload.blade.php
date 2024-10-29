@extends('layouts.applayout')

@section('content')
<div class="container">
    <h2>Subir Certificado de Estudiante</h2>
    <form action="{{ route('certificate.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="category_id" value="{{ $category_id }}">
        <div class="form-group">
            <label for="certificate">Certificado (PDF)</label>
            <input type="file" class="form-control-file" id="certificate" name="certificate" accept=".pdf" required>
        </div>
        <button type="submit" class="btn btn-primary">Subir Certificado</button>
    </form>
</div>
@endsection