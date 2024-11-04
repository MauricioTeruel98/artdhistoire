<!-- Main Navbar -->
<nav class="navbar navbar-expand-lg navbar-light bg-white menu">
    <div class="container">
        <!-- Button for mobile collapse -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMenu"
            aria-controls="navbarMenu" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse justify-content-center" id="navbarMenu">
            <ul class="navbar-nav">
                <!-- First item -->
                <li class="nav-item dropdown">
                    <a class="nav-link" href="/" id="newsDropdown" role="button">
                        {{ app()->getLocale() == 'fr' ? 'Nos actualités' : 'News' }}
                    </a>
                </li>

                <div class="divider"></div> <!-- Vertical divider -->

                <!-- Second item -->
                <li class="nav-item dropdown">
                    <a class="nav-link" href="/interactive/index" id="lisaDropdown" role="button">
                        {{ app()->getLocale() == 'fr' ? 'Vidéos interactives LISA' : 'LISA Interactive Videos' }}
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="lisaDropdown">
                        <li><a class="dropdown-item"
                                href="/interactive">{{ app()->getLocale() == 'fr' ? 'Pilote' : 'Pilot' }}</a></li>
                        @php
                            $sagas = DB::table('categories')->where('is_pilote', '!=', 1)->get();
                        @endphp

                        @foreach ($sagas as $saga)
                            <li><a class="dropdown-item"
                                    href="/interactive/{{ $saga->id }}">{{ app()->getLocale() == 'fr' ? $saga->name_fr : $saga->name }}</a>
                            </li>
                        @endforeach
                    </ul>
                </li>

                <div class="divider"></div> <!-- Vertical divider -->

                <!-- Fourth item -->
                <li class="nav-item dropdown">
                    <a class="nav-link" href="/videos-online" id="onlineVideosDropdown" role="button">
                        {{ app()->getLocale() == 'fr' ? 'Vidéos en ligne' : 'Online Videos' }}
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="onlineVideosDropdown">
                        @php
                            $videos = DB::table('videosonline')->get();
                        @endphp
                        @foreach ($videos as $video)
                            <li><a class="dropdown-item"
                                    href="/video-online/{{ $video->id }}">{{ app()->getLocale() == 'fr' ? $video->title_fr : $video->title }}</a>
                            </li>
                        @endforeach
                    </ul>
                </li>

                <div class="divider"></div> <!-- Vertical divider -->

                <!-- Fifth item -->
                <li class="nav-item dropdown">
                    <a class="nav-link" href="/about" id="aboutDropdown" role="button">
                        {{ app()->getLocale() == 'fr' ? 'À propos de nous' : 'About Us' }}
                    </a>
                </li>

                <!-- Bell icon -->
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="bi bi-bell bell-icon"></i> <!-- Bootstrap Icon for bell -->
                    </a>
                </li>

                <li>
                    <button class="btn btn-sm btn-outline-secondary me-2" type="button" id="searchToggle">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-search">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0" />
                            <path d="M21 21l-6 -6" />
                        </svg>
                    </button>
                </li>

                <div class="language-selector dropdown">
                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button"
                        id="languageDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        @if (app()->getLocale() == 'fr')
                            <span class="flag-icon flag-icon-fr"></span> FR
                        @else
                            <span class="flag-icon flag-icon-gb"></span> EN
                        @endif
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="languageDropdown">
                        <li>
                            <a class="dropdown-item {{ app()->getLocale() == 'fr' ? 'active' : '' }}"
                                href="{{ route('language.switch', 'fr') }}">
                                <span class="flag-icon flag-icon-fr"></span> FR
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item {{ app()->getLocale() == 'en' ? 'active' : '' }}"
                                href="{{ route('language.switch', 'en') }}">
                                <span class="flag-icon flag-icon-gb"></span> EN
                            </a>
                        </li>
                    </ul>
                </div>

                @php
                    $user = Auth::user();
                @endphp

                @if ($user)
                    <!-- User profile dropdown -->
                    <li class="nav-item dropdown profile ms-2">
                        <a class="nav-link dropdown-toggle" href="#" id="profileDropdown" role="button"
                            data-bs-toggle="dropdown" aria-expanded="false" style="padding: 0px;">
                            @if ($user->avatar)
                                <img src="{{ asset('storage/' . $user->avatar) }}" alt="Profile" class="profile-pic"
                                    onerror="this.onerror=null; this.src='{{ asset('img/user.png') }}'">
                            @else
                                <img src="{{ asset('img/user.png') }}" alt="Profile" class="profile-pic">
                            @endif
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileDropdown">

                            <li><a class="dropdown-item"
                                    href="/profile">{{ app()->getLocale() == 'fr' ? 'Profil' : 'Profile' }}</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button
                                        class="dropdown-item">{{ app()->getLocale() == 'fr' ? 'Déconnexion' : 'Log Out' }}</button>
                                </form>
                            </li>

                        </ul>
                    </li>
                @else
                    <li class="nav-item dropdown">
                        <a class="nav-link" href="/login" id="newsDropdown" role="button">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round"
                                class="icon icon-tabler icons-tabler-outline icon-tabler-user-circle">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" />
                                <path d="M12 10m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0" />
                                <path d="M6.168 18.849a4 4 0 0 1 3.832 -2.849h4a4 4 0 0 1 3.834 2.855" />
                            </svg>
                            {{ app()->getLocale() == 'fr' ? 'Se connecter' : 'Login' }}
                        </a>
                    </li>
                @endif
            </ul>
        </div>
    </div>
</nav>

<div id="searchBar" class="bg-light py-3" style="display: none;">
    <div class="container">
        <form id="searchForm">
            <div class="input-group">
                <input type="text" class="form-control" id="searchInput"
                    placeholder="{{ app()->getLocale() == 'fr' ? 'Recherche de contenu' : 'Search for content' }}">
                <button class="btn btn-outline-secondary" type="submit">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-search">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0" />
                        <path d="M21 21l-6 -6" />
                    </svg>
                </button>
            </div>
        </form>
        <div id="searchResults" class="mt-3"></div>
    </div>
</div>
