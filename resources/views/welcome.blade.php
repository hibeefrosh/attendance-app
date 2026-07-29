@extends('layouts.guest')

@section('title', 'Smart Attendance')

@section('content')
<div class="welcome-screen">
    <div class="welcome-top text-center">
        <div class="welcome-logo-badge mx-auto mb-3" aria-hidden="true">
            <i class="bi bi-qr-code"></i>
        </div>

        <h1 class="welcome-title">Smart Attendance</h1>
        <p class="welcome-tagline">Scan. Mark. Track.</p>
        <p class="welcome-desc">A modern QR code based attendance system</p>
    </div>

    <div class="welcome-illustration" aria-hidden="true">
        {{-- Icon-based illustration (no image asset required) --}}
        <svg class="welcome-svg" viewBox="0 0 320 240" xmlns="http://www.w3.org/2000/svg" role="img">
            <defs>
                <linearGradient id="wg" x1="0" y1="0" x2="1" y2="1">
                    <stop offset="0%" stop-color="#8B85F0"/>
                    <stop offset="100%" stop-color="#4C44CF"/>
                </linearGradient>
                <linearGradient id="phoneGrad" x1="0" y1="0" x2="0" y2="1">
                    <stop offset="0%" stop-color="#EEF0FF"/>
                    <stop offset="100%" stop-color="#D8DBFF"/>
                </linearGradient>
            </defs>

            {{-- Soft ground blob --}}
            <ellipse cx="160" cy="210" rx="130" ry="18" fill="#E8E6FF" opacity=".9"/>

            {{-- Large phone with QR --}}
            <rect x="150" y="28" width="130" height="175" rx="22" fill="url(#phoneGrad)" stroke="#4C44CF" stroke-width="4"/>
            <rect x="162" y="48" width="106" height="106" rx="10" fill="#fff"/>
            {{-- QR pattern --}}
            <rect x="172" y="58" width="28" height="28" rx="3" fill="#4C44CF"/>
            <rect x="178" y="64" width="16" height="16" rx="1" fill="#fff"/>
            <rect x="230" y="58" width="28" height="28" rx="3" fill="#4C44CF"/>
            <rect x="236" y="64" width="16" height="16" rx="1" fill="#fff"/>
            <rect x="172" y="116" width="28" height="28" rx="3" fill="#4C44CF"/>
            <rect x="178" y="122" width="16" height="16" rx="1" fill="#fff"/>
            <rect x="210" y="90" width="12" height="12" fill="#4C44CF"/>
            <rect x="226" y="90" width="12" height="12" fill="#4C44CF"/>
            <rect x="210" y="106" width="12" height="12" fill="#4C44CF"/>
            <rect x="242" y="106" width="12" height="12" fill="#4C44CF"/>
            <rect x="210" y="122" width="20" height="8" fill="#4C44CF"/>
            <rect x="236" y="122" width="20" height="8" fill="#4C44CF"/>
            <rect x="210" y="136" width="8" height="8" fill="#4C44CF"/>
            <rect x="222" y="136" width="20" height="8" fill="#4C44CF"/>
            <circle cx="215" cy="188" r="6" fill="#C5C2F5"/>

            {{-- Student figure --}}
            <circle cx="78" cy="78" r="22" fill="#F0C7A8"/>
            <path d="M56 68c4-18 40-18 44 2-8 6-36 6-44-2z" fill="#2C2A4A"/>
            <rect x="58" y="100" width="40" height="52" rx="12" fill="#4C44CF"/>
            {{-- Backpack --}}
            <rect x="48" y="108" width="14" height="36" rx="6" fill="#1E1B4B"/>
            {{-- Arm holding phone --}}
            <path d="M98 118c18 4 28 18 30 28" stroke="#F0C7A8" stroke-width="10" stroke-linecap="round" fill="none"/>
            {{-- Small phone in hand --}}
            <rect x="118" y="138" width="34" height="52" rx="7" fill="#fff" stroke="#4C44CF" stroke-width="3" transform="rotate(-18 135 164)"/>
            <rect x="124" y="146" width="20" height="28" rx="2" fill="#EEF0FF" transform="rotate(-18 134 160)"/>

            {{-- Scan beam --}}
            <path d="M148 155 L168 95" stroke="#8B85F0" stroke-width="2" stroke-dasharray="4 4" opacity=".7"/>
            <circle cx="158" cy="125" r="4" fill="#8B85F0" opacity=".5"/>
        </svg>

        <div class="welcome-wave"></div>
    </div>

    <div class="welcome-actions">
        <a href="{{ route('register') }}" class="btn btn-brand btn-lg w-100">Get Started</a>
        <a href="{{ route('login') }}" class="welcome-login-link">Login</a>
    </div>
</div>
@endsection
