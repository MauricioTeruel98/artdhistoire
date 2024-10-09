@extends('layouts.applayout')

@section('title', "Art d'Histoire | Home")

@section('header')

<style>
    .img-modal{
        padding: 100px;
    }

    .text-video{
        padding: 100px;
    }

    @media (max-width: 768px) {
        .img-modal{
            padding: 50px;
        }

        .text-video{
            padding: 50px;
        }
    }

</style>

@endsection

@section('content')
    @include('partials.banner')
    @include('partials.slider')

    <div class="container mx-auto py-5">
        <header class="text-center mb-5">
            <h1 class="text-4xl font-bold mb-2">LISA</h1>
            <p class="text-lg mb-4">
                Un portail combinant vidéos et anthologie
                <br>
                La pérennité et l'irréfutabilité en un clic
            </p>
            <p class="mb-6">
                LISA pour Learning Interactive Smart Art est notre nouveau portail d'histoire dédié aux arts du XIXe.
            </p>
            <p class="mb-6">
                Les vidéos d'Art d'Histoire sont désormais interactives : apparaissent à l'écran des centaines de pop-up
                cliquables
                donnant accès aux fiches de l'encyclopédie LISA. Chacune de ces fiches est intégralement validée par des
                sources
                primaires lesquelles, numérisées, sont accessibles en quelques clics.
            </p>
            <a href="/interactive/index" class="btn btn-principal">
                Essayez LISA
            </a>
        </header>
        <div class="row">
            @foreach ($videos as $index => $video)
                <div class="col-md-4 mb-4">
                    <a href="#" class="video-link" data-video-id="{{ $video->id }}" data-index="{{ $index }}">
                        <div class="position-relative" style="padding-bottom: 75%;">
                            <img src="/storage/{{$video->home_image}}" alt="Art piece"
                                class="position-absolute w-100 h-100 object-fit-cover">
                            <div class="position-absolute w-100 h-100 d-flex align-items-center justify-content-center">
                                <h2 class="text-white text-center">
                                    {{$video->title}}
                                </h2>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="videoModal" tabindex="-1" aria-labelledby="videoModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-fullscreen">
            <div class="modal-content">
                <div class="container">
                    <div class="modal-body p-0">
                        <button type="button" class="btn-close position-absolute top-0 end-0 m-4 z-3" data-bs-dismiss="modal" aria-label="Close"></button>
                        <div class="row g-0 h-100">
                            <div class="col-md-7 position-relative">
                                <img src="" alt="Video thumbnail" class="img-modal img-fluid w-100 h-100 object-cover" id="modalImage" style="max-height: 700px; object-fit: cover;">
                                <button class="btn btn-link text-black position-absolute top-50 start-0 translate-middle-y fs-5" id="prevVideo">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="35" height="35" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-chevron-left"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M15 6l-6 6l6 6" /></svg>
                                </button>
                                <button class="btn btn-link text-black position-absolute top-50 end-0 translate-middle-y fs-5" id="nextVideo">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="35" height="35" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-chevron-right"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 6l6 6l-6 6" /></svg>
                                </button>
                            </div>
                            <div class="col-md-5 d-flex flex-column text-video">
                                <h2 id="modalTitle" class="fs-1 mb-4"></h2>
                                <div class="flex-grow-1 overflow-auto" style="max-height: calc(100vh - 400px);">
                                    <p id="modalDescription"></p>
                                </div>
                                <a href="#" class="btn btn-link text-black align-self-start mt-4" id="watchVideoBtn">Aller à la page</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const videos = @json($videos);
            let currentIndex = 0;

            const videoLinks = document.querySelectorAll('.video-link');
            const modal = new bootstrap.Modal(document.getElementById('videoModal'));
            const modalImage = document.getElementById('modalImage');
            const modalTitle = document.getElementById('modalTitle');
            const modalDescription = document.getElementById('modalDescription');
            const watchVideoBtn = document.getElementById('watchVideoBtn');
            const prevVideoBtn = document.getElementById('prevVideo');
            const nextVideoBtn = document.getElementById('nextVideo');

            function updateModal(index) {
                const video = videos[index];
                modalImage.src = `/storage/${video.home_image}`;
                modalTitle.textContent = video.title;
                modalDescription.innerHTML = video.text || 'No description available.';
                watchVideoBtn.href = `/video-online/${video.id}`;
                currentIndex = index;
            }

            videoLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    const index = parseInt(this.dataset.index);
                    updateModal(index);
                    modal.show();
                });
            });

            prevVideoBtn.addEventListener('click', function() {
                currentIndex = (currentIndex - 1 + videos.length) % videos.length;
                updateModal(currentIndex);
            });

            nextVideoBtn.addEventListener('click', function() {
                currentIndex = (currentIndex + 1) % videos.length;
                updateModal(currentIndex);
            });
        });
    </script>
@endsection