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
                    $livreBlanc = json_decode(Voyager::setting('site.livre_blanc'), true);
                    $downloadLink = $livreBlanc[0]['download_link'] ?? '#';
                @endphp
                <a href="/storage/{{ $downloadLink }}" target="_blank" class="btn-download">
                    {{ app()->getLocale() == 'fr' ? 'Télécharger le livre blanc' : 'Download the white book' }}
                </a>

                <h3 class="mt-5 mb-3">{{ app()->getLocale() == 'fr' ? 'Nous contacter' : 'Contact us' }}</h3>
                <form>
                    @csrf
                    <input type="text" class="form-control" name="name"
                        placeholder="{{ app()->getLocale() == 'fr' ? 'Nom *' : 'Name *' }}" required>
                    <input type="email" class="form-control" name="email" placeholder="Email *" required>
                    <textarea class="form-control" rows="3" name="message" placeholder="{{ app()->getLocale() == 'fr' ? 'Message' : 'Message' }}"></textarea>
                    <button type="submit"
                        class="btn btn-outline-secondary">{{ app()->getLocale() == 'fr' ? 'Envoyer' : 'Send' }}</button>
                </form>
            </div>

            <div class="col-md-6">
                {!! app()->getLocale() == 'fr' ? $textos->texto_about_second : $textos->texto_about_second_en !!}
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const forms = document.querySelectorAll('form');

            forms.forEach(form => {
                if (form.querySelector('[name="name"]')) {
                    form.addEventListener('submit', function(e) {
                        e.preventDefault();

                        const formData = new FormData(this);
                        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

                        fetch('/contact', {
                                method: 'POST',
                                body: formData,
                                headers: {
                                    'X-CSRF-TOKEN': csrfToken
                                }
                            })
                            .then(response => response.json())
                            .then(data => {
                                const messageDiv = this.querySelector('.message-response') ||
                                    document.createElement('div');
                                messageDiv.className = 'message-response mt-3';

                                if (data.success) {
                                    messageDiv.className += ' text-success';
                                    this.reset();
                                } else {
                                    messageDiv.className += ' text-danger';
                                }

                                messageDiv.textContent = data.message;

                                if (!this.querySelector('.message-response')) {
                                    this.appendChild(messageDiv);
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                            });
                    });
                }
            });
        });
    </script>
@endsection
