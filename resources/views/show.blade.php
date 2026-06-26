@extends('layouts.app')

@section('title', $project->title)

@push('styles')
    @vite(['resources/css/show.css'])
@endpush

@push('scripts')
    @vite(['resources/js/carousel.js'])
@endpush

@section('content')
    @php
        $technologies = json_decode($project->technologies ?? '[]', true) ?: [];
        $images = json_decode($project->images ?? '[]', true) ?: [];
    @endphp

    <article class="show-page">
        <section class="show-top" style="background-image: linear-gradient(180deg, rgba(2, 6, 23, 0.24), rgba(2, 6, 23, 0.78)), url('{{ asset('images/' . $project->thumbnail) }}');">
            <a class="show-back-link" href="/" aria-label="Back to main site">
                <span aria-hidden="true">←</span>
                <span>Back</span>
            </a>

            <div class="titles">
                <p class="show-eyebrow">Project</p>
                <h1>{{ $project->title }}</h1>
                @if ($project->subtitle)
                    <h2>{{ $project->subtitle }}</h2>
                @endif
            </div>
        </section>

        <section class="show-middle">
            <div class="show-content">
                <div class="show-technologies">
                    @foreach ($technologies as $technology)
                        <span class="hero-button hero-button-secondary">{{ $technology }}</span>
                    @endforeach
                </div>

                <div class="show-description">
                    <p>{{ $project->description }}</p>
                    @if ($project->github_url)
                        <a class="hero-button hero-button-primary" href="{{ $project->github_url }}" target="_blank" rel="noopener">View on GitHub</a>
                    @endif
                </div>
            </div>

            @if (!empty($images))
                <div class="show-carousel" aria-label="Project image carousel" data-carousel>
                    <figure class="show-carousel-main-item">
                        <img
                            class="show-carousel-main"
                            src="{{ asset('images/' . $images[0]) }}"
                            alt="{{ $project->title }} preview image"
                            loading="lazy"
                            data-carousel-main
                        >
                    </figure>

                    <div class="show-carousel-thumbs" aria-label="Project image thumbnails">
                        @foreach ($images as $image)
                            <button
                                type="button"
                                class="show-carousel-item"
                                aria-label="Show image {{ $loop->iteration }} of {{ count($images) }}"
                                data-carousel-thumb
                                data-carousel-src="{{ asset('images/' . $image) }}"
                                data-carousel-alt="{{ $project->title }} preview image {{ $loop->iteration }}"
                            >
                                <img
                                    src="{{ asset('images/' . $image) }}"
                                    alt="{{ $project->title }} thumbnail {{ $loop->iteration }}"
                                    loading="lazy"
                                >
                            </button>
                        @endforeach
                    </div>
                </div>
            @endif
        </section>
    </article>

@endsection 