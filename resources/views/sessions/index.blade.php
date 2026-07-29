@extends('layouts.app')

@section('title', 'Sessions')
@section('page_title', 'Attendance Sessions')
@section('page_subtitle', 'Create and monitor QR attendance sessions')

@section('content')
@if(auth()->user()->isStudent())
    <h1 class="h4 fw-bold mb-3">Sessions</h1>
    @forelse($sessions as $session)
        <a href="{{ route('sessions.show', $session) }}" class="history-item text-decoration-none text-dark">
            <div>
                <div class="fw-semibold">{{ $session->course->code }}</div>
                <div class="text-muted small">{{ $session->session_date->format('M d, Y') }} · {{ substr($session->start_time, 0, 5) }}</div>
            </div>
            <span class="badge-pill {{ $session->status === 'active' ? 'badge-active' : 'badge-closed' }}">{{ ucfirst($session->status) }}</span>
        </a>
    @empty
        <div class="text-center text-muted py-5">No sessions found.</div>
    @endforelse
@else
    <div class="d-flex flex-wrap gap-2 justify-content-between align-items-center mb-3">
        <form class="row g-2" method="GET">
            <div class="col-auto">
                <select name="course_id" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="">All courses</option>
                    @foreach($courses as $course)
                        <option value="{{ $course->id }}" @selected(request('course_id') == $course->id)>{{ $course->code }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-auto">
                <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="">All statuses</option>
                    @foreach(['scheduled','active','closed','expired'] as $status)
                        <option value="{{ $status }}" @selected(request('status') === $status)>{{ ucfirst($status) }}</option>
                    @endforeach
                </select>
            </div>
        </form>
        <a href="{{ route('sessions.create') }}" class="btn btn-primary btn-sm btn-pill"><i class="bi bi-plus-lg"></i> New Session</a>
    </div>

    <div class="card sa-card">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead>
                    <tr>
                        <th>Course</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Expires</th>
                        <th>Status</th>
                        <th>Present</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($sessions as $session)
                        <tr>
                            <td class="fw-semibold">{{ $session->course->code }}</td>
                            <td>{{ $session->session_date->format('M d, Y') }}</td>
                            <td>{{ substr($session->start_time, 0, 5) }} - {{ substr($session->end_time, 0, 5) }}</td>
                            <td>{{ $session->expires_at->format('H:i') }}</td>
                            <td><span class="badge-pill {{ $session->status === 'active' ? 'badge-active' : 'badge-closed' }}">{{ ucfirst($session->status) }}</span></td>
                            <td>{{ $session->attendanceRecords->count() }}</td>
                            <td class="text-end"><a href="{{ route('sessions.show', $session) }}" class="btn btn-sm btn-outline-primary btn-pill">Open</a></td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-muted p-4">No sessions found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($sessions->hasPages())
            <div class="card-footer bg-white">{{ $sessions->links() }}</div>
        @endif
    </div>
@endif
@endsection
