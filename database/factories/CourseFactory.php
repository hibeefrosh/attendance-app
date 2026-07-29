<?php

namespace Database\Factories;

use App\Models\Course;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Course>
 */
class CourseFactory extends Factory
{
    public function definition(): array
    {
        $lecturerId = User::query()
            ->whereHas('role', fn ($q) => $q->where('slug', Role::LECTURER))
            ->value('id');

        return [
            'lecturer_id' => $lecturerId ?? User::factory()->lecturer(),
            'code' => strtoupper(fake()->unique()->bothify('CSC###')),
            'title' => fake()->sentence(3),
            'semester' => fake()->randomElement(['First Semester', 'Second Semester']),
            'academic_session' => '2025/2026',
            'description' => fake()->optional()->paragraph(),
        ];
    }
}
