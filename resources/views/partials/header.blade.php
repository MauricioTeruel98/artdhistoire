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
                        Nos actualité
                    </a>
                    {{-- <ul class="dropdown-menu" aria-labelledby="newsDropdown">
                        <li><a class="dropdown-item" href="#">Latest News</a></li>
                        <li><a class="dropdown-item" href="#">Popular Articles</a></li>
                    </ul> --}}
                </li>

                <div class="divider"></div> <!-- Vertical divider -->

                <!-- Second item -->
                <li class="nav-item dropdown">
                    <a class="nav-link" href="/interactive/index" id="lisaDropdown" role="button">
                        LISA Interactive Videos
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="lisaDropdown">
                        <li><a class="dropdown-item" href="/interactive">Pilote</a></li>
                        @php
                            $sagas = DB::table('categories')->where('name', '!=', 'pilote')->get();
                        @endphp

                        @foreach ($sagas as $saga)
                            <li><a class="dropdown-item" href="/interactive/{{ $saga->id }}">{{ $saga->name }}</a></li>
                        @endforeach
                    </ul>
                </li>

                <div class="divider"></div> <!-- Vertical divider -->

                <!-- Third item -->
                {{-- <li class="nav-item dropdown">
                    <a class="nav-link" href="#" id="programsDropdown" role="button">
                        Programs
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="programsDropdown">
                        <li><a class="dropdown-item" href="#">Programs A-Z</a></li>
                        <li><a class="dropdown-item" href="#">Upcoming Programs</a></li>
                    </ul>
                </li> --}}

                {{-- <div class="divider"></div> --}}

                <!-- Fourth item -->
                <li class="nav-item dropdown">
                    <a class="nav-link" href="/videos-online" id="onlineVideosDropdown" role="button">
                        Online Videos
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="onlineVideosDropdown">
                        @php
                            $videos = DB::table('videosonline')->get();
                        @endphp
                        @foreach ($videos as $video)
                            <li><a class="dropdown-item"
                                    href="/video-online/{{ $video->id }}">{{ $video->title }}</a></li>
                        @endforeach
                    </ul>
                </li>

                <div class="divider"></div> <!-- Vertical divider -->

                <!-- Fifth item -->
                <li class="nav-item dropdown">
                    <a class="nav-link" href="/about" id="aboutDropdown" role="button">
                        About Us
                    </a>
                    {{-- <ul class="dropdown-menu" aria-labelledby="aboutDropdown">
                        <li><a class="dropdown-item" href="#">Our Team</a></li>
                        <li><a class="dropdown-item" href="#">Contact</a></li>
                    </ul> --}}
                </li>

                <!-- Bell icon -->
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="bi bi-bell bell-icon"></i> <!-- Bootstrap Icon for bell -->
                    </a>
                </li>

                @php
                    $user = Auth::user();
                @endphp

                @if ($user)
                    <!-- User profile dropdown -->
                    <li class="nav-item dropdown profile">
                        <a class="nav-link dropdown-toggle" href="#" id="profileDropdown" role="button"
                            data-bs-toggle="dropdown" aria-expanded="false" style="padding: 0px;">
                            <img src="{{ asset('img/user.png') }}" alt="Profile" class="profile-pic">
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileDropdown">

                            <li><a class="dropdown-item" href="/profile">Profile</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button class="dropdown-item">Log Out</button>
                                </form>
                            </li>

                        </ul>
                    </li>
                @else
                    <li class="nav-item dropdown">
                        <a class="nav-link" href="/login" id="newsDropdown" role="button">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round"
                                class="icon icon-tabler icons-tabler-outline icon-tabler-user-circle">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" />
                                <path d="M12 10m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0" />
                                <path d="M6.168 18.849a4 4 0 0 1 3.832 -2.849h4a4 4 0 0 1 3.834 2.855" />
                            </svg>
                            Se conecté
                        </a>
                    </li>
                @endif
            </ul>
        </div>
    </div>
</nav>
