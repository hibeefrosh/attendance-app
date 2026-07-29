@extends('layouts.app')

@section('title', 'Create Course')
@section('page_title', 'Create Course')
@section('page_subtitle', 'Add a new course')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-7">
        <div class="card sa-card">
            <div class="card-body p-4">
                <form method="POST" action="{{ route('courses.store') }}">
                    @csrf
                    @include('courses._form')
                    <div class="d-flex gap-2">
                        <button class="btn btn-primary btn-pill" type="submit">Save Course</button>
                        <a href="{{ route('courses.index') }}" class="btn btn-outline-secondary btn-pill">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
