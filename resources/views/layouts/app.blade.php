<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'My Portfolio')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/chat.js'])
    @stack('styles')
</head>
<body>
<div class="balaton-scene" aria-hidden="true">
    <div class="balaton-boat">
        <img src="/images/boat.png" alt="" loading="eager" decoding="async">
    </div>
</div>
<!--@include('partials.header')-->

<main>
    @yield('content')
</main>

<!--@include('partials.footer')-->

<!-- Chat Widget -->
<div id="chat-widget">
    <button id="chat-toggle" aria-label="Open chat" aria-expanded="false">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
        </svg>
    </button>

    <div id="chat-window" class="chat-window">
        <div class="chat-header">
            <div class="chat-header-info">
                <div class="chat-avatar" aria-hidden="true">AI</div>
                <div>
                    <h4>Titusz Assistant</h4>
                    <span>&#x2022; Online</span>
                </div>
            </div>
            <div class="chat-header-actions">
                <button id="chat-diagnostic" class="chat-diagnostic" type="button" aria-label="Run chat diagnostics">Diagnose</button>
                <button id="chat-close" aria-label="Close chat">&times;</button>
            </div>
        </div>

        <div class="chat-messages" id="chat-messages" role="log" aria-live="polite">
            <div class="chat-msg bot">Hi! I'm Titusz's assistant. How can I help you today?</div>
        </div>

        <div class="chat-input-area">
            <input id="chat-input" type="text" placeholder="Ask me anything..." autocomplete="off">
            <button id="chat-send">Send</button>
        </div>
    </div>
</div>

@stack('scripts')
</body>
</html>
