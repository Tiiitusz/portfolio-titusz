@extends('layouts.app')

@section('title', 'Home')

@section('content')
    <div class="main-inner">
        <section class="content-card hero-card">
            <div class="hero-copy">
                <p class="eyebrow">Personal</p>
                <h2>About Me</h2>
                <p>
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
                </p>

                <div class="hero-actions">
                    <a class="hero-button hero-button-primary" href="#projects">View Projects</a>
                    <a class="hero-button hero-button-secondary" href="#contact">Contact Me</a>
                </div>
            </div>

            <div class="hero-stats" aria-label="Skill highlights">
                <div class="stat-card stat-accent">
                    <span>Personal Information</span>
                    <strong>CV</strong>
                </div>
                <div class="stat-card">
                    <span>Portfolio</span>
                    <strong>My previous works</strong>
                </div>
                <div class="stat-card">
                    <span>Free Time</span>
                    <strong>Hobbies</strong>
                </div>
            </div>
        </section>


        <section class="content-card pdf-card" id="cv">
            <div class="hero-copy">
                <p class="eyebrow">Professional</p>
                <h2>Curriculum Vitae</h2>
                <p>
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
                </p>
                <div class="hero-actions">
                    <a class="hero-button hero-button-primary" href="/documents/cv.pdf" target="_blank" rel="noopener">Open Full PDF</a>
                    <a class="hero-button hero-button-secondary" href="/documents/cv.pdf" download>Download</a>
                </div>
            </div>
            <div class="pdf-window" role="group" aria-label="Embedded CV PDF">
                <iframe
                    src="/documents/cv.pdf#view=FitH"
                    title="Curriculum Vitae PDF"
                    loading="lazy"
                ></iframe>
            </div>
        </section>

        <section class="content-card" id="projects">
            <div class="hero-copy">
                <p class="eyebrow">Professional</p>
                <h2>Portfolio</h2>
                <p>
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
                </p>
            </div>

            <div class="projects-list-wrapper" data-projects-list data-page-size="5" aria-live="polite">
                <ul class="projects-list" role="list">
                    @foreach ($projects as $project)
                        <li class="project-item" data-project-item>
                            <a class="project-image-link" href="{{ route('project.show', ['id' => $project['id']]) }}" aria-label="Open {{ $project['title'] }} project page">
                                <img src="{{ asset($project['thumbnail']) }}" alt="{{ $project['title'] }} preview" loading="lazy">
                            </a>
                            <div class="project-content">
                                <a class="project-title" href="{{ route('project.show', ['id' => $project['id']]) }}">{{ $project['title'] }}</a>
                                <p>{{ $project['description'] }}</p>
                            </div>
                        </li>
                    @endforeach
                </ul>

                <div class="projects-pagination" data-pagination-controls>
                    <button class="hero-button hero-button-secondary" type="button" data-pagination-prev>Previous</button>
                    <p class="pagination-status" data-pagination-status>Page 1 / 1</p>
                    <button class="hero-button hero-button-secondary" type="button" data-pagination-next>Next</button>
                </div>
            </div>
        </section>

        <section class="content-card hero-card" id="contact">
            <div class="hero-copy">
                <p class="eyebrow">Get In Touch</p>
                <h2>Contact Me</h2>
                <p>
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
                </p>

                <div class="hero-actions">
                    <a class="hero-button hero-button-primary" href="mailto:hello@example.com">hello@example.com</a>
                    <a class="hero-button hero-button-secondary" href="https://www.linkedin.com" target="_blank" rel="noopener">LinkedIn</a>
                    <a class="hero-button hero-button-secondary" href="https://github.com" target="_blank" rel="noopener">GitHub</a>
                </div>
            </div>

            <div class="hero-stats" aria-label="Contact details">
                <div class="stat-card">
                    <span>Email</span>
                    <strong>hello@example.com</strong>
                </div>
                <div class="stat-card">
                    <span>Location</span>
                    <strong>Budapest, Hungary</strong>
                </div>
                <div class="stat-card">
                    <span>Availability</span>
                    <strong>Open for new projects</strong>
                </div>
            </div>
        </section>
    </div>
@endsection
