@extends('layouts.guest')

@section('title', 'Login')

@section('content')
<div class="auth-screen login-screen">
    <a href="{{ route('welcome') }}" class="login-back" aria-label="Back">
        <i class="bi bi-arrow-left"></i>
    </a>

    <div class="login-header text-center">
        <div class="mapoly-logo-bold" style="width:96px;height:96px;border-radius:22px;margin:0 auto 1rem">
            <img src="{{ asset('images/mapoly-logo.png') }}" alt="MAPOLY Logo">
        </div>
        <div class="fw-bold text-uppercase mb-2" style="letter-spacing:.1em;color:#6b1d2a;font-size:.75rem">
            {{ config('mapoly.institution.short') }}
        </div>
        <h1 class="login-title">Login</h1>
        <p class="login-subtitle">{{ config('mapoly.project.short_title') }} — sign in to continue</p>
    </div>

    <div class="mb-4 p-3" style="background:#f8f9fb;border-radius:14px;border:1px solid #e5e7eb">
        <div class="small fw-semibold mb-2">Choose the correct account</div>
        <div class="small text-muted mb-1">
            <strong>Lecturer / Admin panel:</strong> login with email
            <code>lecturer@demo.com</code>
        </div>
        <div class="small text-muted mb-0">
            <strong>Student app:</strong> login with matric number
            <code>CS/2022/001</code>
        </div>
    </div>

    @include('layouts.partials.alerts')

    <form method="POST" action="{{ route('login') }}" class="login-form">
        @csrf

        <div class="mb-3">
            <label class="form-label login-label" for="login">Matric Number or Email</label>
            <input type="text" name="login" id="login" value="{{ old('login') }}"
                   class="form-control login-input @error('login') is-invalid @enderror"
                   placeholder="Student matric or lecturer email" required autofocus autocomplete="username">
            @error('login') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-2">
            <label class="form-label login-label" for="password">Password</label>
            <div class="password-field">
                <input type="password" name="password" id="password"
                       class="form-control login-input @error('password') is-invalid @enderror"
                       placeholder="Enter your password" required autocomplete="current-password">
                <button type="button" class="password-toggle toggle-pass" tabindex="-1" aria-label="Show password">
                    <i class="bi bi-eye"></i>
                </button>
            </div>
            @error('password') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
        </div>

        <div class="text-end mb-4">
            <a href="#" class="forgot-link">Forgot Password?</a>
        </div>

        <button class="btn btn-brand btn-login w-100" type="submit">Login</button>
    </form>

    <p class="login-footer text-center">
        Student without account? <a href="{{ route('register') }}">Register</a>
    </p>
    <p class="text-center text-muted small mt-3 mb-0">
        {{ config('mapoly.institution.short') }} ·
        <a href="{{ route('about') }}" style="color:#4C44CF;font-weight:600;text-decoration:none">About / How to use</a>
    </p>
</div>
@endsection
