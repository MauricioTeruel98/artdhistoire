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
                                    role="tab" aria-controls="account" aria-selected="true">{{ app()->getLocale() == 'fr' ? 'Données du Compte' : 'Account Data' }}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="subscription-tab" data-toggle="pill" href="#subscription"
                                    role="tab" aria-controls="subscription" aria-selected="false">{{ app()->getLocale() == 'fr' ? 'Abonnement' : 'Subscription' }}</a>
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
                                        <label for="email">{{ app()->getLocale() == 'fr' ? 'Adresse e-mail' : 'Email' }}</label>
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

                                    <button type="submit" class="btn btn-primary btn-block">{{ app()->getLocale() == 'fr' ? 'Mettre à jour les données' : 'Update Data' }}</button>
                                </form>
                            </div>

                            <!-- Détails de l'Abonnement -->
                            <div class="tab-pane fade" id="subscription" role="tabpanel" aria-labelledby="subscription-tab">
                                <h3>{{ app()->getLocale() == 'fr' ? 'Détails de l\'Abonnement' : 'Subscription Details' }}</h3>
                                @if ($user->subscription && $user->subscription->isActive())
                                    <p>{{ app()->getLocale() == 'fr' ? 'Statut :' : 'Status:' }} <strong class="text-success">{{ app()->getLocale() == 'fr' ? 'Actif' : 'Active' }}</strong></p>
                                    <p>{{ app()->getLocale() == 'fr' ? 'Méthode de paiement :' : 'Payment method:' }} {{ ucfirst($user->subscription->payment_method) }}</p>
                                    <p>{{ app()->getLocale() == 'fr' ? 'Date de début :' : 'Start date:' }}
                                        {{ \Carbon\Carbon::parse($user->subscription->start_date)->format('d/m/Y') }}</p>
                                    <p>{{ app()->getLocale() == 'fr' ? 'Date de fin :' : 'End date:' }}
                                        {{ \Carbon\Carbon::parse($user->subscription->end_date)->format('d/m/Y') }}</p>
                                @else
                                    <p>{{ app()->getLocale() == 'fr' ? 'Vous n\'avez aucun abonnement actif.' : 'You don\'t have any active subscription.' }}</p>
                                    <form action="{{ route('subscription.trial') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-primary">{{ app()->getLocale() == 'fr' ? 'Activer l\'abonnement d\'essai (7 jours)' : 'Activate trial subscription (7 days)' }}</button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="text-center mt-4">
                    <a href="{{ route('home') }}" class="btn btn-secondary">{{ app()->getLocale() == 'fr' ? 'Retour à l\'accueil' : 'Back to Home' }}</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>

@endsection
