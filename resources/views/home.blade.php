@extends('layouts.app')

@section('title', 'Home')

@section('content')
    <div class="main-inner home-layout">

        <section class="content-card hero-card home-hero-card" data-home-panel>
            <div class="hero-copy">
                <p class="eyebrow">Personal</p>
                <h2>About Me</h2>
                <p>
                    I'm a 24 year old freshly graduated Computer Scientist from ELTE living in Budapest. I'm currently looking for work and planning my next steps in education.
                    My passion is in making software as efficent as possible, this is really usefull when talking about huge amounts of data needing to be processed and used, therefor I specialize in Neural Networks, AI, Data Science and Management.
                    In my free time I like to compete in amature chess tournaments, develop and play videogames, playing cards and Sailing in the summer. 
                </p>

                <div class="hero-actions">
                    <a class="hero-button hero-button-primary" href="#projects">View Projects</a>
                    <a class="hero-button hero-button-secondary" href="#contact">Contact Me</a>
                </div>
            </div>

            <div class="hero-stats" aria-label="Skill highlights">
                <div class="stat-card stat-accent">
                    <span>Skills</span>
                    <strong>AI / Neural Network development and integration, Database management, Data science, Back-end Website development</strong>
                </div>
                <div class="stat-card">
                    <span>Interests</span>
                    <strong>International trades, Politics</strong>
                </div>
                <div class="stat-card">
                    <span>Free Time and Hobbies</span>
                    <strong>Sailing, Chess, Videogame development, Playing cards</strong>
                </div>
            </div>
        </section>


        <section class="content-card pdf-card home-cv-card" id="cv" data-home-panel>
            <div class="hero-copy">
                <p class="eyebrow">Professional</p>
                <h2>Curriculum Vitae</h2>
                <p>
                </p>
                <div class="hero-actions">
                    <a class="hero-button hero-button-primary" href="/documents/cv.pdf" target="_blank" rel="noopener">Open Full PDF</a>
                    <a class="hero-button hero-button-secondary" href="/documents/cv.pdf" download>Download</a>
                </div>
            </div>
            <div class="pdf-window" role="group" aria-label="CV preview image">
                <img
                    src="{{ asset('images/cv.jpg') }}"
                    alt="Preview image of the curriculum vitae"
                    loading="lazy"
                >
            </div>
        </section>

        <section class="content-card home-projects-card" id="projects" data-home-panel>
            <div class="hero-copy">
                <p class="eyebrow">Professional</p>
                <h2>Portfolio</h2>
                <p>
                    My portfolio mainly consists of smaller university projects and some larger personal projects. Project source code not containing github links are either non public or can be found in the description.       
                </p>
            </div>

            <div class="projects-list-wrapper" data-projects-list data-page-size="5" aria-live="polite">
                <ul class="projects-list" role="list">
                    @foreach ($projects as $project)
                        <li class="project-item" data-project-item>
                            <a class="project-image-link" href="{{ route('project.show', ['id' => $project['id']]) }}" aria-label="Open {{ $project['title'] }} project page">
                                <img src="{{ asset('images/' . $project['thumbnail']) }}" alt="{{ $project['title'] }} preview" loading="lazy">
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

        <section class="content-card hero-card home-contact-card" id="contact" data-home-panel>
            <div class="hero-copy">
                <p class="eyebrow">Get In Touch</p>
                <h2>Contact Me</h2>
                <p>
                    Feel free to contact me
                </p>

                <div class="hero-actions">
                    <a class="hero-button hero-button-primary" href="mailto:papptituszpeter@gmail.com">papptituszpeter@gmail.com</a>
                    <a class="hero-button hero-button-secondary" href="https://www.linkedin.com" target="_blank" rel="noopener">LinkedIn</a>
                    <a class="hero-button hero-button-secondary" href="https://github.com" target="_blank" rel="noopener">GitHub</a>
                </div>
            </div>

            <div class="hero-stats" aria-label="Contact details">
                <div class="stat-card">
                    <span>Email</span>
                    <strong>papptituszpeter@gmail.com</strong>
                </div>
                <div class="stat-card">
                    <span>Location</span>
                    <strong>Budapest, Hungary</strong>
                </div>
                <div class="stat-card">
                    <span>Availability</span>
                    <strong>Full-time, Part-time, Contract</strong>
                </div>
            </div>
        </section>
    </div>
@endsection
