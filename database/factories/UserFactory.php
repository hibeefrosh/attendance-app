<?php

namespace Database\Factories;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    protected static ?string $password;

    public function definition(): array
    {
        return [
            'role_id' => Role::query()->where('slug', Role::STUDENT)->value('id')
                ?? Role::query()->create(['name' => 'Student', 'slug' => Role::STUDENT])->id,
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'matric_number' => strtoupper(fake()->bothify('CS/####/###')),
            'department' => 'Computer Science',
            'level' => fake()->randomElement(['100', '200', '300', '400']),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
        ];
    }

    public function lecturer(): static
    {
        return $this->state(fn () => [
            'role_id' => Role::query()->where('slug', Role::LECTURER)->value('id'),
            'matric_number' => null,
            'level' => null,
        ]);
    }

    public function student(): static
    {
        return $this->state(fn () => [
            'role_id' => Role::query()->where('slug', Role::STUDENT)->value('id'),
        ]);
    }

    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
