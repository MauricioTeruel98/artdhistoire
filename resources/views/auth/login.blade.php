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
        .card-body {
            padding: 2rem;
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
                <div class="card shadow-sm">
                    <div class="card-body">
                        @if(session('pdf_message'))
                            <div class="alert alert-info">
                                {{ session('pdf_message') }}
                            </div>
                        @endif
                        <h2 class="text-center mb-4 fw-bold">Login</h2>
                        <form action="{{ route('login') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required>
                                @error('email')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required>
                                @error('password')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-primary w-100 mb-3">Login</button>
                        </form>

                        <div class="text-center">
                            <p>Or login with:</p>
                            <a href="{{ url('login/google') }}" class="btn social-btn btn-google my-2">
                                <i class="fab fa-google me-2"></i>Login with Google
                            </a>
                            <a href="{{ url('login/facebook') }}" class="btn social-btn btn-facebook">
                                <i class="fab fa-facebook-f me-2"></i>Login with Facebook
                            </a>
                        </div>

                        <div class="text-center mt-3">
                            <a href="{{ route('register') }}" class="text-decoration-none">Don't have an account? Register here</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@endsection