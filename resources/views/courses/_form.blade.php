@php $course = $course ?? null; @endphp

<div class="mb-3">
    <label class="form-label">Course Code</label>
    <input type="text" name="code" value="{{ old('code', $course->code ?? '') }}" class="form-control @error('code') is-invalid @enderror" required>
    @error('code') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>
<div class="mb-3">
    <label class="form-label">Course Title</label>
    <input type="text" name="title" value="{{ old('title', $course->title ?? '') }}" class="form-control @error('title') is-invalid @enderror" required>
    @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>
<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">Semester</label>
        <select name="semester" class="form-select @error('semester') is-invalid @enderror" required>
            @foreach(['First Semester','Second Semester'] as $sem)
                <option value="{{ $sem }}" @selected(old('semester', $course->semester ?? '') === $sem)>{{ $sem }}</option>
            @endforeach
        </select>
        @error('semester') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">Academic Session</label>
        <input type="text" name="academic_session" value="{{ old('academic_session', $course->academic_session ?? '2025/2026') }}" class="form-control @error('academic_session') is-invalid @enderror" placeholder="2025/2026" required>
        @error('academic_session') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
</div>
<div class="mb-4">
    <label class="form-label">Description (optional)</label>
    <textarea name="description" rows="3" class="form-control @error('description') is-invalid @enderror">{{ old('description', $course->description ?? '') }}</textarea>
    @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>
