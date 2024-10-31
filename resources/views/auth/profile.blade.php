@extends('layouts.applayout')

@section('title', "Art d'Histoire | Profile")

@section('header')
<style>
    .profile-container {
        background-color: #fff;
        padding: 3rem;
        border-radius: 10px;
        box-shadow: 0 0 20px rgba(0,0,0,0.1);
        margin-top: 2rem;
    }

    .profile-header {
        text-align: center;
        margin-bottom: 2rem;
    }

    .profile-header h2 {
        color: #322668;
        font-size: 2rem;
        margin-bottom: 1rem;
    }

    .nav-pills .nav-link {
        color: #757575;
        border-radius: 50px;
        padding: 0.8rem 2rem;
        margin: 0 0.5rem;
        transition: all 0.3s ease;
    }

    .nav-pills .nav-link.active {
        background-color: #322668;
        color: white;
    }

    .form-group label {
        color: #322668;
        font-weight: 500;
        margin-bottom: 0.5rem;
    }

    .form-control {
        border-radius: 8px;
        border: 1px solid #e0e0e0;
        padding: 0.8rem;
    }

    .btn-update {
        background-color: #322668;
        color: white;
        padding: 0.8rem 2rem;
        border: none;
        border-radius: 50px;
        font-size: 1.1rem;
        transition: all 0.3s ease;
        margin-top: 1.5rem;
        width: 100%;
    }

    .btn-update:hover {
        background-color: #3F7652;
        color: white;
    }

    .subscription-card {
        border: none;
        border-radius: 8px;
        box-shadow: 0 0 15px rgba(0,0,0,0.05);
        margin-bottom: 1.5rem;
        transition: all 0.3s ease;
        padding: 1.5rem;
    }

    .subscription-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }

    .avatar-container {
        text-align: center;
        margin-bottom: 2rem;
    }

    .avatar-preview {
        width: 150px;
        height: 150px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid #322668;
        margin-bottom: 1rem;
    }

    .password-section {
        background-color: #f8f9fa;
        padding: 2rem;
        border-radius: 8px;
        margin-top: 2rem;
    }

    .toggle-password {
        border: none;
        background: transparent;
        color: #322668;
        cursor: pointer;
    }

    .tab-content {
        padding: 2rem;
        background-color: #fff;
        border-radius: 0 0 8px 8px;
    }

    .student-badge {
        background-color: #3F7652;
        color: white;
        padding: 0.3rem 1rem;
        border-radius: 50px;
        font-size: 0.9rem;
        display: inline-block;
        margin-top: 0.5rem;
    }
</style>
@endsection

