@extends('layouts.applayout')

@section('title', "Art d'Histoire | Profil")

@section('content')

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h2 class="text-center">{{ app()->getLocale() == 'fr' ? 'Profil Utilisateur' : 'User Profile' }}</h2>
                    </div>
                    <div class="card-body">
                        <ul class="nav nav-pills mb-3" id="profileTabs" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="account-tab" data-toggle="pill" href="#account"
                                    role="tab" aria-controls="account"
                                    aria-selected="true">{{ app()->getLocale() == 'fr' ? 'Données du Compte' : 'Account Data' }}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="subscription-tab" data-toggle="pill" href="#subscription"
                                    role="tab" aria-controls="subscription"
                                    aria-selected="false">{{ app()->getLocale() == 'fr' ? 'Abonnement' : 'Subscription' }}</a>
                            </li>
                        </ul>

                        <div class="tab-content" id="profileTabsContent">
                            <!-- Données du Compte -->
                            <div class="tab-pane fade show active" id="account" role="tabpanel"
                                aria-labelledby="account-tab">
                                <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')

                                    <div class="form-group">
                                        <label for="name">{{ app()->getLocale() == 'fr' ? 'Nom' : 'Name' }}</label>
                                        <input type="text" class="form-control" id="name" name="name"
                                            value="{{ $user->name }}" required>
                                    </div>

                                    <div class="form-group">
                                        <label
                                            for="email">{{ app()->getLocale() == 'fr' ? 'Adresse e-mail' : 'Email' }}</label>
                                        <input type="email" class="form-control" id="email" name="email"
                                            value="{{ $user->email }}" required>
                                    </div>

                                    <div class="form-group">
                                        <label for="avatar">{{ app()->getLocale() == 'fr' ? 'Avatar' : 'Avatar' }}</label>
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

                                    <button type="submit"
                                        class="btn btn-outline-secondary btn-block">{{ app()->getLocale() == 'fr' ? 'Mettre à jour les données' : 'Update Data' }}</button>
                                </form>
                                <hr class="my-4">

                                <h4>{{ app()->getLocale() == 'fr' ? 'Changer le mot de passe' : 'Change Password' }}</h4>
                                <form action="{{ route('profile.update.password') }}" method="POST">
                                    @csrf
                                    @method('PUT')

                                    <div class="form-group">
                                        <label
                                            for="current_password">{{ app()->getLocale() == 'fr' ? 'Mot de passe actuel' : 'Current Password' }}</label>
                                        <div class="input-group">
                                            <input type="password" class="form-control" id="current_password"
                                                name="current_password" required>
                                            <div class="input-group-append">
                                                <button class="btn btn-outline-secondary toggle-password" type="button"
                                                    data-target="current_password">
                                                    <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-eye"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" /><path d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6" /></svg>
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label
                                            for="new_password">{{ app()->getLocale() == 'fr' ? 'Nouveau mot de passe' : 'New Password' }}</label>
                                        <div class="input-group">
                                            <input type="password" class="form-control" id="new_password"
                                                name="new_password" required>
                                            <div class="input-group-append">
                                                <button class="btn btn-outline-secondary toggle-password" type="button"
                                                    data-target="new_password">
                                                    <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-eye"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" /><path d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6" /></svg>
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label
                                            for="new_password_confirmation">{{ app()->getLocale() == 'fr' ? 'Confirmer le nouveau mot de passe' : 'Confirm New Password' }}</label>
                                        <div class="input-group">
                                            <input type="password" class="form-control" id="new_password_confirmation"
                                                name="new_password_confirmation" required>
                                            <div class="input-group-append">
                                                <button class="btn btn-outline-secondary toggle-password" type="button"
                                                    data-target="new_password_confirmation">
                                                    <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-eye"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" /><path d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6" /></svg>
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    <button type="submit"
                                        class="btn btn-outline-secondary btn-block mt-4">{{ app()->getLocale() == 'fr' ? 'Mettre à jour le mot de passe' : 'Update Password' }}</button>
                                </form>
                            </div>

                            <!-- Détails des Abonnements -->
                            <div class="tab-pane fade" id="subscription" role="tabpanel"
                                aria-labelledby="subscription-tab">
                                <h3>{{ app()->getLocale() == 'fr' ? 'Détails des Abonnements' : 'Subscription Details' }}
                                </h3>
                                @if ($activeSubscriptions->isNotEmpty())
                                    @foreach ($activeSubscriptions as $subscription)
                                        @foreach ($subscription->categories as $category)
                                            <div class="card mb-3">
                                                <div class="card-body">
                                                    <h5 class="card-title">{{ $category->name }}</h5>
                                                    <p class="card-text">
                                                        {{ app()->getLocale() == 'fr' ? 'Date de fin :' : 'End date:' }}
                                                        {{ Carbon\Carbon::parse($subscription->end_date)->format('d/m/Y') }}
                                                    </p>
                                                    <a href="{{ route('interactive.show', $category->id) }}"
                                                        class="btn btn-primary">
                                                        {{ app()->getLocale() == 'fr' ? 'Accéder à la saga' : 'Go to saga' }}
                                                    </a>
                                                </div>
                                            </div>
                                        @endforeach
                                    @endforeach
                                @else
                                    <p>{{ app()->getLocale() == 'fr' ? 'Vous n\'avez aucun abonnement actif.' : 'You don\'t have any active subscriptions.' }}
                                    </p>
                                    <a href="{{ route('interactive.index') }}" class="btn btn-outline-secondary">
                                        {{ app()->getLocale() == 'fr' ? 'Voir les sagas disponibles' : 'View available sagas' }}
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="text-center mt-4">
                    <a href="{{ route('home') }}"
                        class="btn btn-outline-secondary">{{ app()->getLocale() == 'fr' ? 'Retour à l\'accueil' : 'Back to Home' }}</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.querySelectorAll('.toggle-password').forEach(button => {
            button.addEventListener('click', function() {
                const input = document.getElementById(this.getAttribute('data-target'));
                if (input.type === 'password') {
                    input.type = 'text';
                    this.innerHTML = '<i class="fa fa-eye-slash"></i>';
                } else {
                    input.type = 'password';
                    this.innerHTML = '<i class="fa fa-eye"></i>';
                }
            });
        });
    </script>

@endsection
