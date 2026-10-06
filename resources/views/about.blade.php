@extends('layouts.guest')

@section('title', 'About — '.config('mapoly.project.short_title'))

@section('content')
<div class="auth-screen" style="padding-top:1.5rem">
    <a href="{{ url()->previous() !== url()->current() ? url()->previous() : route('welcome') }}" class="login-back" aria-label="Back">
        <i class="bi bi-arrow-left"></i>
    </a>

    <div class="text-center mb-4">
        <div class="mapoly-logo-bold" aria-label="MAPOLY logo">
            <img src="{{ asset('images/mapoly-logo.png') }}" alt="MAPOLY Logo">
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

    <div class="sa-card p-3 mb-3" style="border:1px solid #e5e7eb;border-radius:16px;background:#fff">
        <div class="text-muted small text-uppercase fw-semibold mb-3">Developed By</div>
        @foreach(config('mapoly.owners') as $owner)
            <div class="d-flex justify-content-between align-items-start py-2 {{ !$loop->last ? 'border-bottom' : '' }}">
                <div class="fw-semibold" style="font-size:.95rem">{{ $owner['name'] }}</div>
                <div class="text-muted small ms-3 text-nowrap">{{ $owner['matric'] }}</div>
            </div>
        @endforeach
    </div>

    <div class="sa-card p-3 mb-3" style="border:1px solid #e5e7eb;border-radius:16px;background:#fff">
        <div class="text-muted small text-uppercase fw-semibold mb-3">System roles</div>
        <p class="small mb-2">
            <strong>Lecturer panel (admin for courses):</strong>
            login with <code>lecturer@demo.com</code> / <code>password</code>.
            Create courses, assign students, create sessions, display QR, mark attendance manually, and export reports.
        </p>
        <p class="small mb-0">
            <strong>Student app:</strong>
            login with matric (e.g. <code>CS/2022/001</code>) / <code>password</code>,
            or register a new student account. Scan QR to mark attendance.
        </p>
        <p class="small text-muted mt-2 mb-0">
            There is no separate “Admin” role — the lecturer account is the management side of the system.
        </p>
    </div>

    <div class="sa-card p-3 mb-3" style="border:1px solid #e5e7eb;border-radius:16px;background:#fff">
        <div class="text-muted small text-uppercase fw-semibold mb-3">Where is the QR code generated?</div>
        <ol class="small mb-0 ps-3">
            <li>Login as lecturer</li>
            <li>Go to <strong>Sessions</strong> → create a session</li>
            <li>Click <strong>Activate</strong></li>
            <li>Open <strong>Display QR</strong> (or QR from session page)</li>
        </ol>
        <p class="small text-muted mt-2 mb-0">
            The QR is for <strong>fast attendance marking</strong> in class — not for creating student accounts.
            Students register once with the Register form, then scan QR each lecture.
        </p>
    </div>

    <div class="sa-card p-3 mb-4" style="border:1px solid #e5e7eb;border-radius:16px;background:#fff">
        <div class="text-muted small text-uppercase fw-semibold mb-3">Registration vs attendance</div>
        <p class="small mb-2">
            <strong>1. Student registration (account):</strong>
            Register page → create student profile (name, matric, department, level ND I–HND II).
        </p>
        <p class="small mb-2">
            <strong>2. Course registration (enrolment):</strong>
            Lecturer opens a course → <strong>Assign Students</strong> → select students for that course.
        </p>
        <p class="small mb-0">
            <strong>3. Attendance:</strong>
            Lecturer activates session + shows QR (fast). Students scan.
            If a student cannot scan, lecturer uses <strong>Attendance List → Mark Present</strong> (manual).
        </p>
    </div>

    <a href="{{ route('login') }}" class="btn btn-brand w-100 mb-2">Go to Login</a>
    <a href="{{ route('welcome') }}" class="d-block text-center text-muted small text-decoration-none">Back to home</a>
</div>
@endsection
