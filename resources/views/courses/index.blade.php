@extends('layouts.app')

@section('title', 'Courses')
@section('page_title', 'Courses')
@section('page_subtitle', auth()->user()->isLecturer() ? 'Manage your courses' : 'Your enrolled courses')

@section('content')
@if(auth()->user()->isStudent())
    <h1 class="h4 fw-bold mb-3">Courses</h1>
    @forelse($courses as $course)
        <a href="{{ route('courses.show', $course) }}" class="action-tile">
            <div class="tile-icon" style="background:linear-gradient(135deg,#6a63ef,#4C44CF);font-size:1.1rem">
                <i class="bi bi-journal-text"></i>
            </div>
            <div>
                <div class="fw-bold">{{ $course->code }}</div>
                <div class="text-muted small">{{ $course->title }}</div>
                <div class="text-muted small">{{ $course->semester }} · {{ $course->academic_session }}</div>
            </div>
            <i class="bi bi-chevron-right chev"></i>
        </a>
    @empty
        <div class="text-center text-muted py-5">You are not enrolled in any courses yet.</div>
    @endforelse
    @if($courses->hasPages())
        <div class="mt-3">{{ $courses->links() }}</div>
    @endif
@else
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0 fw-bold">Course List</h5>
        <a href="{{ route('courses.create') }}" class="btn btn-primary btn-pill"><i class="bi bi-plus-lg"></i> Create Course</a>
    </div>

    <div class="card sa-card">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead>
                    <tr>
                        <th>Code</th>
                        <th>Title</th>
                        <th>Semester</th>
                        <th>Session</th>
                        <th>Students</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($courses as $course)
                        <tr>
                            <td class="fw-semibold">{{ $course->code }}</td>
                            <td>{{ $course->title }}</td>
                            <td>{{ $course->semester }}</td>
                            <td>{{ $course->academic_session }}</td>
                            <td>{{ $course->students_count ?? 0 }}</td>
                            <td class="text-end">
                                <a href="{{ route('courses.show', $course) }}" class="btn btn-sm btn-outline-primary btn-pill">View</a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-muted p-4">No courses found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($courses->hasPages())
            <div class="card-footer bg-white">{{ $courses->links() }}</div>
        @endif
    </div>
@endif
@endsection
