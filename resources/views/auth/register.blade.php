@extends('layouts.applayout')

@section('title', "Art d'Histoire | Videos ")

@section('header')
    <style>
        body {
            background-color: #f8f9fa;
        }
        .card {
            border: none;
            border-radius: 1rem;
            box-shadow: 0 0.5rem 1rem 0 rgba(0, 0, 0, 0.1);
        }
        .card-header {
            background-color: #ffffff;
            border-bottom: none;
            padding: 2rem 0 1rem;
        }
        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }
        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }
        .social-btn {
            color: #fff;
            padding: 0.5rem 1rem;
        }
        .btn-google {
            background-color: #db4437;
        }
        .btn-facebook {
            background-color: #4267B2;
        }
    </style>
@endsection

@section('content')
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header text-center">
                        <h2 class="fw-bold">{{ app()->getLocale() == 'fr' ? 'S\'inscrire' : 'Register' }}</h2>
                    </div>
                    <div class="card-body p-5">
                        <form action="{{ route('register') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="name" class="form-label">{{ app()->getLocale() == 'fr' ? 'Nom' : 'Name' }}</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" id="name" value="{{ old('name') }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">{{ app()->getLocale() == 'fr' ? 'E-mail' : 'Email' }}</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" id="email" value="{{ old('email') }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">{{ app()->getLocale() == 'fr' ? 'Mot de passe' : 'Password' }}</label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" name="password" id="password" required>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="password_confirmation" class="form-label">{{ app()->getLocale() == 'fr' ? 'Confirmer le mot de passe' : 'Confirm Password' }}</label>
                                <input type="password" class="form-control" name="password_confirmation" id="password_confirmation" required>
                            </div>

                            <button type="submit" class="btn btn-primary w-100 mb-3">{{ app()->getLocale() == 'fr' ? 'S\'inscrire' : 'Register' }}</button>
                        </form>

                        <div class="text-center">
                            <p>{{ app()->getLocale() == 'fr' ? 'Ou inscrivez-vous avec :' : 'Or register with:' }}</p>
                            <a href="{{ url('login/google') }}" class="btn social-btn btn-google mb-2">
                                <i class="fab fa-google me-2"></i>{{ app()->getLocale() == 'fr' ? 'S\'inscrire avec Google' : 'Register with Google' }}
                            </a>
                            <a href="{{ url('login/facebook') }}" class="btn social-btn btn-facebook">
                                <i class="fab fa-facebook-f me-2"></i>{{ app()->getLocale() == 'fr' ? 'S\'inscrire avec Facebook' : 'Register with Facebook' }}
                            </a>
                        </div>
                    </div>
                </div>

                <div class="text-center mt-3">
                    <a href="{{ route('login') }}" class="text-decoration-none">{{ app()->getLocale() == 'fr' ? 'Déjà inscrit ? Connectez-vous ici' : 'Already registered? Login here' }}</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@endsection
