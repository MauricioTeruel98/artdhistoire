@extends('layouts.applayout')

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
                                <input type="password" class="form-control @error('password') is-invalid @enderror"
                                    id="password" name="password" required>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="password_confirmation" class="form-label">
                                    {{ app()->getLocale() == 'fr' ? 'Confirmer le nouveau mot de passe' : 'Confirm new password' }}
                                </label>
                                <input type="password" class="form-control" id="password_confirmation"
                                    name="password_confirmation" required>
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
@endsection
