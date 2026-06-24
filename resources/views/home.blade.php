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

            @php
                $dummyProjects = [
                    [
                        'title' => 'Aurora Analytics Dashboard',
                        'description' => 'A data-heavy admin dashboard with real-time charts, alerts, and export features.',
                        'url' => '/projects/aurora-analytics-dashboard',
                        'image' => 'https://picsum.photos/seed/aurora-dashboard/460/260',
                    ],
                    [
                        'title' => 'Nomad Travel Planner',
                        'description' => 'A trip-building app that combines itinerary mapping, budget tracking, and booking notes.',
                        'url' => '/projects/nomad-travel-planner',
                        'image' => 'https://picsum.photos/seed/nomad-planner/460/260',
                    ],
                    [
                        'title' => 'Pulse Fitness Tracker',
                        'description' => 'A mobile-first platform for workout logging, progress streaks, and trainer messaging.',
                        'url' => '/projects/pulse-fitness-tracker',
                        'image' => 'https://picsum.photos/seed/pulse-fitness/460/260',
                    ],
                    [
                        'title' => 'Civic Service Portal',
                        'description' => 'An appointment and request system for municipal services with status updates for residents.',
                        'url' => '/projects/civic-service-portal',
                        'image' => 'https://picsum.photos/seed/civic-portal/460/260',
                    ],
                    [
                        'title' => 'Studio Booking Suite',
                        'description' => 'A scheduling suite for creative studios with calendar sync, invoices, and package management.',
                        'url' => '/projects/studio-booking-suite',
                        'image' => 'https://picsum.photos/seed/studio-booking/460/260',
                    ],
                    [
                        'title' => 'FreshCart Grocery App',
                        'description' => 'A streamlined ecommerce experience for grocery delivery with smart substitutions and reorder lists.',
                        'url' => '/projects/freshcart-grocery-app',
                        'image' => 'https://picsum.photos/seed/freshcart-app/460/260',
                    ],
                    [
                        'title' => 'Event Horizon Tickets',
                        'description' => 'A ticketing platform with tiered access, QR check-in, and host-level attendance analytics.',
                        'url' => '/projects/event-horizon-tickets',
                        'image' => 'https://picsum.photos/seed/event-horizon/460/260',
                    ],
                    [
                        'title' => 'Atlas Real Estate CRM',
                        'description' => 'A sales and lead tracking CRM tailored for property agents and agency-level reporting.',
                        'url' => '/projects/atlas-real-estate-crm',
                        'image' => 'https://picsum.photos/seed/atlas-crm/460/260',
                    ],
                    [
                        'title' => 'Mentor Connect Platform',
                        'description' => 'A mentoring hub with profile matching, session notes, and milestone-based learning plans.',
                        'url' => '/projects/mentor-connect-platform',
                        'image' => 'https://picsum.photos/seed/mentor-connect/460/260',
                    ],
                    [
                        'title' => 'FleetOps Logistics Hub',
                        'description' => 'A logistics control center for dispatch, route optimization, and delivery proof management.',
                        'url' => '/projects/fleetops-logistics-hub',
                        'image' => 'https://picsum.photos/seed/fleetops-hub/460/260',
                    ],
                    [
                        'title' => 'Glacier Finance UI Kit',
                        'description' => 'A design-to-code component library for fintech onboarding flows and account management tools.',
                        'url' => '/projects/glacier-finance-ui-kit',
                        'image' => 'https://picsum.photos/seed/glacier-finance/460/260',
                    ],
                    [
                        'title' => 'Cinema Club Membership',
                        'description' => 'A membership web app for cinemas with loyalty points, seat perks, and monthly plans.',
                        'url' => '/projects/cinema-club-membership',
                        'image' => 'https://picsum.photos/seed/cinema-club/460/260',
                    ],
                ];
            @endphp

            <div class="projects-list-wrapper" data-projects-list data-page-size="5" aria-live="polite">
                <ul class="projects-list" role="list">
                    @foreach ($dummyProjects as $project)
                        <li class="project-item" data-project-item>
                            <a class="project-image-link" href="{{ $project['url'] }}" aria-label="Open {{ $project['title'] }} project page">
                                <img src="{{ $project['image'] }}" alt="{{ $project['title'] }} preview" loading="lazy">
                            </a>
                            <div class="project-content">
                                <a class="project-title" href="{{ $project['url'] }}">{{ $project['title'] }}</a>
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
