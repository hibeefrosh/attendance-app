@extends('layouts.guest')

@section('title', 'About — '.config('mapoly.project.short_title'))

@section('content')
<div class="auth-screen" style="padding-top:1.5rem">
    <a href="{{ route('welcome') }}" class="login-back" aria-label="Back">
        <i class="bi bi-arrow-left"></i>
    </a>

    <div class="text-center mb-4">
        <div class="welcome-logo-badge mx-auto mb-3" aria-hidden="true">
            <i class="bi bi-mortarboard-fill"></i>
        </div>
        <div class="fw-bold text-uppercase" style="letter-spacing:.08em;color:#4C44CF;font-size:.8rem">
            {{ config('mapoly.institution.short') }}
        </div>
        <h1 class="h4 fw-bold mt-2 mb-1">{{ config('mapoly.institution.full') }}</h1>
        <p class="text-muted small mb-0">{{ config('mapoly.institution.location') }}</p>
    </div>

    <div class="sa-card p-3 mb-3" style="border:1px solid #e5e7eb;border-radius:16px;background:#f8f9fb">
        <div class="text-muted small text-uppercase fw-semibold mb-2">Project</div>
        <h2 class="h6 fw-bold mb-2">{{ config('mapoly.project.title') }}</h2>
        <p class="text-muted small mb-0">
            A student project developed for {{ config('mapoly.institution.short') }} —
            QR-based class attendance using mobile devices.
        </p>
    </div>

    <div class="sa-card p-3 mb-4" style="border:1px solid #e5e7eb;border-radius:16px;background:#fff">
        <div class="text-muted small text-uppercase fw-semibold mb-3">Developed By</div>
        @foreach(config('mapoly.owners') as $owner)
            <div class="d-flex justify-content-between align-items-start py-2 {{ !$loop->last ? 'border-bottom' : '' }}">
                <div class="fw-semibold" style="font-size:.95rem">{{ $owner['name'] }}</div>
                <div class="text-muted small ms-3 text-nowrap">{{ $owner['matric'] }}</div>
            </div>
        @endforeach
    </div>

    <a href="{{ route('welcome') }}" class="btn btn-brand w-100">Back to App</a>
</div>
@endsection
