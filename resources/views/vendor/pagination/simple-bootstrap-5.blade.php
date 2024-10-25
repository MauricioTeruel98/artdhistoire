@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Navigation de pagination">
        <ul class="pagination">
            {{-- Lien de la page précédente --}}
            @if ($paginator->onFirstPage())
                <li class="page-item disabled" aria-disabled="true">
                    <span class="page-link">{{app()->getLocale() == 'fr' ? 'Précédent' : 'Previous'}}</span>
                </li>
            @else
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev">
                        {{app()->getLocale() == 'fr' ? 'Précédent' : 'Previous'}}
                    </a>
                </li>
            @endif

            {{-- Lien de la page suivante --}}
            @if ($paginator->hasMorePages())
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next">{{app()->getLocale() == 'fr' ? 'Suivant' : 'Next'}}</a>
                </li>
            @else
                <li class="page-item disabled" aria-disabled="true">
                    <span class="page-link">{{app()->getLocale() == 'fr' ? 'Suivant' : 'Next'}}</span>
                </li>
            @endif
        </ul>
    </nav>
@endif
