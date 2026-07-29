@extends('layouts.app')

@section('title', 'Attendance Success')

@section('success')
<div class="success-screen">
    <div class="success-topbar">
        <a href="{{ route('dashboard') }}" class="success-back" aria-label="Back"><i class="bi bi-arrow-left"></i></a>
        <h1 class="success-page-title">Attendance Success</h1>
        <span class="success-top-spacer"></span>
    </div>

    <div class="success-check-wrap">
        <span class="confetti c1"></span>
        <span class="confetti c2"></span>
        <span class="confetti c3"></span>
        <span class="confetti c4"></span>
        <span class="confetti c5"></span>
        <span class="confetti c6"></span>
        <div class="success-check"><i class="bi bi-check-lg"></i></div>
    </div>

    <h2 class="success-headline">Attendance Marked!</h2>
    <p class="success-sub">You have successfully marked your attendance</p>

    <div class="detail-card">
        <div class="detail-row">
            <span class="lbl">Course</span>
            <span class="val">{{ $record->course->title }}</span>
        </div>
        <div class="detail-row">
            <span class="lbl">Date</span>
            <span class="val">{{ $record->attendanceSession->session_date->format('M d, Y') }}</span>
        </div>
        <div class="detail-row">
            <span class="lbl">Time</span>
            <span class="val">{{ $record->checked_in_at->format('g:i A') }}</span>
        </div>
        <div class="detail-row">
            <span class="lbl">Session</span>
            <span class="val">{{ $record->attendanceSession->title ?: $record->course->code }}</span>
        </div>
    </div>

    <a href="{{ route('dashboard') }}" class="btn btn-brand btn-login w-100 mb-3">Done</a>
    <a href="{{ route('attendance.history') }}" class="success-link">View My Attendance</a>
</div>
@endsection
