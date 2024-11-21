@extends('layouts.applayout')

@section('header')
    <style>
        .password-toggle {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #6c757d;
        }

        .password-field-wrapper {
            position: relative;
        }
    </style>
@endsection

@section('content')
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h2 class="text-center mb-4">
                            {{ app()->getLocale() == 'fr' ? 'Réinitialiser le mot de passe' : 'Reset Password' }}
                        </h2>

                        <form method="POST" action="{{ route('password.update') }}">
                            @csrf
                            <input type="hidden" name="token" value="{{ $token }}">
                            <input type="hidden" name="email" value="{{ request()->email }}">

                            <div class="mb-3">
                                <label for="password" class="form-label">
                                    {{ app()->getLocale() == 'fr' ? 'Nouveau mot de passe' : 'New password' }}
                                </label>
                                <div class="password-field-wrapper">
                                    <input type="password" class="form-control @error('password') is-invalid @enderror"
                                        id="password" name="password" required>
                                    <i class="bi bi-eye-slash password-toggle" data-target="password"></i>
                                </div>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="password_confirmation" class="form-label">
                                    {{ app()->getLocale() == 'fr' ? 'Confirmer le nouveau mot de passe' : 'Confirm new password' }}
                                </label>
                                <div class="password-field-wrapper">
                                    <input type="password" class="form-control" id="password_confirmation"
                                        name="password_confirmation" required>
                                    <i class="bi bi-eye-slash password-toggle" data-target="password_confirmation"></i>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary w-100">
                                {{ app()->getLocale() == 'fr' ? 'Réinitialiser le mot de passe' : 'Reset password' }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    <script>
    document.querySelectorAll('.password-toggle').forEach(toggle => {
        toggle.addEventListener('click', function() {
            const targetId = this.getAttribute('data-target');
            const passwordInput = document.getElementById(targetId);
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                this.classList.remove('bi-eye-slash');
                this.classList.add('bi-eye');
            } else {
                passwordInput.type = 'password';
                this.classList.remove('bi-eye');
                this.classList.add('bi-eye-slash');
            }
        });
    });
    </script>
@endsection
