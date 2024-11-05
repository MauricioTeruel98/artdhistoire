@extends('layouts.applayout')

@section('title', "Art d'Histoire | Videos ")

@section('header')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .card-plans {
            border: 1px solid #ddd;
            padding: 2rem;
        }

        .card-formula {
            min-height: 330px;
        }

        .display-4 {
            font-size: 3rem;
            font-weight: bold;
        }

        .btn-primary,
        .btn-info,
        .btn-success {
            border: none;
            font-size: 1.2rem;
            padding: 0.8rem 2rem;
        }

        .btn-primary {
            background-color: #0000FF;
        }

        .btn-primary:hover {
            background-color: #0000cc;
        }

        .btn-success {
            background-color: #28a745;
        }

        .btn-success:hover {
            background-color: #218838;
        }

        .text-muted {
            font-size: 0.9rem;
        }

        .img-container {
            width: 100%;
            height: 200px;
            display: flex;
            justify-content: center;
            align-items: center;
            overflow: hidden;
        }

        .price-tag {
            font-size: 2rem;
            margin: 1.5rem 0;
        }

        .price-tag .display-4 {
            font-size: 3.5rem;
            font-weight: bold;
        }

        .small {
            font-size: 0.9rem;
            line-height: 1.4;
            padding: 0 1rem;
        }

        .btn-outline-primary {
            border: 2px solid #0000FF;
            color: #0000FF;
            padding: 0.5rem 2rem;
            margin: 1rem 0;
        }

        .btn-outline-primary:hover {
            background-color: #0000FF;
            color: white;
        }

        .highlighted-plan {
            border: 3px solid #0000FF;
            box-shadow: 0 0 15px rgba(0, 0, 255, 0.1);
            transform: scale(1.02);
            transition: all 0.3s ease;
        }

        .recommended-badge {
            background-color: #0000FF;
            color: white;
            padding: 0.5rem;
            position: absolute;
            top: 0;
            right: 0;
            border-bottom-left-radius: 8px;
            font-size: 0.9rem;
        }

        .card-abono {
            position: relative;
            transition: all 0.3s ease;
        }

        .saga-card {
            border: 1px solid #eaeaea;
            border-radius: 0.375rem;
            transition: all 0.3s ease;
            background: white;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .saga-card:hover {
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
        }

        .saga-image-container {
            position: relative;
            height: 200px;
            overflow: hidden;
            border-radius: 0.375rem 0.375rem 0 0;
            flex-shrink: 0;
        }

        .saga-image-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .saga-card:hover .saga-image-container img {
            transform: scale(1.05);
        }

        .saga-content {
            padding: 1.5rem;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }

        .saga-title {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 1rem;
            color: #333;
            flex-shrink: 0;
        }

        .saga-price {
            background: #f8f9fa;
            padding: 1rem;
            border-radius: 10px;
            margin: 1rem 0;
            flex-shrink: 0;
        }

        .saga-price .display-4 {
            color: #212529;
            font-weight: 700;
        }

        .student-badge {
            background: #e3f2fd;
            color: #1565c0;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.9rem;
            display: inline-block;
            margin-bottom: 1rem;
            flex-shrink: 0;
        }

        .coupon-section {
            margin: 1.5rem 0;
            padding: 1rem;
            border: 1px dashed #dee2e6;
            border-radius: 10px;
            background: #fafafa;
            flex-shrink: 0;
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
            margin-top: auto;
            flex-shrink: 0;
        }

        .btn-subscribe {
            background: #0000FF;
            color: white;
            padding: 1rem 2rem;
            border-radius: 30px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
        }

        .btn-subscribe:hover {
            background: #0000cc;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 255, 0.2);
        }

        .saga-description {
            color: #666;
            font-size: 0.95rem;
            line-height: 1.6;
            margin-bottom: 1.5rem;
            flex-grow: 1;
        }

        .col-md-4.mb-4 {
            margin-bottom: 2rem;
        }
    </style>
@endsection

@section('content')
    <div class="container mt-5">
        <h2 class="text-center mb-4">{{ app()->getLocale() == 'fr' ? 'Nos Formules' : 'Our Plans' }}</h2>

        <div class="row justify-content-center mb-5">
            {{-- Plan Particulier --}}
            <div class="col-md-4 mb-4">
                <div class="card card-plans text-center card-formula">
                    <h5>{{ app()->getLocale() == 'fr' ? 'Vous êtes un particulier ?' : 'Are you an individual?' }}</h5>
                    <div class="price-tag">€ <span class="display-4">{{ Voyager::setting('site.abono_normal') }}</span></div>
                    <p class="small">
                        {{ app()->getLocale() == 'fr' ? $textosFormula->formula_normal : $textosFormula->formula_normal_en }}
                    </p>
                </div>
            </div>

            {{-- Plan Estudiante --}}
            <div class="col-md-4 mb-4">
                <div class="card card-plans text-center card-formula">
                    <h5>{{ app()->getLocale() == 'fr' ? 'Vous êtes étudiant ?' : 'Are you a student?' }}</h5>
                    <div class="price-tag">€ <span class="display-4">{{ Voyager::setting('site.abono_estudiant') }}</span>
                    </div>
                    <p class="small">
                        {{ app()->getLocale() == 'fr' ? $textosFormula->formula_estudiante : $textosFormula->formula_estudiante_en }}
                    </p>
                    <p class="text-muted small">*
                        {{ app()->getLocale() == 'fr' ? 'Sous présentation de justificatif' : 'With proof of student status' }}
                    </p>
                </div>
            </div>

            {{-- Plan Empresa/Socio --}}
            <div class="col-md-4 mb-4">
                <div class="card card-plans text-center card-formula">
                    <h5>{{ app()->getLocale() == 'fr' ? 'Vous êtes partenaire ou souhaitez devenir partenaire ?' : 'Are you a partner or wish to become a partner?' }}
                    </h5>
                    <p>{{ app()->getLocale() == 'fr' ? $textosFormula->formula_personalizada : $textosFormula->formula_personalizada_en }}
                    </p>
                    <button class="btn btn-outline-primary" onclick="document.getElementById('chat-toggle').click()">
                        {{ app()->getLocale() == 'fr' ? 'Contactez - nous' : 'Contact us' }}
                    </button>
                </div>
            </div>
        </div>
        <h2 class="text-center mb-4">
            {{ app()->getLocale() == 'fr' ? Voyager::setting('site.titulo_sagas_index') : Voyager::setting('site.titulo_sagas_index_en') }}
        </h2>
        <div class="row justify-content-center">
            @foreach ($categories as $category)
                <div class="col-md-4 mb-4">
                    <div class="saga-card">
                        <div class="saga-image-container">
                            <img src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name }}"
                                class="saga-image">
                        </div>

                        <div class="saga-content">
                            <h3 class="saga-title">
                                {{ app()->getLocale() == 'fr' ? $category->name_fr : $category->name }}
                            </h3>

                            @auth
                                @if (Auth::user()->is_student ?? false)
                                    <div class="student-badge">
                                        <i class="fas fa-graduation-cap me-2"></i>
                                        {{ app()->getLocale() == 'fr' ? 'Prix spécial pour les étudiants' : 'Special price for students' }}
                                    </div>
                                @endif

                                <div class="saga-price">
                                    <div class="display-4"
                                        data-original-amount="{{ Auth::user()->is_student ?? false ? Voyager::setting('site.abono_estudiant') : Voyager::setting('site.abono_normal') }}">
                                        €
                                        <strong>{{ Auth::user()->is_student ?? false ? Voyager::setting('site.abono_estudiant') : Voyager::setting('site.abono_normal') }}</strong>
                                    </div>
                                </div>

                                <p class="saga-description">
                                    {{ app()->getLocale() == 'fr' ? $textosFormula->texto_debajo_formula : $textosFormula->texto_debajo_formula_en }}
                                </p>

                                <div class="action-buttons">
                                    {{-- Formulario de Stripe --}}
                                    <form action="{{ route('subscription.create') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="category_id" value="{{ $category->id }}">
                                        <input type="hidden" name="amount" value="{{ $amount }}">
                                        <input type="hidden" name="payment_method" value="stripe">
                                        <input type="hidden" name="coupon_code" id="hidden_coupon_code_{{ $category->id }}">

                                        <div class="coupon-section">
                                            <label for="coupon_code_{{ $category->id }}" class="d-block mb-2">
                                                <i class="fas fa-tag me-2"></i>
                                                {{ app()->getLocale() == 'fr' ? 'Code promo' : 'Coupon code' }}
                                            </label>
                                            <input type="text" class="coupon-input" id="coupon_code_{{ $category->id }}"
                                                placeholder="{{ app()->getLocale() == 'fr' ? 'Entrez votre code' : 'Enter your code' }}">
                                            <small id="coupon-message-{{ $category->id }}"
                                                class="form-text mt-2 d-block"></small>
                                        </div>

                                        <button type="submit" class="btn btn-outline-primary w-100 mb-2">
                                            <i class="fas fa-credit-card me-2"></i>
                                            {{ app()->getLocale() == 'fr' ? 'Payer avec une carte de crédit' : 'Pay with credit card' }}
                                        </button>
                                    </form>

                                    {{-- Formulario de PayPal --}}
                                    <form action="{{ route('subscription.create') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="category_id" value="{{ $category->id }}">
                                        <input type="hidden" name="amount" value="{{ $amount }}">
                                        <input type="hidden" name="payment_method" value="paypal">
                                        <input type="hidden" name="coupon_code"
                                            id="hidden_coupon_code_paypal_{{ $category->id }}">

                                        <button type="submit" class="btn btn-outline-primary w-100">
                                            <i class="fab fa-paypal me-2"></i>
                                            {{ app()->getLocale() == 'fr' ? 'Payer avec PayPal' : 'Pay with PayPal' }}
                                        </button>
                                    </form>
                                </div>
                                <script>
                                    document.getElementById('coupon_code_{{ $category->id }}').addEventListener('input', function() {
                                        const couponCode = this.value;
                                        const hiddenInputs = [
                                            document.getElementById('hidden_coupon_code_{{ $category->id }}'),
                                            document.getElementById('hidden_coupon_code_paypal_{{ $category->id }}')
                                        ];

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
                                                    const messageElement = document.getElementById('coupon-message-{{ $category->id }}');
                                                    if (data.valid) {
                                                        messageElement.style.color = 'green';
                                                        messageElement.textContent = data.message;
                                                        // Actualizar ambos inputs ocultos con el código del cupón
                                                        hiddenInputs.forEach(input => input.value = couponCode);
                                                    } else {
                                                        messageElement.style.color = 'red';
                                                        messageElement.textContent = data.message;
                                                        // Limpiar ambos inputs ocultos
                                                        hiddenInputs.forEach(input => input.value = '');
                                                    }
                                                });
                                        }
                                    });
                                </script>
                            @else
                                {{-- Mostrar precio base para usuarios no autenticados --}}
                                <div class="saga-price">
                                    <div class="display-4">
                                        € <strong>{{ Voyager::setting('site.abono_normal') }}</strong>
                                    </div>
                                </div>

                                <p class="saga-description">
                                    {{ app()->getLocale() == 'fr' ? $textosFormula->texto_debajo_formula : $textosFormula->texto_debajo_formula_en }}
                                </p>

                                <div class="action-buttons">
                                    <a href="{{ route('login') }}" class="btn btn-outline-secondary">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round"
                                            class="icon icon-tabler icons-tabler-outline icon-tabler-login">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path
                                                d="M14 8v-2a2 2 0 0 0 -2 -2h-7a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h7a2 2 0 0 0 2 -2v-2" />
                                            <path d="M20 12h-13l3 -3m0 6l-3 -3" />
                                        </svg>
                                        {{ app()->getLocale() == 'fr' ? 'Se connecter pour continuer' : 'Login to continue' }}
                                    </a>
                                </div>
                            @endauth
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
