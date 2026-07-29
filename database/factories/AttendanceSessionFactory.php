<?php

namespace Database\Factories;

use App\Models\AttendanceSession;
use App\Models\Course;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<AttendanceSession>
 */
class AttendanceSessionFactory extends Factory
{
    public function definition(): array
    {
        $course = Course::query()->inRandomOrder()->first() ?? Course::factory()->create();
        $date = fake()->dateTimeBetween('-1 week', '+1 week');

        return [
            'course_id' => $course->id,
            'lecturer_id' => $course->lecturer_id,
            'title' => 'Lecture '.fake()->numberBetween(1, 12),
            'session_date' => $date->format('Y-m-d'),
            'start_time' => '09:00:00',
            'end_time' => '11:00:00',
            'expires_at' => (clone $date)->setTime(11, 0),
            'token' => hash('sha256', Str::uuid()->toString().Str::random(16)),
            'status' => fake()->randomElement(['scheduled', 'active', 'closed']),
        ];
    }
}
