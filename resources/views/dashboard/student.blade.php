@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
@php
    $user = auth()->user();
    $first = explode(' ', $user->name)[0];
    $initial = strtoupper(substr($user->name, 0, 1));
    $pct = max(0, min(100, (float) $percentage));
    $r = 54;
    $c = 2 * M_PI * $r;
    $offset = $c - ($pct / 100) * $c;
    $levelLabel = $user->level ? (is_numeric($user->level) ? 'Level '.$user->level : $user->level) : '';
@endphp

{{-- Header --}}
<div class="dash-header">
    <h1 class="dash-page-title">Dashboard</h1>
    <div class="avatar avatar-md" aria-hidden="true">{{ $initial }}</div>
</div>

{{-- Profile card --}}
<div class="dash-profile-card">
    <div class="avatar avatar-lg">{{ $initial }}</div>
    <div class="dash-profile-text">
        <div class="dash-hello">Hello, {{ $first }} 👋</div>
        <div class="dash-meta">{{ $user->department ?: 'Department' }}</div>
        @if($levelLabel)
            <div class="dash-meta">{{ $levelLabel }}</div>
        @endif
    </div>
</div>

{{-- Overall attendance --}}
<div class="dash-attendance-card">
    <div class="dash-attendance-title">Overall Attendance</div>
    <div class="dash-ring">
        <svg width="148" height="148" viewBox="0 0 148 148">
            <circle cx="74" cy="74" r="{{ $r }}" fill="none" stroke="#EDE9FE" stroke-width="12"></circle>
            <circle cx="74" cy="74" r="{{ $r }}" fill="none" stroke="#6C5CE7" stroke-width="12"
                    stroke-linecap="round"
                    stroke-dasharray="{{ $c }}"
                    stroke-dashoffset="{{ $offset }}"
                    transform="rotate(-90 74 74)"></circle>
        </svg>
        <div class="dash-ring-value">{{ (int) $percentage }}%</div>
    </div>
    <div class="dash-standing">{{ $standing }}</div>
</div>

{{-- Quick actions --}}
<h2 class="dash-section-title">Quick Actions</h2>

<a href="{{ route('attendance.scan') }}" class="dash-action">
    <div class="dash-action-icon"><i class="bi bi-qr-code-scan"></i></div>
    <div class="dash-action-copy">
        <div class="dash-action-title">Scan QR Code</div>
        <div class="dash-action-sub">Mark your attendance</div>
    </div>
    <i class="bi bi-chevron-right dash-action-chev"></i>
</a>

<a href="{{ route('attendance.history') }}" class="dash-action">
    <div class="dash-action-icon"><i class="bi bi-file-earmark-text"></i></div>
    <div class="dash-action-copy">
        <div class="dash-action-title">My Attendance</div>
        <div class="dash-action-sub">View your attendance history</div>
    </div>
    <i class="bi bi-chevron-right dash-action-chev"></i>
</a>
@endsection
