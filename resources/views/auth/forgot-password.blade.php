@extends('layouts.applayout')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h2 class="text-center mb-4">
                        {{ app()->getLocale() == 'fr' 
                            ? 'Mot de passe oublié' 
                            : 'Forgot your password?' }}
                    </h2>

                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="email" class="form-label">
                                {{ app()->getLocale() == 'fr' ? 'Email' : 'Email' }}
                            </label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                   id="email" name="email" value="{{ old('email') }}" required autofocus>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary w-100">
                            {{ app()->getLocale() == 'fr' 
                                ? 'Envoyer le lien de réinitialisation' 
                                : 'Send reset link' }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 