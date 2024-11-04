<!-- Banner -->
<div class="" style="background-color: #252563;">
    <div class="container">
        <div class="d-md-flex justify-content-center align-items-center">
            <a href="/">
                <img src="{{ asset('img/logo-header.webp') }}" alt="">
            </a>
            <h2 class="title-header text-white playfair-display">
                {!! app()->getLocale() == 'fr' ? $textos->texto_header : $textos->texto_header_en !!}
            </h2>
        </div>
    </div>
</div>