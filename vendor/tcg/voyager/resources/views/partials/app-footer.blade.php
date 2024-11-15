<footer class="app-footer">
    <div class="site-footer-right">
        @if (rand(1,100) == 100)
            <i class="voyager-rum-1"></i> {{ __('voyager::theme.footer_copyright2') }}
        @else
            Made by InitSoluciones
        @endif
        @php $version = Voyager::getVersion(); @endphp
    </div>
</footer>
