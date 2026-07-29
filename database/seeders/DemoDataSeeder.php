<?php

namespace Database\Seeders;

use App\Models\AttendanceRecord;
use App\Models\AttendanceSession;
use App\Models\Course;
use App\Models\Role;
use App\Models\User;
use App\Services\AttendanceSessionService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        $lecturerRole = Role::query()->where('slug', Role::LECTURER)->firstOrFail();
        $studentRole = Role::query()->where('slug', Role::STUDENT)->firstOrFail();

        $lecturer = User::query()->updateOrCreate(
            ['email' => 'lecturer@demo.com'],
            [
                'role_id' => $lecturerRole->id,
                'name' => 'Dr. Ada Okonkwo',
                'password' => Hash::make('password'),
                'department' => 'Computer Science',
            ]
        );

        $students = collect([
            ['name' => 'Ibrahim Musa', 'email' => 'student1@demo.com', 'matric_number' => 'CS/2022/001', 'level' => '300'],
            ['name' => 'Aisha Bello', 'email' => 'student2@demo.com', 'matric_number' => 'CS/2022/002', 'level' => '300'],
            ['name' => 'Chinedu Okafor', 'email' => 'student3@demo.com', 'matric_number' => 'CS/2022/003', 'level' => '200'],
            ['name' => 'Fatima Yusuf', 'email' => 'student4@demo.com', 'matric_number' => 'CS/2021/014', 'level' => '400'],
            ['name' => 'Tunde Adeyemi', 'email' => 'student5@demo.com', 'matric_number' => 'CS/2023/021', 'level' => '200'],
        ])->map(function (array $data) use ($studentRole) {
            return User::query()->updateOrCreate(
                ['email' => $data['email']],
                [
                    'role_id' => $studentRole->id,
                    'name' => $data['name'],
                    'matric_number' => $data['matric_number'],
                    'department' => 'Computer Science',
                    'level' => $data['level'],
                    'password' => Hash::make('password'),
                ]
            );
        });

        $courseA = Course::query()->updateOrCreate(
            [
                'code' => 'CSC301',
                'academic_session' => '2025/2026',
                'semester' => 'First Semester',
            ],
            [
                'lecturer_id' => $lecturer->id,
                'title' => 'Software Engineering',
                'description' => 'Principles of software design, architecture, and quality assurance.',
            ]
        );

        $courseB = Course::query()->updateOrCreate(
            [
                'code' => 'CSC305',
                'academic_session' => '2025/2026',
                'semester' => 'First Semester',
            ],
            [
                'lecturer_id' => $lecturer->id,
                'title' => 'Web Application Development',
                'description' => 'Building secure full-stack web applications.',
            ]
        );

        $courseA->students()->syncWithoutDetaching(
            $students->take(4)->mapWithKeys(fn ($s) => [$s->id => ['registered_at' => now()]])->all()
        );

        $courseB->students()->syncWithoutDetaching(
            $students->skip(1)->mapWithKeys(fn ($s) => [$s->id => ['registered_at' => now()]])->all()
        );

        /** @var AttendanceSessionService $sessionService */
        $sessionService = app(AttendanceSessionService::class);

        $sessionToday = AttendanceSession::query()->updateOrCreate(
            [
                'course_id' => $courseA->id,
                'session_date' => now()->toDateString(),
                'start_time' => '09:00:00',
            ],
            [
                'lecturer_id' => $lecturer->id,
                'title' => 'Week 4 — Requirements Engineering',
                'end_time' => '11:00:00',
                'expires_at' => now()->endOfDay(),
                'token' => $sessionService->generateToken(),
                'status' => 'active',
            ]
        );

        $pastSession = AttendanceSession::query()->updateOrCreate(
            [
                'course_id' => $courseA->id,
                'session_date' => now()->subDays(2)->toDateString(),
                'start_time' => '09:00:00',
            ],
            [
                'lecturer_id' => $lecturer->id,
                'title' => 'Week 3 — Process Models',
                'end_time' => '11:00:00',
                'expires_at' => now()->subDays(2)->setTime(11, 0),
                'token' => $sessionService->generateToken(),
                'status' => 'closed',
            ]
        );

        $webSession = AttendanceSession::query()->updateOrCreate(
            [
                'course_id' => $courseB->id,
                'session_date' => now()->subDay()->toDateString(),
                'start_time' => '13:00:00',
            ],
            [
                'lecturer_id' => $lecturer->id,
                'title' => 'Laravel MVC Lab',
                'end_time' => '15:00:00',
                'expires_at' => now()->subDay()->setTime(15, 0),
                'token' => $sessionService->generateToken(),
                'status' => 'closed',
            ]
        );

        foreach ($students->take(3) as $index => $student) {
            AttendanceRecord::query()->firstOrCreate(
                [
                    'student_id' => $student->id,
                    'attendance_session_id' => $pastSession->id,
                ],
                [
                    'course_id' => $courseA->id,
                    'checked_in_at' => now()->subDays(2)->setTime(9, 5 + $index),
                    'ip_address' => '127.0.0.1',
                    'device_info' => 'Demo Seeder',
                ]
            );
        }

        AttendanceRecord::query()->firstOrCreate(
            [
                'student_id' => $students[1]->id,
                'attendance_session_id' => $webSession->id,
            ],
            [
                'course_id' => $courseB->id,
                'checked_in_at' => now()->subDay()->setTime(13, 10),
                'ip_address' => '127.0.0.1',
                'device_info' => 'Demo Seeder',
            ]
        );

        AttendanceRecord::query()->firstOrCreate(
            [
                'student_id' => $students[0]->id,
                'attendance_session_id' => $sessionToday->id,
            ],
            [
                'course_id' => $courseA->id,
                'checked_in_at' => now()->setTime(9, 12),
                'ip_address' => '127.0.0.1',
                'device_info' => 'Demo Seeder',
            ]
        );
    }
}
