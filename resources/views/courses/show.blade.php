@extends('layouts.app')

@section('title', $course->code)
@section('page_title', $course->code)
@section('page_subtitle', $course->title)

@section('content')
@if(auth()->user()->isStudent())
    <a href="{{ route('courses.index') }}" class="auth-back mb-3"><i class="bi bi-arrow-left"></i></a>
    <h1 class="h4 fw-bold mb-1">{{ $course->code }}</h1>
    <p class="text-muted mb-3">{{ $course->title }}</p>
    <div class="sa-card p-3 mb-3">
        <div class="mb-2"><span class="text-muted">Semester</span><div class="fw-semibold">{{ $course->semester }}</div></div>
        <div class="mb-2"><span class="text-muted">Session</span><div class="fw-semibold">{{ $course->academic_session }}</div></div>
        <div><span class="text-muted">Lecturer</span><div class="fw-semibold">{{ $course->lecturer->name }}</div></div>
    </div>
    @if($course->description)
        <p class="text-muted">{{ $course->description }}</p>
    @endif
@else
    <div class="d-flex flex-wrap gap-2 justify-content-between align-items-center mb-3">
        <div>
            <span class="badge-pill badge-upcoming">{{ $course->semester }}</span>
            <span class="badge-pill badge-closed ms-1">{{ $course->academic_session }}</span>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('courses.students', $course) }}" class="btn btn-outline-primary btn-sm btn-pill">Assign Students</a>
            <a href="{{ route('courses.edit', $course) }}" class="btn btn-outline-secondary btn-sm btn-pill">Edit</a>
            <form method="POST" action="{{ route('courses.destroy', $course) }}" onsubmit="return confirm('Delete this course?')">
                @csrf @method('DELETE')
                <button class="btn btn-outline-danger btn-sm btn-pill" type="submit">Delete</button>
            </form>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-4">
            <div class="card sa-card">
                <div class="card-body">
                    <h5 class="mb-3 fw-bold">Overview</h5>
                    <p class="mb-2"><strong>Lecturer:</strong> {{ $course->lecturer->name }}</p>
                    <p class="mb-2"><strong>Students:</strong> {{ $course->students_count }}</p>
                    <p class="mb-2"><strong>Sessions:</strong> {{ $course->attendance_sessions_count }}</p>
                    <p class="mb-0"><strong>Records:</strong> {{ $course->attendance_records_count }}</p>
                </div>
            </div>
        </div>
        <div class="col-lg-8">
            <div class="card sa-card">
                <div class="card-header bg-white border-0 pt-3">
                    <h5 class="mb-0 fw-bold">Enrolled Students</h5>
                </div>
                <div class="table-responsive">
                    <table class="table mb-0 align-middle">
                        <thead>
                            <tr>
                                <th>Matric</th>
                                <th>Name</th>
                                <th>Department</th>
                                <th>Level</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($course->students as $student)
                                <tr>
                                    <td>{{ $student->matric_number }}</td>
                                    <td>{{ $student->name }}</td>
                                    <td>{{ $student->department }}</td>
                                    <td>{{ $student->level }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="text-muted">No students enrolled.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endif
@endsection
