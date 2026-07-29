@extends('layouts.app')

@section('title', 'Assign Students')
@section('page_title', 'Assign Students')
@section('page_subtitle', $course->code.' — '.$course->title)

@section('content')
<div class="row g-4">
    <div class="col-lg-5">
        <div class="card sa-card">
            <div class="card-header bg-white border-0 pt-3">
                <h5 class="mb-0 fw-bold">Add Students</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('courses.students.assign', $course) }}">
                    @csrf
                    <div class="mb-3" style="max-height: 360px; overflow:auto;">
                        @forelse($availableStudents as $student)
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" name="student_ids[]" value="{{ $student->id }}" id="stu{{ $student->id }}">
                                <label class="form-check-label" for="stu{{ $student->id }}">
                                    {{ $student->name }} <span class="text-muted">({{ $student->matric_number }})</span>
                                </label>
                            </div>
                        @empty
                            <p class="text-muted mb-0">All students are already enrolled.</p>
                        @endforelse
                    </div>
                    @if($availableStudents->isNotEmpty())
                        <button class="btn btn-primary btn-pill" type="submit">Assign Selected</button>
                    @endif
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-7">
        <div class="card sa-card">
            <div class="card-header bg-white border-0 pt-3">
                <h5 class="mb-0 fw-bold">Currently Enrolled ({{ $course->students->count() }})</h5>
            </div>
            <div class="table-responsive">
                <table class="table mb-0 align-middle">
                    <thead>
                        <tr>
                            <th>Matric</th>
                            <th>Name</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($course->students as $student)
                            <tr>
                                <td>{{ $student->matric_number }}</td>
                                <td>{{ $student->name }}</td>
                                <td class="text-end">
                                    <form method="POST" action="{{ route('courses.students.remove', [$course, $student]) }}" onsubmit="return confirm('Remove this student?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger btn-pill" type="submit">Remove</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="text-muted">No enrolled students.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
