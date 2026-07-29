@extends('layouts.guest')

@section('title', 'Login')

@section('content')
<div class="auth-screen login-screen">
    <a href="{{ route('welcome') }}" class="login-back" aria-label="Back">
        <i class="bi bi-arrow-left"></i>
    </a>

    <div class="login-header text-center">
        <h1 class="login-title">Login</h1>
        <p class="login-subtitle">Welcome back! Please sign in to continue</p>
    </div>

    @include('layouts.partials.alerts')

    <form method="POST" action="{{ route('login') }}" class="login-form">
        @csrf

        <div class="mb-3">
            <label class="form-label login-label" for="login">Matric Number</label>
            <input type="text" name="login" id="login" value="{{ old('login') }}"
                   class="form-control login-input @error('login') is-invalid @enderror"
                   placeholder="Enter your matric number" required autofocus autocomplete="username">
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
        Don’t have an account? <a href="{{ route('register') }}">Register</a>
    </p>
</div>
@endsection
