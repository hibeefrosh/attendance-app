@extends('layouts.app')

@section('title', 'Reports')
@section('page_title', 'Attendance Reports')
@section('page_subtitle', 'Filter and export attendance records')

@section('content')
<form method="GET" class="card sa-card mb-4">
    <div class="card-body">
        <div class="row g-3 align-items-end">
            <div class="col-md-3">
                <label class="form-label fw-semibold">Course</label>
                <select name="course_id" class="form-select">
                    <option value="">All courses</option>
                    @foreach($courses as $course)
                        <option value="{{ $course->id }}" @selected(request('course_id') == $course->id)>{{ $course->code }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold">Student</label>
                <select name="student_id" class="form-select">
                    <option value="">All students</option>
                    @foreach($students as $student)
                        <option value="{{ $student->id }}" @selected(request('student_id') == $student->id)>{{ $student->name }} ({{ $student->matric_number }})</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label fw-semibold">From</label>
                <input type="date" name="date_from" value="{{ request('date_from') }}" class="form-control">
            </div>
            <div class="col-md-2">
                <label class="form-label fw-semibold">To</label>
                <input type="date" name="date_to" value="{{ request('date_to') }}" class="form-control">
            </div>
            <div class="col-md-2">
                <button class="btn btn-primary btn-pill w-100" type="submit">Filter</button>
            </div>
        </div>
        <div class="mt-3">
            <a class="btn btn-outline-success btn-sm btn-pill" href="{{ route('reports.export', request()->query()) }}">
                <i class="bi bi-filetype-csv"></i> Export Excel/CSV
            </a>
        </div>
    </div>
</form>

<div class="card sa-card">
    <div class="table-responsive">
        <table class="table mb-0 align-middle">
            <thead>
                <tr>
                    <th>Matric</th>
                    <th>Student</th>
                    <th>Course</th>
                    <th>Session Date</th>
                    <th>Check-in</th>
                    <th>IP</th>
                </tr>
            </thead>
            <tbody>
                @forelse($records as $record)
                    <tr>
                        <td>{{ $record->student->matric_number }}</td>
                        <td>{{ $record->student->name }}</td>
                        <td>{{ $record->course->code }}</td>
                        <td>{{ $record->attendanceSession->session_date->format('M d, Y') }}</td>
                        <td>{{ $record->checked_in_at->format('Y-m-d H:i') }}</td>
                        <td>{{ $record->ip_address }}</td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-muted p-4">No records match your filters.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($records->hasPages())
        <div class="card-footer bg-white">{{ $records->links() }}</div>
    @endif
</div>
@endsection
