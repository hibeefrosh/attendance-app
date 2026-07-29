@extends('layouts.app')

@section('title', 'My Attendance')

@section('content')
<div class="hist-topbar">
    <a href="{{ route('dashboard') }}" class="hist-icon-btn" aria-label="Back"><i class="bi bi-arrow-left"></i></a>
    <h1 class="hist-page-title">My Attendance</h1>
    <button class="hist-icon-btn" type="button" data-bs-toggle="collapse" data-bs-target="#courseFilter" aria-label="Filter">
        <i class="bi bi-funnel"></i>
    </button>
</div>

<form method="GET" class="collapse show mb-3" id="courseFilter">
    <div class="hist-select-wrap">
        <select name="course_id" class="form-select hist-select" onchange="this.form.submit()">
            @forelse($courses as $course)
                <option value="{{ $course->id }}" @selected(($selectedCourse->id ?? null) == $course->id)>
                    {{ $course->title }}
                </option>
            @empty
                <option value="">No courses</option>
            @endforelse
        </select>
    </div>
</form>

<div class="row g-2 mb-4">
    <div class="col-4">
        <div class="mini-stat">
            <div class="val val-purple">{{ $stats['total'] }}</div>
            <div class="lbl">Total Sessions</div>
        </div>
    </div>
    <div class="col-4">
        <div class="mini-stat">
            <div class="val val-green">{{ $stats['attended'] }}</div>
            <div class="lbl">Attended</div>
        </div>
    </div>
    <div class="col-4">
        <div class="mini-stat">
            <div class="val val-purple">{{ $stats['percentage'] }}%</div>
            <div class="lbl">Percentage</div>
        </div>
    </div>
</div>

<h2 class="dash-section-title">Attendance History</h2>

@if($sessions instanceof \Illuminate\Pagination\AbstractPaginator)
    @forelse($sessions as $session)
        @php
            $present = $session->attendanceRecords->isNotEmpty();
            $checkIn = $session->attendanceRecords->first()?->checked_in_at;
        @endphp
        <div class="history-item">
            <div>
                <div class="fw-semibold hist-date">
                    {{ $session->session_date->format('M d, Y') }}
                    <span class="text-muted fw-normal">|</span>
                    {{ substr($session->start_time, 0, 5) }} - {{ substr($session->end_time, 0, 5) }}
                </div>
                @if($checkIn)
                    <div class="text-muted small">Checked in {{ $checkIn->format('g:i A') }}</div>
                @endif
            </div>
            <span class="badge-pill {{ $present ? 'badge-present' : 'badge-absent' }}">
                {{ $present ? 'Present' : 'Absent' }}
            </span>
        </div>
    @empty
        <div class="text-center text-muted py-5">No sessions found for this course.</div>
    @endforelse

    @if($sessions->hasPages())
        <div class="mt-3">{{ $sessions->links() }}</div>
    @endif
@else
    <div class="text-center text-muted py-5">Enroll in a course to see attendance history.</div>
@endif
@endsection
