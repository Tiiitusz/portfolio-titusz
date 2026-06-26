<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'My Portfolio')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
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
@stack('scripts')
</body>
</html>
