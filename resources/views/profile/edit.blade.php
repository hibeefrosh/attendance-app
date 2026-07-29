@extends('layouts.app')

@section('title', 'Profile')
@section('page_title', 'Settings')
@section('page_subtitle', 'Update your account details')

@section('content')
@if(auth()->user()->isStudent())
    <h1 class="h4 fw-bold mb-3">Profile</h1>
@endif

<div class="{{ auth()->user()->isStudent() ? '' : 'row justify-content-center' }}">
    <div class="{{ auth()->user()->isStudent() ? '' : 'col-lg-7' }}">
        <div class="card sa-card">
            <div class="card-body p-4">
                <div class="text-center mb-4">
                    <div class="avatar avatar-lg mx-auto mb-2" style="width:72px;height:72px;font-size:1.5rem">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                    <div class="fw-bold">{{ $user->name }}</div>
                    <div class="text-muted small">{{ $user->isStudent() ? 'Student' : 'Lecturer' }}</div>
                </div>

                <form method="POST" action="{{ route('profile.update') }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Full Name</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" class="form-control @error('name') is-invalid @enderror" required>
                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Email</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" class="form-control @error('email') is-invalid @enderror" required>
                        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    @if($user->isStudent())
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Matric Number</label>
                            <input type="text" name="matric_number" value="{{ old('matric_number', $user->matric_number) }}" class="form-control @error('matric_number') is-invalid @enderror" required>
                            @error('matric_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="row">
                            <div class="col-6 mb-3">
                                <label class="form-label fw-semibold">Department</label>
                                <input type="text" name="department" value="{{ old('department', $user->department) }}" class="form-control @error('department') is-invalid @enderror" required>
                                @error('department') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-6 mb-3">
                                <label class="form-label fw-semibold">Level</label>
                                <select name="level" class="form-select @error('level') is-invalid @enderror" required>
                                    @foreach(['100','200','300','400','500'] as $level)
                                        <option value="{{ $level }}" @selected(old('level', $user->level) === $level)>{{ $level }}</option>
                                    @endforeach
                                </select>
                                @error('level') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>
                    @endif

                    <hr class="my-4">
                    <p class="text-muted small">Leave password blank to keep your current password.</p>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">New Password</label>
                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror">
                        @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Confirm Password</label>
                        <input type="password" name="password_confirmation" class="form-control">
                    </div>

                    <button class="btn btn-brand w-100 mb-3" type="submit">Save Changes</button>
                </form>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="btn btn-outline-danger w-100 btn-pill" type="submit">Logout</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
