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
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="is_student" id="is_student" value="1">
                                    <label class="form-check-label" for="is_student">
                                        {{ app()->getLocale() == 'fr' ? 'Je suis étudiant' : 'I am a student' }}
                                    </label>
                                </div>
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

                            <button type="submit" class="btn btn-outline-secondary w-100 mb-3">{{ app()->getLocale() == 'fr' ? 'S\'inscrire' : 'Register' }}</button>
                        </form>

                        <div class="text-center">
                            <p>{{ app()->getLocale() == 'fr' ? 'Ou connectez-vous avec :' : 'Or login with:' }}</p>
                            <a href="{{ url('login/google') }}" class="btn social-btn btn-google my-2">
                                <svg  xmlns="http://www.w3.org/2000/svg"  width="20"  height="20"  viewBox="0 0 24 24"  fill="currentColor"  class="icon icon-tabler icons-tabler-filled icon-tabler-brand-google me-2"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 2a9.96 9.96 0 0 1 6.29 2.226a1 1 0 0 1 .04 1.52l-1.51 1.362a1 1 0 0 1 -1.265 .06a6 6 0 1 0 2.103 6.836l.001 -.004h-3.66a1 1 0 0 1 -.992 -.883l-.007 -.117v-2a1 1 0 0 1 1 -1h6.945a1 1 0 0 1 .994 .89c.04 .367 .061 .737 .061 1.11c0 5.523 -4.477 10 -10 10s-10 -4.477 -10 -10s4.477 -10 10 -10z" /></svg>{{ app()->getLocale() == 'fr' ? 'Se connecter avec Google' : 'Login with Google' }}
                            </a>
                            <a href="{{ url('login/facebook') }}" class="btn social-btn btn-facebook">
                                <svg  xmlns="http://www.w3.org/2000/svg"  width="20"  height="20"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-brand-facebook me-2"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 10v4h3v7h4v-7h3l1 -4h-4v-2a1 1 0 0 1 1 -1h3v-4h-3a5 5 0 0 0 -5 5v2h-3" /></svg>{{ app()->getLocale() == 'fr' ? 'Se connecter avec Facebook' : 'Login with Facebook' }}
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
