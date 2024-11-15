@extends('layouts.applayout')

@section('title', "Art d'Histoire | Abonnement Requis")

@section('header')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .saga-card {
            border: 1px solid #eaeaea;
            border-radius: 0.375rem;
            transition: all 0.3s ease;
            background: white;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        .saga-image-container {
            position: relative;
            height: 200px;
            overflow: hidden;
            border-radius: 0.375rem 0.375rem 0 0;
        }

        .saga-image-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .saga-content {
            padding: 1.5rem;
        }

        .saga-title {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 1rem;
            color: #333;
        }

        .saga-price {
            background: #f8f9fa;
            padding: 1rem;
            border-radius: 10px;
            margin: 1rem 0;
        }

        .saga-price .display-4 {
            color: #212529;
            font-weight: 700;
            font-size: 2.5rem;
        }

        .student-badge {
            background: #e3f2fd;
            color: #1565c0;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.9rem;
            display: inline-block;
            margin-bottom: 1rem;
        }

        .coupon-section {
            margin: 1.5rem 0;
            padding: 1rem;
            border: 1px dashed #dee2e6;
            border-radius: 10px;
            background: #fafafa;
        }

        .coupon-input {
            border: 1px solid #ced4da;
            border-radius: 8px;
            padding: 0.5rem 1rem;
            width: 100%;
            margin-top: 0.5rem;
        }

        .action-buttons {
            display: flex;
            flex-direction: column;
            gap: 0rem;
            margin-top: 1rem;
        }

        .btn-outline-primary {
            border: 2px solid #0000FF;
            color: #0000FF;
            padding: 0.8rem 2rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-outline-primary:hover {
            background-color: #0000FF;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 255, 0.2);
        }
    </style>
@endsection

@section('content')
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <h2 class="text-center mb-4">
                    {{ app()->getLocale() == 'fr' ? 'Abonnement Requis' : 'Subscription Required' }}
                </h2>

                <div class="saga-card">
                    <div class="saga-image-container">
                        <img src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name }}">
                    </div>

                    <div class="saga-content text-center">
                        <h3 class="saga-title">{{ $category->name }}</h3>

                        @auth
                            @if (Auth::user()->is_student ?? false)
                                <div class="student-badge">
                                    <i class="fas fa-graduation-cap me-2"></i>
                                    {{ app()->getLocale() == 'fr' ? 'Prix spécial pour les étudiants' : 'Special price for students' }}
                                </div>
                            @endif

                            <div class="price-tag" style="color: #212529 !important;">
                                <span class="arapey">
                                    {{ app()->getLocale() == 'fr' ? '€' : '$' }}
                                </span>
                                <span class="display-4 arapey"
                                    data-original-amount="{{ Auth::user()->is_student ?? false
                                        ? (app()->getLocale() == 'fr'
                                            ? Voyager::setting('site.abono_estudiant')
                                            : Voyager::setting('site.abono_estudiant_DOLARES'))
                                        : (app()->getLocale() == 'fr'
                                            ? Voyager::setting('site.abono_normal')
                                            : Voyager::setting('site.abono_normal_DOLARES')) }}">

                                    <strong>{{ Auth::user()->is_student ?? false
                                        ? (app()->getLocale() == 'fr'
                                            ? Voyager::setting('site.abono_estudiant')
                                            : Voyager::setting('site.abono_estudiant_DOLARES'))
                                        : (app()->getLocale() == 'fr'
                                            ? Voyager::setting('site.abono_normal')
                                            : Voyager::setting('site.abono_normal_DOLARES')) }}</strong>
                                </span>
                                <p class="saga-description">
                                    {{ app()->getLocale() == 'fr' ? $textosFormula->texto_debajo_formula : $textosFormula->texto_debajo_formula_en }}
                                </p>
                            </div>

                            <div class="action-buttons">
                                <form action="{{ route('subscription.create') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="category_id" value="{{ $category->id }}">
                                    <input type="hidden" name="amount" value="{{ $amount }}">
                                    <input type="hidden" name="payment_method" value="stripe">
                                    <input type="hidden" name="coupon_code" id="hidden_coupon_code">

                                    <div class="coupon-section">
                                        <label for="coupon_code" class="d-block mb-2">
                                            <i class="fas fa-tag me-2"></i>
                                            {{ app()->getLocale() == 'fr' ? 'Code promo' : 'Coupon code' }}
                                        </label>
                                        <input type="text" class="coupon-input" id="coupon_code"
                                            placeholder="{{ app()->getLocale() == 'fr' ? 'Entrez votre code' : 'Enter your code' }}">
                                        <small id="coupon-message" class="form-text mt-2 d-block"></small>
                                    </div>

                                    <div class="action-buttons">
                                        <button type="submit" class="btn btn-outline-primary w-100">
                                            <i class="fas fa-credit-card me-2"></i>
                                            {{ app()->getLocale() == 'fr' ? 'Payer avec une carte de crédit' : 'Pay with credit card' }}
                                        </button>
                                    </div>
                                </form>

                                @if (app()->getLocale() == 'en')
                                    <form action="{{ route('subscription.create') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="category_id" value="{{ $category->id }}">
                                        <input type="hidden" name="amount" value="{{ $amount }}">
                                        <input type="hidden" name="payment_method" value="paypal">
                                        <input type="hidden" name="coupon_code" class="hidden_coupon_code">

                                        <button type="submit" class="btn btn-outline-primary w-100">
                                            <i class="fab fa-paypal me-2"></i>
                                            {{ app()->getLocale() == 'fr' ? 'Payer avec PayPal' : 'Pay with PayPal' }}
                                        </button>
                                    </form>
                                @endif
                            </div>
                            <script>
                                document.getElementById('coupon_code').addEventListener('input', function() {
                                    const couponCode = this.value;
                                    const hiddenInputs = document.querySelectorAll('.hidden_coupon_code, #hidden_coupon_code');

                                    if (couponCode) {
                                        fetch('/validate-coupon', {
                                                method: 'POST',
                                                headers: {
                                                    'Content-Type': 'application/json',
                                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                                                },
                                                body: JSON.stringify({
                                                    code: couponCode
                                                })
                                            })
                                            .then(response => response.json())
                                            .then(data => {
                                                const messageElement = document.getElementById('coupon-message');
                                                if (data.valid) {
                                                    messageElement.style.color = 'green';
                                                    messageElement.textContent = data.message;
                                                    // Actualizar todos los inputs ocultos con el código del cupón
                                                    hiddenInputs.forEach(input => input.value = couponCode);
                                                } else {
                                                    messageElement.style.color = 'red';
                                                    messageElement.textContent = data.message;
                                                    // Limpiar los inputs ocultos
                                                    hiddenInputs.forEach(input => input.value = '');
                                                }
                                            });
                                    }
                                });
                            </script>
                        @else
                            <div class="saga-price">
                                <div class="display-4">
                                    € <strong>{{ Voyager::setting('site.abono_normal') }}</strong>
                                </div>
                            </div>
                            <div class="action-buttons">
                                <a href="{{ route('login') }}" class="btn btn-outline-primary">
                                    <i class="fas fa-sign-in-alt me-2"></i>
                                    {{ app()->getLocale() == 'fr' ? 'Se connecter pour continuer' : 'Login to continue' }}
                                </a>
                            </div>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
