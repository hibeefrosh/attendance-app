@extends('layouts.guest')

@section('title', 'Register')

@section('content')
<div class="auth-screen">
    <a href="{{ route('welcome') }}" class="auth-back"><i class="bi bi-arrow-left"></i></a>

    <h1 class="fw-bold mb-1" style="font-size:1.75rem">Create account</h1>
    <p class="text-muted mb-4">Register once to start scanning attendance</p>

    @include('layouts.partials.alerts')

    <form method="POST" action="{{ route('register') }}">
        @csrf
        <div class="mb-3">
            <label class="form-label fw-semibold">Full Name</label>
            <input type="text" name="name" value="{{ old('name') }}" class="form-control @error('name') is-invalid @enderror" required>
            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div class="mb-3">
            <label class="form-label fw-semibold">Matric Number</label>
            <input type="text" name="matric_number" value="{{ old('matric_number') }}" class="form-control @error('matric_number') is-invalid @enderror" required>
            @error('matric_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div class="mb-3">
            <label class="form-label fw-semibold">Email</label>
            <input type="email" name="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror" required>
            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div class="row">
            <div class="col-6 mb-3">
                <label class="form-label fw-semibold">Department</label>
                <input type="text" name="department" value="{{ old('department') }}" class="form-control @error('department') is-invalid @enderror" required>
                @error('department') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-6 mb-3">
                <label class="form-label fw-semibold">Level</label>
                <select name="level" class="form-select @error('level') is-invalid @enderror" required>
                    <option value="">Select</option>
                    @foreach(['100','200','300','400','500'] as $level)
                        <option value="{{ $level }}" @selected(old('level') === $level)>{{ $level }}</option>
                    @endforeach
                </select>
                @error('level') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
        </div>
        <div class="mb-3">
            <label class="form-label fw-semibold">Password</label>
            <div class="input-group">
                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
                <button type="button" class="btn btn-outline-secondary toggle-pass" tabindex="-1" aria-label="Show password">
                    <i class="bi bi-eye"></i>
                </button>
            </div>
            @error('password') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
        </div>
        <div class="mb-4">
            <label class="form-label fw-semibold">Confirm Password</label>
            <input type="password" name="password_confirmation" class="form-control" required>
        </div>
        <button class="btn btn-primary btn-brand btn-lg w-100" type="submit">Register</button>
    </form>

    <p class="text-center mt-4 mb-0 text-muted">
        Already have an account? <a href="{{ route('login') }}" class="fw-semibold">Login</a>
    </p>
</div>
@endsection
