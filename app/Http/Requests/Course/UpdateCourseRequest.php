<?php

namespace App\Http\Requests\Course;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCourseRequest extends FormRequest
{
    public function authorize(): bool
    {
        $course = $this->route('course');

        return $this->user()?->isLecturer()
            && $course
            && $course->isOwnedBy($this->user());
    }

    public function rules(): array
    {
        $courseId = $this->route('course')?->id;

        return [
            'code' => [
                'required',
                'string',
                'max:50',
                Rule::unique('courses')->where(fn ($q) => $q
                    ->where('academic_session', $this->input('academic_session'))
                    ->where('semester', $this->input('semester'))
                    ->whereNull('deleted_at'))
                    ->ignore($courseId),
            ],
            'title' => ['required', 'string', 'max:255'],
            'semester' => ['required', 'string', 'max:50'],
            'academic_session' => ['required', 'string', 'max:50'],
            'description' => ['nullable', 'string', 'max:2000'],
        ];
    }
}
