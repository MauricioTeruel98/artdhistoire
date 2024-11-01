@extends('layouts.applayout')

@section('title', "Art d'Histoire | About ")

@section('header')

    <style>
        h1 {
            color: #2d2654;
            font-size: 24px;
            margin-bottom: 0;
        }

        h2 {
            color: #4a4a4a;
            font-size: 18px;
            font-weight: normal;
            margin-top: 5px;
        }

        .subtitle {
            color: #6a6a6a;
            font-style: italic;
        }

        .btn-custom {
            background-color: #2d2654;
            color: white;
            border: none;
            padding: 10px 20px;
        }

        .btn-custom:hover {
            background-color: #3a3169;
            color: white;
        }

        .form-control {
            margin-bottom: 10px;
        }

        .highlight {
            color: #2d2654;
            font-weight: bold;
        }

        h1 {
            font-weight: bold;
        }

        .green-font {
            color: #204007 !important;
            font-weight: bold;
        }

        .btn-download {
            display: inline-block;
            background-color: white;
            color: black;
            border: 2px solid black;
            padding: 15px 30px;
            text-align: center;
            text-decoration: none;
            font-size: 18px;
            font-weight: bold;
            margin-top: 20px;
            cursor: pointer;
        }

        .btn-download:hover {
            background-color: #f0f0f0;
        }
    </style>

@endsection

@section('content')
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-6">
                {!! app()->getLocale() == 'fr' ? $textos->texto_about_first : $textos->texto_about_first_en !!}

                {{-- Boton libro blanco --}}

                @php
                    if(app()->getLocale() == 'fr') {
                        $livreBlanc = json_decode(Voyager::setting('site.livre_blanc'), true);
                        $downloadLink = $livreBlanc[0]['download_link'] ?? '#';
                    } else {
                        $livreBlanc = json_decode(Voyager::setting('site.lb_anglais'), true);
                        $downloadLink = $livreBlanc[0]['download_link'] ?? '#';
                    }
                @endphp
                <a href="/storage/{{ $downloadLink }}" target="_blank" class="btn-download">
                    {{ app()->getLocale() == 'fr' ? 'Télécharger le livre blanc' : 'Download the white book' }}
                </a>

                <h3 class="mt-5 mb-3">{{ app()->getLocale() == 'fr' ? 'Nous contacter' : 'Contact us' }}</h3>
                <form id="contact-form" class="mt-3">
                    @csrf
                    <div class="mb-3">
                        <input type="text" class="form-control bottom-border" id="name" name="name"
                            placeholder="{{ app()->getLocale() == 'fr' ? 'Nom *' : 'Name *' }}" required>
                    </div>
                    <div class="mb-3">
                        <input type="email" class="form-control bottom-border" id="email" name="email"
                            placeholder="Email *" required>
                    </div>
                    <div class="mb-3">
                        <textarea class="form-control bottom-border" id="message" name="message" rows="3"
                            placeholder="{{ app()->getLocale() == 'fr' ? 'Message' : 'Message' }}" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-outline-secondary">
                        {{ app()->getLocale() == 'fr' ? 'Envoyer' : 'Send' }}
                    </button>
                    <div id="contact-messages"></div>
                </form>
            </div>

            <div class="col-md-6">
                {!! app()->getLocale() == 'fr' ? $textos->texto_about_second : $textos->texto_about_second_en !!}
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const contactForm = document.getElementById('contact-form');
            const csrfToken = document.querySelector('meta[name="csrf-token"]');

            contactForm.addEventListener('submit', function(e) {
                e.preventDefault();

                const formData = new FormData(contactForm);

                if (csrfToken) {
                    fetch('/contact', {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-CSRF-TOKEN': csrfToken.getAttribute('content')
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            const contactMessages = document.getElementById('contact-messages');
                            if (data.success) {
                                contactMessages.innerHTML =
                                    '<p class="text-success mt-3">{{ app()->getLocale() == 'fr' ? 'Votre message a été envoyé avec succès!' : 'Your message has been sent successfully!' }}</p>';
                                contactForm.reset();
                            } else {
                                contactMessages.innerHTML =
                                    '<p class="text-danger mt-3">{{ app()->getLocale() == 'fr' ? 'Une erreur s\'est produite. Veuillez réessayer.' : 'An error occurred. Please try again.' }}</p>';
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            const contactMessages = document.getElementById('contact-messages');
                            contactMessages.innerHTML =
                                '<p class="text-danger mt-3">{{ app()->getLocale() == 'fr' ? 'Une erreur s\'est produite. Veuillez réessayer.' : 'An error occurred. Please try again.' }}</p>';
                        });
                } else {
                    console.error('CSRF token not found');
                    const contactMessages = document.getElementById('contact-messages');
                    contactMessages.innerHTML =
                        '<p class="text-danger mt-3">{{ app()->getLocale() == 'fr' ? 'Une erreur s\'est produite. Veuillez réessayer.' : 'An error occurred. Please try again.' }}</p>';
                }
            });
        });
    </script>
@endsection
