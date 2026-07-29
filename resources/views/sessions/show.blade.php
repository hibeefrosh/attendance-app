@extends('layouts.app')

@section('title', 'Session Details')
@section('page_title', $session->displayName())
@section('page_subtitle', $session->course->code.' — '.$session->course->title)

@section('content')
@if(auth()->user()->isStudent())
    <a href="{{ route('sessions.index') }}" class="auth-back mb-3"><i class="bi bi-arrow-left"></i></a>
    <h1 class="h4 fw-bold">{{ $session->course->code }}</h1>
    <p class="text-muted">{{ $session->session_date->format('M d, Y') }} · {{ substr($session->start_time, 0, 5) }}–{{ substr($session->end_time, 0, 5) }}</p>
    <span class="badge-pill {{ $session->status === 'active' ? 'badge-active' : 'badge-closed' }}">{{ ucfirst($session->status) }}</span>
@else
    <div class="d-flex flex-wrap gap-2 justify-content-between align-items-center mb-3">
        <div>
            <span class="badge-pill {{ $session->status === 'active' ? 'badge-active' : 'badge-closed' }}">{{ ucfirst($session->status) }}</span>
            <span class="text-muted ms-2">Expires {{ $session->expires_at->format('M d, Y H:i') }}</span>
        </div>
        <div class="d-flex flex-wrap gap-2">
            @if($session->status !== 'active' && ! $session->isExpired())
                <form method="POST" action="{{ route('sessions.activate', $session) }}">@csrf
                    <button class="btn btn-success btn-sm btn-pill" type="submit">Activate</button>
                </form>
            @endif
            @if($session->status === 'active')
                <a href="{{ route('sessions.qr', $session) }}" class="btn btn-primary btn-sm btn-pill" target="_blank">Display QR</a>
                <form method="POST" action="{{ route('sessions.close', $session) }}">@csrf
                    <button class="btn btn-warning btn-sm btn-pill" type="submit">Close Session</button>
                </form>
            @endif
            <a href="{{ route('sessions.attendance', $session) }}" class="btn btn-outline-primary btn-sm btn-pill">Attendance List</a>
            <a href="{{ route('reports.session.print', $session) }}" class="btn btn-outline-secondary btn-sm btn-pill" target="_blank">Print / PDF</a>
            <form method="POST" action="{{ route('sessions.destroy', $session) }}" onsubmit="return confirm('Delete this session?')">
                @csrf @method('DELETE')
                <button class="btn btn-outline-danger btn-sm btn-pill" type="submit">Delete</button>
            </form>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-5">
            <div class="card sa-card mb-4">
                <div class="card-body">
                    <h5 class="mb-3 fw-bold">Session Info</h5>
                    <p class="mb-2"><strong>Date:</strong> {{ $session->session_date->format('l, M d, Y') }}</p>
                    <p class="mb-2"><strong>Time:</strong> {{ substr($session->start_time, 0, 5) }} — {{ substr($session->end_time, 0, 5) }}</p>
                    <p class="mb-2"><strong>Present:</strong> {{ $session->attendanceRecords->count() }}</p>
                    <p class="mb-0 text-muted small">QR encodes a secure token only — never a database ID.</p>
                </div>
            </div>

            @if($qrSvg)
                <div class="card sa-card text-center">
                    <div class="card-body">
                        <h5 class="mb-3 fw-bold">Live QR Code</h5>
                        <div class="qr-frame">{!! $qrSvg !!}</div>
                        <p class="text-muted small mt-3 mb-0">Students scan this with the mobile app camera.</p>
                    </div>
                </div>
            @endif
        </div>
        <div class="col-lg-7">
            <div class="card sa-card">
                <div class="card-header bg-white border-0 pt-3">
                    <h5 class="mb-0 fw-bold">Checked-in Students</h5>
                </div>
                <div class="table-responsive">
                    <table class="table mb-0 align-middle">
                        <thead>
                            <tr>
                                <th>Matric</th>
                                <th>Name</th>
                                <th>Time</th>
                                <th>IP</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($session->attendanceRecords as $record)
                                <tr>
                                    <td>{{ $record->student->matric_number }}</td>
                                    <td>{{ $record->student->name }}</td>
                                    <td>{{ $record->checked_in_at->format('H:i:s') }}</td>
                                    <td>{{ $record->ip_address }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="text-muted">No check-ins yet.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endif
@endsection
