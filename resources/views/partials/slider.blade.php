<div>
    <div class="variable-width">
        @foreach ($slider as $slide)
        <div>
            <img src="/storage/{{ $slide->image }}" alt="Imagen Slider">
        </div>
        @endforeach
    </div>
</div>