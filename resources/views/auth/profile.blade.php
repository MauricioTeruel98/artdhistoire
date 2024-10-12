@extends('layouts.applayout')

@section('title', "Art d'Histoire | Perfil")

@section('content')

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h2 class="text-center">Perfil de Usuario</h2>
                    </div>
                    <div class="card-body">
                        <ul class="nav nav-pills mb-3" id="profileTabs" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="account-tab" data-toggle="pill" href="#account"
                                    role="tab" aria-controls="account" aria-selected="true">Datos de la Cuenta</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="subscription-tab" data-toggle="pill" href="#subscription"
                                    role="tab" aria-controls="subscription" aria-selected="false">Suscripción</a>
                            </li>
                        </ul>

                        <div class="tab-content" id="profileTabsContent">
                            <!-- Datos de la Cuenta -->
                            <div class="tab-pane fade show active" id="account" role="tabpanel"
                                aria-labelledby="account-tab">
                                <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')

                                    <div class="form-group">
                                        <label for="name">Nombre</label>
                                        <input type="text" class="form-control" id="name" name="name"
                                            value="{{ $user->name }}" required>
                                    </div>

                                    <div class="form-group">
                                        <label for="email">Correo Electrónico</label>
                                        <input type="email" class="form-control" id="email" name="email"
                                            value="{{ $user->email }}" required>
                                    </div>

                                    <div class="form-group">
                                        <label for="avatar">Avatar</label>
                                        <div class="mb-3">
                                            <input type="file" class="form-control-file" id="avatar" name="avatar">
                                        </div>
                                        @if ($user->avatar)
                                            <div class="mb-3">
                                                <img src="{{ asset('storage/' . $user->avatar) }}" alt="Avatar"
                                                    class="img-thumbnail" width="150">
                                            </div>
                                        @endif
                                    </div>

                                    <button type="submit" class="btn btn-primary btn-block">Actualizar Datos</button>
                                </form>
                            </div>

                            <!-- Datos de Suscripción -->
                            <div class="tab-pane fade" id="subscription" role="tabpanel" aria-labelledby="subscription-tab">
                                <h3>Detalles de la Suscripción</h3>
                                @if ($user->subscription && $user->subscription->isActive())
                                    <p>Estado: <strong class="text-success">Activa</strong></p>
                                    <p>Método de pago: {{ ucfirst($user->subscription->payment_method) }}</p>
                                    <p>Fecha de inicio:
                                        {{ \Carbon\Carbon::parse($user->subscription->start_date)->format('d/m/Y') }}</p>
                                    <p>Fecha de finalización:
                                        {{ \Carbon\Carbon::parse($user->subscription->end_date)->format('d/m/Y') }}</p>
                                @else
                                    <p>No tienes ninguna suscripción activa.</p>
                                    <form action="{{ route('subscription.trial') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-primary">Activar suscripción de prueba (7
                                            días)</button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="text-center mt-4">
                    <a href="{{ route('home') }}" class="btn btn-secondary">Volver al inicio</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>

@endsection
