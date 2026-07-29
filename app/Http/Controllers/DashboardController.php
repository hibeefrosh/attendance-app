<?php

namespace App\Http\Controllers;

use App\Models\AttendanceRecord;
use App\Models\AttendanceSession;
use App\Models\Course;
use App\Services\AttendanceService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __construct(private AttendanceService $attendanceService) {}

    public function index(Request $request): View
    {
        $user = $request->user();

        if ($user->isLecturer()) {
            return $this->lecturerDashboard($user);
        }

        return $this->studentDashboard($user);
    }

    private function lecturerDashboard($user): View
    {
        $courseIds = Course::query()->where('lecturer_id', $user->id)->pluck('id');

        $uniqueStudents = DB::table('course_registrations')
            ->whereIn('course_id', $courseIds)
            ->distinct('student_id')
            ->count('student_id');

        $todaySessions = AttendanceSession::query()
            ->ownedBy($user)
            ->today()
            ->with(['course', 'attendanceRecords'])
            ->orderBy('start_time')
            ->get();

        $recentAttendance = AttendanceRecord::query()
            ->whereIn('course_id', $courseIds)
            ->with(['student', 'course', 'attendanceSession'])
            ->latest('checked_in_at')
            ->limit(6)
            ->get();

        $courses = Course::query()
            ->where('lecturer_id', $user->id)
            ->withCount(['students', 'attendanceSessions', 'attendanceRecords'])
            ->get();

        $avgPercentage = $courses->isEmpty()
            ? 0
            : round($courses->avg(fn ($c) => $this->attendanceService->courseAttendancePercentage($c->id)), 0);

        // Present vs expected for donut
        $heldSessions = AttendanceSession::query()
            ->whereIn('course_id', $courseIds)
            ->whereIn('status', ['active', 'closed', 'expired'])
            ->count();

        $enrolledTotal = (int) DB::table('course_registrations')
            ->whereIn('course_id', $courseIds)
            ->count();

        $expected = max($heldSessions * max($uniqueStudents, 1), 1);
        $presentCount = AttendanceRecord::query()->whereIn('course_id', $courseIds)->count();
        $absentCount = max($expected - $presentCount, 0);

        // 7-day attendance trend
        $trendLabels = [];
        $trendData = [];
        for ($i = 6; $i >= 0; $i--) {
            $day = Carbon::today()->subDays($i);
            $trendLabels[] = $day->format('M j');

            $daySessions = AttendanceSession::query()
                ->whereIn('course_id', $courseIds)
                ->whereDate('session_date', $day)
                ->pluck('id');

            if ($daySessions->isEmpty()) {
                $trendData[] = 0;
                continue;
            }

            $dayExpected = $daySessions->count() * max($uniqueStudents, 1);
            $dayPresent = AttendanceRecord::query()
                ->whereIn('attendance_session_id', $daySessions)
                ->count();

            $trendData[] = $dayExpected > 0
                ? round(($dayPresent / $dayExpected) * 100)
                : 0;
        }

        return view('dashboard.lecturer', [
            'stats' => [
                'students' => $uniqueStudents,
                'courses' => $courses->count(),
                'today_sessions' => $todaySessions->count(),
                'attendance_percentage' => $avgPercentage,
            ],
            'todaySessions' => $todaySessions,
            'recentAttendance' => $recentAttendance,
            'donut' => [
                'present' => $presentCount,
                'absent' => $absentCount,
                'average' => $avgPercentage,
            ],
            'trendLabels' => $trendLabels,
            'trendData' => $trendData,
            'firstCourse' => $courses->first(),
        ]);
    }

    private function studentDashboard($user): View
    {
        $courses = $user->registeredCourses()
            ->withCount(['attendanceSessions', 'attendanceRecords'])
            ->get();

        $todaySessions = AttendanceSession::query()
            ->whereIn('course_id', $courses->pluck('id'))
            ->today()
            ->whereIn('status', ['active', 'scheduled'])
            ->with('course')
            ->get();

        $percentage = $this->attendanceService->studentAttendancePercentage($user);
        $standing = $percentage >= 70 ? 'Good Standing' : ($percentage >= 50 ? 'Needs Improvement' : 'At Risk');

        return view('dashboard.student', [
            'courses' => $courses,
            'todaySessions' => $todaySessions,
            'percentage' => $percentage,
            'standing' => $standing,
        ]);
    }
}
