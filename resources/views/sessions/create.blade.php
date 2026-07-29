@extends('layouts.app')

@section('title', 'Create Session')
@section('page_title', 'Create Attendance Session')
@section('page_subtitle', 'Generate a secure QR attendance window')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-7">
        <div class="card sa-card">
            <div class="card-body p-4">
                <form method="POST" action="{{ route('sessions.store') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Course</label>
                        <select name="course_id" class="form-select @error('course_id') is-invalid @enderror" required>
                            <option value="">Select course</option>
                            @foreach($courses as $course)
                                <option value="{{ $course->id }}" @selected(old('course_id') == $course->id)>{{ $course->code }} — {{ $course->title }}</option>
                            @endforeach
                        </select>
                        @error('course_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Title (optional)</label>
                        <input type="text" name="title" value="{{ old('title') }}" class="form-control" placeholder="Morning Session">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Date</label>
                        <input type="date" name="session_date" value="{{ old('session_date', now()->toDateString()) }}" class="form-control @error('session_date') is-invalid @enderror" required>
                        @error('session_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Start Time</label>
                            <input type="time" name="start_time" value="{{ old('start_time') }}" class="form-control @error('start_time') is-invalid @enderror" required>
                            @error('start_time') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">End Time</label>
                            <input type="time" name="end_time" value="{{ old('end_time') }}" class="form-control @error('end_time') is-invalid @enderror" required>
                            @error('end_time') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Expiry Time (optional)</label>
                        <input type="datetime-local" name="expires_at" value="{{ old('expires_at') }}" class="form-control @error('expires_at') is-invalid @enderror">
                        <div class="form-text">Defaults to session end time if left empty.</div>
                        @error('expires_at') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="form-check mb-4">
                        <input class="form-check-input" type="checkbox" name="activate_now" value="1" id="activate_now" @checked(old('activate_now', true))>
                        <label class="form-check-label" for="activate_now">Activate now and enable QR</label>
                    </div>
                    <button class="btn btn-primary btn-pill" type="submit">Create Session</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
