@extends('layouts.applayout')

@section('content')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="text-center mb-0">
                        {{ app()->getLocale() == 'fr' ? 'Activer un coupon d\'accès' : 'Redeem Access Coupon' }}
                    </h3>
                </div>
                <div class="card-body">
                    <form id="coupon-form" class="coupon-form">
                        @csrf
                        <div class="form-group mb-3">
                            <label for="coupon_code">
                                {{ app()->getLocale() == 'fr' ? 'Code du coupon' : 'Coupon Code' }}
                            </label>
                            <input type="text" 
                                   class="form-control" 
                                   name="coupon_code"
                                   id="coupon_code" 
                                   placeholder="{{ app()->getLocale() == 'fr' ? 'Entrez le code du coupon' : 'Enter coupon code' }}">
                        </div>

                        <div id="coupon-message" class="alert d-none"></div>

                        <div id="redemption-section" class="d-none">
                            <div class="coupon-details mb-3">
                                <h5>{{ app()->getLocale() == 'fr' ? 'Détails du coupon' : 'Coupon Details' }}</h5>
                                <p id="duration-text"></p>
                                <p id="category-text"></p>
                            </div>

                            <button type="button" id="redeem-button" class="btn btn-primary w-100">
                                {{ app()->getLocale() == 'fr' ? 'Activer l\'accès' : 'Activate Access' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Verificar que los elementos existen
    const form = document.getElementById('coupon-form');
    const couponInput = document.getElementById('coupon_code');
    const messageDiv = document.getElementById('coupon-message');
    const redemptionSection = document.getElementById('redemption-section');
    const durationText = document.getElementById('duration-text');
    const categoryText = document.getElementById('category-text');
    const redeemButton = document.getElementById('redeem-button');
    const currentLocale = '{{ app()->getLocale() }}';

    // Verificar que todos los elementos necesarios existen
    if (!form || !couponInput || !messageDiv || !redemptionSection || !durationText || !categoryText || !redeemButton) {
        console.error('No se encontraron todos los elementos necesarios');
        return;
    }

    let validCouponCode = null;
    let categoryId = null;

    function debounce(func, wait) {
        let timeout;
        return function(...args) {
            clearTimeout(timeout);
            timeout = setTimeout(() => func.apply(this, args), wait);
        };
    }

    const validateCoupon = debounce(function(code) {
        if (!code || code.length < 3) return;

        fetch('/validate-access-coupon', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ code: code })
        })
        .then(response => response.json())
        .then(data => {
            messageDiv.classList.remove('d-none', 'alert-success', 'alert-danger');

            if (data.valid) {
                messageDiv.classList.add('alert-success');
                messageDiv.textContent = data.message;

                validCouponCode = code;
                categoryId = data.category_id;

                durationText.textContent = `${currentLocale == 'fr' ? 'Durée' : 'Duration'}: ${data.duration_days} ${currentLocale == 'fr' ? 'jours' : 'days'}`;
                categoryText.textContent = `${currentLocale == 'fr' ? 'Catégorie' : 'Category'}: ${data.category_name}`;

                redemptionSection.classList.remove('d-none');
            } else {
                messageDiv.classList.add('alert-danger');
                messageDiv.textContent = data.message;
                resetForm();
            }
            messageDiv.classList.remove('d-none');
        })
        .catch(error => {
            console.error('Error:', error);
            messageDiv.classList.remove('d-none');
            messageDiv.classList.add('alert-danger');
            messageDiv.textContent = currentLocale == 'fr' ? 
                'Une erreur s\'est produite' : 
                'An error occurred';
            resetForm();
        });
    }, 500);

    function resetForm() {
        validCouponCode = null;
        categoryId = null;
        redemptionSection.classList.add('d-none');
    }

    // Event Listeners
    couponInput.addEventListener('input', function() {
        const code = this.value.trim();
        if (code.length >= 3) {
            validateCoupon(code);
        } else {
            resetForm();
            messageDiv.classList.add('d-none');
        }
    });

    redeemButton.addEventListener('click', function() {
        if (!validCouponCode) return;

        this.disabled = true;
        
        fetch('/redeem-access-coupon', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ code: validCouponCode })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                messageDiv.classList.remove('alert-danger');
                messageDiv.classList.add('alert-success');
                messageDiv.textContent = data.message;

                setTimeout(() => {
                    window.location.href = '/interactive/' + categoryId;
                }, 2000);
            } else {
                throw new Error(data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            messageDiv.classList.remove('alert-success');
            messageDiv.classList.add('alert-danger');
            messageDiv.textContent = currentLocale == 'fr' ? 
                'Une erreur s\'est produite lors de l\'activation' : 
                'An error occurred during activation';
            this.disabled = false;
        });
    });
});
</script>

<style>
    .card {
        border: none;
        box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
    }

    .card-header {
        background-color: #f8f9fa;
        border-bottom: 1px solid #eee;
        padding: 1.5rem;
    }

    .card-body {
        padding: 2rem;
    }

    .form-control {
        padding: 0.75rem;
        border: 1px solid #ced4da;
        border-radius: 0.25rem;
    }

    .btn-primary {
        padding: 0.75rem;
        font-weight: 500;
    }

    .alert {
        padding: 1rem;
        margin-bottom: 1rem;
        border-radius: 0.25rem;
    }

    .coupon-details {
        background-color: #f8f9fa;
        padding: 1rem;
        border-radius: 0.25rem;
    }
</style>
@endsection