@section('content')
<div class="container profile-container">
    <div class="profile-header">
        <h2>{{ app()->getLocale() == 'fr' ? 'Profil Utilisateur' : 'User Profile' }}</h2>
        @if($user->is_student)
            <span class="student-badge">
                {{ app()->getLocale() == 'fr' ? 'Compte Étudiant' : 'Student Account' }}
            </span>
        @endif
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-10">
            <ul class="nav nav-pills mb-4 justify-content-center" id="profileTabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="account-tab" data-toggle="pill" href="#account" role="tab">
                        {{ app()->getLocale() == 'fr' ? 'Données du Compte' : 'Account Data' }}
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="subscription-tab" data-toggle="pill" href="#subscription" role="tab">
                        {{ app()->getLocale() == 'fr' ? 'Abonnements' : 'Subscriptions' }}
                    </a>
                </li>
            </ul>

            <div class="tab-content" id="profileTabsContent">
                <!-- Account Tab -->
                <div class="tab-pane fade show active" id="account" role="tabpanel">
                    <div class="avatar-container">
                        @if($user->avatar)
                            <img src="{{ asset('storage/' . $user->avatar) }}" alt="Avatar" class="avatar-preview">
                        @else
                            <img src="{{ asset('img/user.png') }}" alt="Default Avatar" class="avatar-preview">
                        @endif
                    </div>

                    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="form-group">
                            <label for="avatar">{{ app()->getLocale() == 'fr' ? 'Changer l\'avatar' : 'Change avatar' }}</label>
                            <input type="file" class="form-control-file" id="avatar" name="avatar">
                        </div>

                        <div class="form-group">
                            <label for="name">{{ app()->getLocale() == 'fr' ? 'Nom' : 'Name' }}</label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ $user->name }}" required>
                        </div>

                        <div class="form-group">
                            <label for="email">{{ app()->getLocale() == 'fr' ? 'Adresse e-mail' : 'Email' }}</label>
                            <input type="email" class="form-control" id="email" name="email" value="{{ $user->email }}" required>
                        </div>

                        <div class="form-group">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="is_student" id="is_student" value="1" {{ $user->is_student ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_student">
                                    {{ app()->getLocale() == 'fr' ? 'Je suis étudiant' : 'I am a student' }}
                                </label>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-update">
                            {{ app()->getLocale() == 'fr' ? 'Mettre à jour le profil' : 'Update profile' }}
                        </button>
                    </form>

                    <div class="password-section">
                        <h4 class="text-center mb-4">
                            {{ app()->getLocale() == 'fr' ? 'Changer le mot de passe' : 'Change password' }}
                        </h4>
                        <form action="{{ route('profile.update.password') }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="form-group">
                                <label for="current_password">
                                    {{ app()->getLocale() == 'fr' ? 'Mot de passe actuel' : 'Current password' }}
                                </label>
                                <div class="input-group">
                                    <input type="password" class="form-control" id="current_password" name="current_password" required>
                                    <div class="input-group-append">
                                        <button class="btn toggle-password" type="button" data-target="current_password">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="new_password">
                                    {{ app()->getLocale() == 'fr' ? 'Nouveau mot de passe' : 'New password' }}
                                </label>
                                <div class="input-group">
                                    <input type="password" class="form-control" id="new_password" name="new_password" required>
                                    <div class="input-group-append">
                                        <button class="btn toggle-password" type="button" data-target="new_password">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="new_password_confirmation">
                                    {{ app()->getLocale() == 'fr' ? 'Confirmer le nouveau mot de passe' : 'Confirm new password' }}
                                </label>
                                <div class="input-group">
                                    <input type="password" class="form-control" id="new_password_confirmation" name="new_password_confirmation" required>
                                    <div class="input-group-append">
                                        <button class="btn toggle-password" type="button" data-target="new_password_confirmation">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-update">
                                {{ app()->getLocale() == 'fr' ? 'Mettre à jour le mot de passe' : 'Update password' }}
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Subscriptions Tab -->
                <div class="tab-pane fade" id="subscription" role="tabpanel">
                    <h3 class="text-center mb-4">
                        {{ app()->getLocale() == 'fr' ? 'Mes Abonnements' : 'My Subscriptions' }}
                    </h3>
                    
                    @if($activeSubscriptions->isNotEmpty())
                        @foreach($activeSubscriptions as $subscription)
                            @foreach($subscription->categories as $category)
                                <div class="subscription-card">
                                    <h5 class="card-title">{{ $category->name }}</h5>
                                    <p class="card-text">
                                        {{ app()->getLocale() == 'fr' ? 'Date de fin :' : 'End date:' }}
                                        {{ Carbon\Carbon::parse($subscription->end_date)->format('d/m/Y') }}
                                    </p>
                                    <a href="{{ route('interactive.show', $category->id) }}" class="btn btn-update">
                                        {{ app()->getLocale() == 'fr' ? 'Accéder à la saga' : 'Access saga' }}
                                    </a>
                                </div>
                            @endforeach
                        @endforeach
                    @else
                        <div class="text-center">
                            <p>{{ app()->getLocale() == 'fr' ? 'Vous n\'avez aucun abonnement actif.' : 'You have no active subscriptions.' }}</p>
                            <a href="{{ route('interactive.index') }}" class="btn btn-update">
                                {{ app()->getLocale() == 'fr' ? 'Voir les sagas disponibles' : 'View available sagas' }}
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Password toggle script
    document.querySelectorAll('.toggle-password').forEach(button => {
        button.addEventListener('click', function() {
            const input = document.getElementById(this.getAttribute('data-target'));
            if (input.type === 'password') {
                input.type = 'text';
                this.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path><line x1="1" y1="1" x2="23" y2="23"></line></svg>';
            } else {
                input.type = 'password';
                this.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>';
            }
        });
    });
    
    // Initialize Bootstrap tabs
    document.addEventListener('DOMContentLoaded', function() {
        // Get all elements with data-toggle="pill"
        const triggerTabList = [].slice.call(document.querySelectorAll('[data-toggle="pill"]'));
        
        triggerTabList.forEach(function(triggerEl) {
            triggerEl.addEventListener('click', function(event) {
                event.preventDefault();
                
                // Remove active class from all tabs
                document.querySelectorAll('.nav-link').forEach(tab => {
                    tab.classList.remove('active');
                });
                
                // Remove active and show classes from all contents
                document.querySelectorAll('.tab-pane').forEach(pane => {
                    pane.classList.remove('active', 'show');
                });
                
                // Add active class to clicked tab
                this.classList.add('active');
                
                // Get tab target and show its content
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.classList.add('active', 'show');
                }
            });
        });
    });
    </script>
@endsection