@extends('layouts.app')

@section('title', 'Edit Course')
@section('page_title', 'Edit Course')
@section('page_subtitle', $course->code)

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-7">
        <div class="card sa-card">
            <div class="card-body p-4">
                <form method="POST" action="{{ route('courses.update', $course) }}">
                    @csrf
                    @method('PUT')
                    @include('courses._form')
                    <div class="d-flex gap-2">
                        <button class="btn btn-primary btn-pill" type="submit">Update Course</button>
                        <a href="{{ route('courses.show', $course) }}" class="btn btn-outline-secondary btn-pill">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
