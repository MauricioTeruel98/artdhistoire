<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <link rel="stylesheet" href="{{ asset('css/index.css') }}">
    <link rel="icon" href="{{ asset('img/favicon-francesa.png') }}" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- CSS de Slick Slider -->
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css" />
    <link rel="stylesheet" type="text/css"
        href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick-theme.css" />

    <!-- JS de jQuery (Slick depende de jQuery) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- JS de Slick Slider -->
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>

    @yield('header')

    <style>
        @font-face {
            font-family: 'Playfair Display';
            src: url('../fonts/playfair/PlayfairDisplay-Regular.ttf') format('truetype');
            font-weight: normal;
            font-style: normal;
        }

        @font-face {
            font-family: 'Arial';
            src: url('../fonts/arial/ARIAL.ttf') format('truetype');
            font-weight: normal;
            font-style: normal;
        }

        @font-face {
            font-family: 'Linotype';
            src: url('../fonts/linotype/Linotype\ Didot\ italic.otf') format('opentype');
            font-weight: normal;
            font-style: normal;
        }

        @font-face {
            font-family: 'Futura';
            src: url('../fonts/futura-2/Futura\ Book\ font.ttf') format('truetype');
            font-weight: normal;
            font-style: normal;
        }

        @font-face {
            font-family: 'Futura Light';
            src: url('../fonts/futura-2/futura\ light\ bt.ttf') format('truetype');
            font-weight: normal;
            font-style: normal;
        }

        @font-face {
            font-family: 'Baskeville Italic';
            src: url('../fonts/baskeville/LibreBaskerville-Italic.ttf') format('truetype');
            font-weight: normal;
            font-style: normal;
        }


        /* Ahora puedes usar la fuente en tu CSS */
        body {
            font-family: 'Futura', sans-serif !important;
            /* Aplica la fuente a todo el cuerpo de la página */
            color: #757575 !important;
        }

        .futura{
            font-family: 'Futura', sans-serif !important;
        }

        .playfair-display {
            font-family: 'Playfair Display', sans-serif !important;
        }

        .arial {
            font-family: 'Arial', sans-serif !important;
        }

        .linotype {
            font-family: 'Linotype', sans-serif !important;
        }

        .futura-light {
            font-family: 'Futura Light', sans-serif !important;
        }

        .baskeville-italic {
            font-family: 'Baskeville Italic', sans-serif !important;
        }

        a {
            text-decoration: none;
        }

        h3 {
            font-weight: 200 !important;
        }

        .navbar {
            padding-top: 20px;
            padding-bottom: 20px;
        }

        .navbar-nav .nav-link {
            font-size: 18px;
            color: #6c757d;
            /* Light grey color */
        }

        .navbar-nav .nav-link:hover {
            color: #000;
            /* Black on hover */
        }

        .navbar-nav .dropdown-menu {
            border: none;
            /* Remove border */
        }

        .divider {
            border-left: 1px solid #6c757d;
            height: 30px;
            margin-left: 15px;
            margin-right: 15px;
        }

        /* Dropdown on hover */
        .dropdown:hover .dropdown-menu {
            display: block;
            margin-top: 0;
            /* Fixes the margin issue when dropdown is shown */
        }

        .profile .profile-pic {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            object-fit: cover;
        }

        .profile .dropdown-menu a {
            color: #4a4a4a;
        }

        .profile .dropdown-menu a:hover {
            color: #000;
            text-decoration: none;
        }

        /* Styling for the bell icon */
        .profile .bell-icon {
            font-size: 1.5rem;
            color: #007bff;
            margin-right: 15px;
        }

        /* Add some spacing between items */
        .profile .dropdown-item {
            padding: 10px 20px;
        }

        /* Line between Profile and Log Out */
        .profile .dropdown-divider {
            margin: 0;
        }

        .swiper-slide {
            opacity: 0.5;
            /* Opacidad inicial de los slides que no están en el centro */
            transition: opacity 0.3s ease;
        }

        .swiper-slide-active {
            opacity: 1;
            /* Opacidad completa del slide en el centro */
        }

        .swiper-slide img {
            width: 'auto';
            /* Ajustar el tamaño de las imágenes */
            height: 200px;
        }

        .btn-principal {
            border-radius: 50px;
            padding: 8px 40px;
            background-color: #322668;
            color: white;
            text-decoration: none;
            letter-spacing: 3px;
            transition: all 0.2s ease, visibility 0s;
        }

        .btn-principal:hover {
            background-color: #3F7652;
            transition: all 0.2s ease, visibility 0s;
        }

        .link {
            text-decoration: none;
            font-weight: bold;
            color: #1f1f1f;
        }

        .card-tips {
            border-radius: 25px;
            padding: 25px;
            background-color: rgba(40, 38, 102, 0.6);
            color: #e0e0e0;
        }

        .bg-image {
            background-repeat: no-repeat;
            background-size: cover;
            padding: 60px 0px;
        }

        .title-header {
            color: white;
            font-size: 18px;
            font-style: italic;
            padding: 0px 20px;
        }

        .variable-width {
            widows: 100%;
            /*
            border-top: 3px solid #1d1d1d;
            border-bottom: 3px solid #1d1d1d;
            */
        }

        .variable-width img {
            height: 200px;
            object-fit: cover;
        }

        /* Responsive collapse menu */
        @media (max-width: 992px) {

            img {
                width: 100%;
            }

            video {
                width: 100%;
            }

            .menu .navbar-collapse {
                justify-content: center;
            }
        }
    </style>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/3.5.0/css/flag-icon.min.css">

    <style>
        #overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 999;
            display: none;
        }

        #newsletter-panel {
            position: fixed;
            top: 0;
            right: -300px;
            width: 300px;
            height: 100%;
            background-color: #000;
            color: #fff;
            transition: right 0.3s ease-in-out;
            z-index: 1001;
            padding: 20px;
        }

        #newsletter-panel.open {
            right: 0;
        }

        .newsletter-title {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 20px;
            color: #a020f0;
        }

        .newsletter-form input {
            background-color: transparent;
            border: 1px solid #fff;
            color: #fff;
            margin-bottom: 10px;
        }

        .newsletter-form input::placeholder {
            color: #aaa;
        }

        .newsletter-form button {
            background-color: #a020f0;
            border: none;
            color: #fff;
            padding: 5px 10px;
            cursor: pointer;
        }

        .close-btn {
            background-color: #666;
            border: none;
            color: #fff;
            padding: 5px 10px;
            cursor: pointer;
            margin-top: 10px;
        }

        .newsletter-toggle {
            position: fixed;
            top: 50%;
            right: 0;
            transform: translateY(-50%) rotate(-90deg);
            transform-origin: right bottom;
            background-color: #a020f0;
            color: #fff;
            padding: 10px 20px;
            cursor: pointer;
            z-index: 999;
        }

        .language-selector .btn {
            background-color: #f8f9fa;
            border-color: #dee2e6;
            color: #495057;
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
        }

        .language-selector .dropdown-menu {
            min-width: 5rem;
            padding: 0.5rem 0;
        }

        .language-selector .dropdown-item {
            padding: 0.25rem 1rem;
        }

        .language-selector .dropdown-item.active {
            background-color: #e9ecef;
            color: #212529;
        }

        .flag-icon {
            display: inline-block;
            width: 1em;
            height: 1em;
            vertical-align: middle;
            margin-right: 0.25rem;
            background-size: contain;
            background-position: 50%;
            background-repeat: no-repeat;
        }

        @media (max-width: 991px) {
            .navbar-nav {
                text-align: center;
            }

            .nav-item {
                padding: 10px 0;
            }

            .divider {
                display: none;
            }

            .language-selector {
                margin-top: 15px;
                text-align: center;
            }

            .dropdown-menu {
                text-align: center;
            }

            .profile .profile-pic {
                margin: 0 auto;
                display: block;
            }

            .navbar-toggler {
                border: none;
            }

            .navbar-toggler:focus {
                box-shadow: none;
            }

            .navbar-brand {
                margin-right: auto;
            }
        }
    </style>
