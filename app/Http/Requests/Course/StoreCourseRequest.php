<?php

namespace App\Http\Requests\Course;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCourseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isLecturer() ?? false;
    }

    public function rules(): array
    {
        return [
            'code' => [
                'required',
                'string',
                'max:50',
                Rule::unique('courses')->where(fn ($q) => $q
                    ->where('academic_session', $this->input('academic_session'))
                    ->where('semester', $this->input('semester'))
                    ->whereNull('deleted_at')),
            ],
            'title' => ['required', 'string', 'max:255'],
            'semester' => ['required', 'string', 'max:50'],
            'academic_session' => ['required', 'string', 'max:50'],
            'description' => ['nullable', 'string', 'max:2000'],
        ];
    }
}
