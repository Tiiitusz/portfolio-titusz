@extends('layouts.app')

@section('title', 'Add Project')

@section('content')
    @include('admin.projects._form', [
        'project' => $project,
        'action' => route('admin.projects.store'),
        'method' => 'POST',
        'heading' => 'Add Project',
        'buttonLabel' => 'Save Project',
        'backUrl' => route('admin.dashboard'),
    ])
@endsection@extends('layouts.app')

@section('title', 'Add Project')

@section('content')
    <section class="admin-page">
        <div class="admin-shell admin-form-shell">
            <div class="admin-header">
                <div>
                    <p class="eyebrow">Admin</p>
                    <h1>Add Project</h1>
                    <p>Create a new portfolio project entry.</p>
                </div>

                <a class="hero-button hero-button-secondary" href="{{ route('admin.dashboard') }}">Back to Dashboard</a>
            </div>

            @if ($errors->any())
                <div class="auth-error" role="alert">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('admin.projects.store') }}" class="admin-form">
                @csrf

                <div class="admin-form-grid">
                    <label>
                        <span>Title</span>
                        <input type="text" name="title" value="{{ old('title') }}" required>
                    </label>

                    <label>
                        <span>Subtitle</span>
                        <input type="text" name="subtitle" value="{{ old('subtitle') }}">
                    </label>
                </div>

                <label>
                    <span>Description</span>
                    <textarea name="description" rows="6" required>{{ old('description') }}</textarea>
                </label>

                <div class="admin-form-grid">
                    <label>
