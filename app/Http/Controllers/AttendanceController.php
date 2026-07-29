<?php

namespace App\Http\Controllers;

use App\Http\Requests\Attendance\MarkAttendanceRequest;
use App\Models\AttendanceRecord;
use App\Models\AttendanceSession;
use App\Services\AttendanceService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AttendanceController extends Controller
{
    public function __construct(private AttendanceService $attendanceService) {}

    public function scan(): View
    {
        abort_unless(auth()->user()->isStudent(), 403);

        return view('attendance.scan');
    }

    public function mark(MarkAttendanceRequest $request): JsonResponse
    {
        $result = $this->attendanceService->markAttendance(
            $request->validated('token'),
            $request->user(),
            $request
        );

        if ($result['success'] && isset($result['record'])) {
            $record = $result['record'];
            $result['redirect'] = route('attendance.success', $record);
            $result['details'] = [
                'course' => $record->course->title,
                'course_code' => $record->course->code,
                'date' => $record->attendanceSession->session_date->format('M d, Y'),
                'time' => $record->checked_in_at->format('g:i A'),
                'session' => $record->attendanceSession->title
                    ?: $record->attendanceSession->displayName(),
            ];
        }

        return response()->json($result, $result['success'] ? 200 : 422);
    }

    public function success(AttendanceRecord $record): View
    {
        abort_unless(auth()->user()->isStudent(), 403);
        abort_unless((int) $record->student_id === (int) auth()->id(), 403);

        $record->load(['course', 'attendanceSession']);

        return view('attendance.success', compact('record'));
    }

    public function history(Request $request): View
    {
        $user = $request->user();
        $courseId = $request->integer('course_id') ?: null;

        $courses = $user->registeredCourses()->orderBy('code')->get();
        $selectedCourse = $courseId
            ? $courses->firstWhere('id', $courseId)
            : $courses->first();

        $scopeCourseId = $selectedCourse?->id;

        $totalSessions = 0;
        $attended = 0;

        if ($scopeCourseId) {
            $totalSessions = AttendanceSession::query()
                ->where('course_id', $scopeCourseId)
                ->whereIn('status', ['active', 'closed', 'expired'])
                ->count();

            $attended = AttendanceRecord::query()
                ->where('student_id', $user->id)
                ->where('course_id', $scopeCourseId)
                ->count();
        }

        $percentage = $totalSessions > 0
            ? round(($attended / $totalSessions) * 100)
            : 0;

        // Build present/absent history for selected course sessions
        $sessions = collect();
        if ($scopeCourseId) {
            $sessions = AttendanceSession::query()
                ->where('course_id', $scopeCourseId)
                ->whereIn('status', ['active', 'closed', 'expired'])
                ->with(['attendanceRecords' => fn ($q) => $q->where('student_id', $user->id)])
                ->latest('session_date')
                ->paginate(15)
                ->withQueryString();
        }

        return view('attendance.history', [
            'courses' => $courses,
            'selectedCourse' => $selectedCourse,
            'sessions' => $sessions,
            'stats' => [
                'total' => $totalSessions,
                'attended' => $attended,
                'percentage' => $percentage,
            ],
        ]);
    }

    public function sessionList(AttendanceSession $session): View
    {
        $this->authorize('viewReport', $session);

        $session->load(['course', 'attendanceRecords.student']);

        $enrolled = $session->course->students()->orderBy('name')->get();
        $presentIds = $session->attendanceRecords->pluck('student_id');

        return view('attendance.session-list', [
            'session' => $session,
            'enrolled' => $enrolled,
            'presentIds' => $presentIds,
        ]);
    }
}
