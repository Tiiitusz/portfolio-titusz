@extends('layouts.app')

@section('title', 'Login')

@section('content')
    <section class="auth-page">
        <div class="auth-card">
            <p class="eyebrow">Admin Access</p>
            <h1>Login</h1>

            @if ($errors->any())
                <div class="auth-error" role="alert">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('login.authenticate') }}" class="auth-form">
                @csrf

                <label>
                    <span>Email</span>
                    <input type="email" name="email" value="{{ old('email') }}" required autofocus>
                </label>

                <label>
                    <span>Password</span>
                    <input type="password" name="password" required>
                </label>

                <label class="auth-remember">
                    <input type="checkbox" name="remember" value="1">
                    <span>Remember me</span>
                </label>

                <button type="submit" class="hero-button hero-button-primary">Sign in</button>
            </form>
        </div>
    </section>
@endsection