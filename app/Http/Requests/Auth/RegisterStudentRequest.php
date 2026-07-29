<?php

namespace App\Http\Requests\Auth;

use App\Models\Role;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class RegisterStudentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'matric_number' => ['required', 'string', 'max:50', 'unique:users,matric_number'],
            'department' => ['required', 'string', 'max:255'],
            'level' => ['required', 'string', 'max:50'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ];
    }

    public function messages(): array
    {
        return [
            'matric_number.unique' => 'This matric number is already registered.',
            'email.unique' => 'This email is already registered.',
        ];
    }

    public function studentRoleId(): int
    {
        return Role::query()->where('slug', Role::STUDENT)->value('id');
    }
}
