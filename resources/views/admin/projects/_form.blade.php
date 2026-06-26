@php
    $project = $project ?? null;
    $action = $action ?? '#';
    $buttonLabel = $buttonLabel ?? 'Save Project';
    $heading = $heading ?? 'Project';
    $backUrl = $backUrl ?? route('admin.dashboard');
    $currentImages = isset($project) ? (json_decode($project->images ?? '[]', true) ?: []) : [];
@endphp

<section class="admin-page">
    <div class="admin-shell admin-form-shell">
        <div class="admin-header">
            <div>
                <p class="eyebrow">Admin</p>
                <h1>{{ $heading }}</h1>
                <p>{{ $heading === 'Add Project' ? 'Create a new portfolio project entry.' : 'Update the selected portfolio project.' }}</p>
            </div>

            <a class="hero-button hero-button-secondary" href="{{ $backUrl }}">Back to Dashboard</a>
        </div>

        @if ($errors->any())
            <div class="auth-error" role="alert">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ $action }}" class="admin-form" enctype="multipart/form-data" data-project-form>
            @csrf
            @if (($method ?? 'POST') !== 'POST')
                @method($method)
            @endif

            <div class="admin-form-grid">
                <label>
                    <span>Title</span>
                    <input type="text" name="title" value="{{ old('title', $project->title ?? '') }}" required>
                </label>

                <label>
                    <span>Subtitle</span>
                    <input type="text" name="subtitle" value="{{ old('subtitle', $project->subtitle ?? '') }}">
                </label>
            </div>

            <label>
                <span>Description</span>
                <textarea name="description" rows="6" required>{{ old('description', $project->description ?? '') }}</textarea>
            </label>

            <div class="admin-form-grid">
                <label class="admin-upload-field">
                    <span>Thumbnail image</span>
                    <div class="admin-upload-control">
                        <input type="file" name="thumbnail" accept="image/*" {{ $project ? '' : 'required' }} data-upload-input>
                        <button type="button" class="hero-button hero-button-secondary admin-upload-button" data-upload-trigger>Choose file</button>
                        <span class="admin-upload-name" data-upload-name>{{ $project ? 'Leave blank to keep the current thumbnail.' : 'No file selected' }}</span>
                    </div>
                    <small>{{ $project ? 'Leave blank to keep the current thumbnail.' : 'Upload one image for the project thumbnail.' }}</small>
                </label>

                <label>
                    <span>GitHub URL</span>
                    <input type="url" name="github_url" value="{{ old('github_url', $project->github_url ?? '') }}" placeholder="https://github.com/...">
                </label>
            </div>

            @if ($project && !empty($project->thumbnail))
                <div class="admin-preview">
                    <span>Current thumbnail</span>
                    <img src="{{ asset('images/' . $project->thumbnail) }}" alt="{{ $project->title }} thumbnail" loading="lazy">
                </div>
            @endif

            <label>
                <span>Technologies</span>
                <textarea name="technologies" rows="4" placeholder="One item per line or comma-separated">{{ old('technologies', isset($project) ? implode(",\n", json_decode($project->technologies ?? '[]', true) ?: []) : '') }}</textarea>
            </label>

            @if ($project)
                <label class="admin-upload-field">
                    <span>Project images</span>
                    <div class="admin-upload-control">
                        <input type="file" name="images[]" accept="image/*" multiple data-upload-input>
                        <button type="button" class="hero-button hero-button-secondary admin-upload-button" data-upload-trigger>Choose files</button>
                        <button type="button" class="hero-button hero-button-primary admin-upload-button" data-submit-project-form data-upload-url="{{ route('admin.projects.images.store', $project) }}">Upload images</button>
                        <span class="admin-upload-name" data-upload-name>No files selected</span>
                    </div>
                    <small>You can select multiple images. Click Upload images to save them without leaving the page.</small>
                </label>

                <div class="admin-gallery-preview">
                    <span>Current gallery</span>
                    <div class="admin-gallery-grid" data-gallery-grid>
                        @forelse ($currentImages as $image)
                            <div class="admin-gallery-item" data-gallery-item data-image-name="{{ $image }}">
                                <img src="{{ asset('images/' . $image) }}" alt="{{ $project->title }} gallery image" loading="lazy">
                                <button type="button" class="admin-gallery-remove-button" data-remove-image>Delete</button>
                            </div>
                        @empty
                            <p class="admin-gallery-empty" data-gallery-empty>No gallery images yet.</p>
                        @endforelse
                    </div>
                </div>
            @endif

            <label class="admin-check">
                <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $project->is_featured ?? false) ? 'checked' : '' }}>
                <span>Featured project</span>
            </label>

            <button type="submit" class="hero-button hero-button-primary">{{ $buttonLabel }}</button>
        </form>
    </div>
</section>