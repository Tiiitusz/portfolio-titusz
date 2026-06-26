@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
    @php
        $projects = $projects ?? collect();
        $totalProjects = $projects->count();
    @endphp

    <section class="admin-page">
        <div class="admin-shell">
            <div class="admin-header">
                <div>
                    <p class="eyebrow">Admin</p>
                    <h1>Dashboard</h1>
                    <p>{{ $totalProjects }} project{{ $totalProjects === 1 ? '' : 's' }} in the portfolio.</p>
                </div>

                <div class="admin-actions">
                    <a class="hero-button hero-button-primary" href="{{ route('admin.projects.create') }}">Add New Project</a>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="hero-button hero-button-secondary">Logout</button>
                    </form>
                </div>
            </div>

            <div class="admin-list" role="list" aria-label="Project list">
                @forelse ($projects as $project)
                    <article class="admin-project" role="listitem">
                        <a class="admin-project-main-link" href="{{ route('admin.projects.edit', $project) }}" aria-label="Edit {{ $project->title }}">
                            <img class="admin-project-thumb" src="{{ asset('images/' . $project->thumbnail) }}" alt="{{ $project->title }} thumbnail" loading="lazy">

                            <div class="admin-project-body">
                                <div class="admin-project-title-row">
                                    <div>
                                        <h2>{{ $project->title }}</h2>
                                        @if ($project->subtitle)
                                            <p>{{ $project->subtitle }}</p>
                                        @endif
                                    </div>

                                    <span class="admin-badge {{ $project->is_featured ? 'is-featured' : '' }}">
                                        {{ $project->is_featured ? 'Featured' : 'Standard' }}
                                    </span>
                                </div>

                                <p class="admin-description">{{ $project->description }}</p>

                                @php
                                    $technologies = json_decode($project->technologies ?? '[]', true) ?: [];
                                @endphp

                                @if (!empty($technologies))
                                    <div class="admin-tags">
                                        @foreach ($technologies as $technology)
                                            <span>{{ $technology }}</span>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </a>

                        <div class="admin-project-links">
                            <a href="{{ route('project.show', $project->id) }}" target="_blank" rel="noopener">View</a>
                            @if ($project->github_url)
                                <a href="{{ $project->github_url }}" target="_blank" rel="noopener">GitHub</a>
                            @endif
                        </div>
                    </article>
                @empty
                    <div class="admin-empty">
                        <h2>No projects yet</h2>
                        <p>Add your first portfolio project to populate this list.</p>
                        <a class="hero-button hero-button-primary" href="{{ route('admin.projects.create') }}">Add New Project</a>
                    </div>
                @endforelse
            </div>
        </div>
    </section>
@endsection