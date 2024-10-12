@extends('layouts.applayout')

@section('title', "Art d'Histoire | Suscripción Requerida")

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Suscripción Requerida</div>
                <div class="card-body">
                    <h2 class="mb-4">Acceso Restringido</h2>
                    <p>Para acceder a este contenido, necesitas una suscripción activa.</p>
                    <p>Elige una de nuestras opciones de suscripción para disfrutar de todo el contenido:</p>
                    <div class="mt-4">
                        <a href="{{ route('interactive.index') }}" class="btn btn-primary">Ver Planes de Suscripción</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection