@extends('layouts.app')

@section('title', 'Session Attendance')
@section('page_title', 'Attendance List')
@section('page_subtitle', $session->course->code.' — '.$session->session_date->format('M d, Y'))

@section('content')
<div class="mb-3 no-print">
    <a href="{{ route('reports.session.print', $session) }}" class="btn btn-outline-secondary btn-sm btn-pill" target="_blank">Print / PDF View</a>
    @if($session->isActive())
        <a href="{{ route('sessions.qr', $session) }}" class="btn btn-outline-primary btn-sm btn-pill">Display QR</a>
    @endif
    <a href="{{ route('sessions.show', $session) }}" class="btn btn-outline-primary btn-sm btn-pill">Back</a>
</div>

<div class="alert alert-light border mb-3">
    <div class="fw-semibold mb-1">How attendance works</div>
    <ul class="mb-0 small text-muted">
        <li><strong>QR scan:</strong> students scan the session QR from their phone (fast method).</li>
        <li><strong>Manual mark:</strong> lecturer marks a student Present here if they could not scan.</li>
    </ul>
</div>

<div class="card sa-card">
    <div class="table-responsive">
        <table class="table mb-0 align-middle">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Matric</th>
                    <th>Name</th>
                    <th>Level</th>
                    <th>Status</th>
                    <th>Check-in</th>
                    <th class="no-print">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($enrolled as $i => $student)
                    @php $present = $presentIds->contains($student->id); @endphp
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>{{ $student->matric_number }}</td>
                        <td>{{ $student->name }}</td>
                        <td>{{ $student->level ?: '—' }}</td>
                        <td>
                            <span class="badge-pill {{ $present ? 'badge-present' : 'badge-absent' }}">
                                {{ $present ? 'Present' : 'Absent' }}
                            </span>
                        </td>
                        <td>
                            @if($present)
                                {{ $session->attendanceRecords->firstWhere('student_id', $student->id)?->checked_in_at?->format('H:i:s') }}
                            @else
                                —
                            @endif
                        </td>
                        <td class="no-print">
                            @if(! $present && in_array($session->status, ['active', 'closed'], true))
                                <form method="POST" action="{{ route('sessions.attendance.manual', [$session, $student]) }}"
                                      onsubmit="return confirm('Mark {{ $student->name }} as present?')">
                                    @csrf
                                    <button class="btn btn-sm btn-outline-success btn-pill" type="submit">Mark Present</button>
                                </form>
                            @elseif($present)
                                <span class="text-muted small">Recorded</span>
                            @else
                                <span class="text-muted small">—</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
