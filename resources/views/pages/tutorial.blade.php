@extends('layouts.applayout')

@section('title', "Art d'Histoire | Home ")

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="ratio ratio-16x9">
                    @php
                        $videoPath = json_decode(app()->getLocale() == 'fr' ? Voyager::setting('site.tutorial') : Voyager::setting('site.tutorial_en'), true);
                        $videoUrl = $videoPath[0]['download_link'] ?? '';
                    @endphp
                    <video class="w-100" src="/storage/{{ $videoUrl }}" controls></video>
                </div>
            </div>
        </div>
    </div>

<style>
    video {
        max-width: 100%;
        height: auto;
        display: block;
        margin: 0 auto;
    }
</style>
@endsection