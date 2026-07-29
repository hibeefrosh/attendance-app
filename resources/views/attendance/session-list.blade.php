@extends('layouts.app')

@section('title', 'Session Attendance')
@section('page_title', 'Attendance List')
@section('page_subtitle', $session->course->code.' — '.$session->session_date->format('M d, Y'))

@section('content')
<div class="mb-3 no-print">
    <a href="{{ route('reports.session.print', $session) }}" class="btn btn-outline-secondary btn-sm btn-pill" target="_blank">Print / PDF View</a>
    <a href="{{ route('sessions.show', $session) }}" class="btn btn-outline-primary btn-sm btn-pill">Back</a>
</div>

<div class="card sa-card">
    <div class="table-responsive">
        <table class="table mb-0 align-middle">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Matric</th>
                    <th>Name</th>
                    <th>Status</th>
                    <th>Check-in</th>
                </tr>
            </thead>
            <tbody>
                @foreach($enrolled as $i => $student)
                    @php $present = $presentIds->contains($student->id); @endphp
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>{{ $student->matric_number }}</td>
                        <td>{{ $student->name }}</td>
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
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