</head>

<body>
    @include('partials.header')

    @yield('content')

    @include('partials.footer')

    <div id="overlay"></div>

    <div id="newsletter-panel">
        <h2 class="newsletter-title">KEEP IN TOUCH!</h2>
        <p>Je m'inscris à la newsletter</p>
        <form class="newsletter-form" id="newsletterForm">
            @csrf
            <input type="text" name="nom" placeholder="Nom" required>
            <input type="email" name="email" placeholder="Email" required>
            <button type="submit">S'inscrire</button>
        </form>
        <div id="newsletterMessage"></div>
        <button class="close-btn" onclick="toggleNewsletter()">Fermer</button>
    </div>

    <div class="newsletter-toggle" onclick="toggleNewsletter()">Newsletter</div>

    <script src="{{ asset('js/app.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
    <script type="text/javascript" src="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('.variable-width').slick({
                infinite: true,
                loop: true,
                speed: 300,
                slidesToShow: 1,
                centerMode: false,
                variableWidth: true,
                autoplay: true,
                arrows: false
            });

            $('#newsletterForm').submit(function(e) {
                e.preventDefault();
                $.ajax({
                    url: '/subscribe-newsletter',
                    method: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        $('#newsletterMessage').html('<p style="color: green;">' + response
                            .message + '</p>');
                        $('#newsletterForm')[0].reset();
                    },
                    error: function(xhr) {
                        $('#newsletterMessage').html('<p style="color: red;">Error: ' + xhr
                            .responseJSON.message + '</p>');
                    }
                });
            });
        });

        function toggleNewsletter() {
            const panel = document.getElementById('newsletter-panel');
            const overlay = document.getElementById('overlay');
            panel.classList.toggle('open');
            if (panel.classList.contains('open')) {
                overlay.style.display = 'block';
            } else {
                overlay.style.display = 'none';
            }
        }

        // Close newsletter panel when clicking outside
        document.addEventListener('click', function(event) {
            const panel = document.getElementById('newsletter-panel');
            const overlay = document.getElementById('overlay');
            const newsletterToggle = document.querySelector('.newsletter-toggle');

            if (!panel.contains(event.target) && !newsletterToggle.contains(event.target) && panel.classList
                .contains('open')) {
                toggleNewsletter();
            }
        });
    </script>
</body>

</html>
