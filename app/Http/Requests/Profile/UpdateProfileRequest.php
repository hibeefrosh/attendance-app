<?php

namespace App\Http\Requests\Profile;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UpdateProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        $user = $this->user();

        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($user->id),
            ],
            'password' => ['nullable', 'confirmed', Password::defaults()],
        ];

        if ($user->isStudent()) {
            $rules['matric_number'] = [
                'required',
                'string',
                'max:50',
                Rule::unique('users', 'matric_number')->ignore($user->id),
            ];
            $rules['department'] = ['required', 'string', 'max:255'];
            $rules['level'] = ['required', 'string', 'max:50'];
        }

        return $rules;
    }
}
