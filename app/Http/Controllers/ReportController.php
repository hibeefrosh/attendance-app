<?php

namespace App\Http\Controllers;

use App\Models\AttendanceRecord;
use App\Models\AttendanceSession;
use App\Models\Course;
use App\Models\User;
use App\Services\AttendanceService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportController extends Controller
{
    public function __construct(private AttendanceService $attendanceService) {}

    public function index(Request $request): View
    {
        abort_unless($request->user()->isLecturer(), 403);

        $lecturer = $request->user();
        $courses = Course::query()
            ->where('lecturer_id', $lecturer->id)
            ->orderBy('code')
            ->get();

        $query = AttendanceRecord::query()
            ->whereIn('course_id', $courses->pluck('id'))
            ->with(['student', 'course', 'attendanceSession']);

        if ($request->filled('course_id')) {
            $query->where('course_id', $request->integer('course_id'));
        }

        if ($request->filled('student_id')) {
            $query->where('student_id', $request->integer('student_id'));
        }

        if ($request->filled('date_from')) {
            $query->whereDate('checked_in_at', '>=', $request->date('date_from'));
        }

        if ($request->filled('date_to')) {
            $query->whereDate('checked_in_at', '<=', $request->date('date_to'));
        }

        $records = $query->latest('checked_in_at')->paginate(20)->withQueryString();

        $students = User::query()
            ->whereIn('id', function ($q) use ($courses) {
                $q->select('student_id')
                    ->from('course_registrations')
                    ->whereIn('course_id', $courses->pluck('id'));
            })
            ->orderBy('name')
            ->get();

        return view('reports.index', compact('records', 'courses', 'students'));
    }

    public function exportCsv(Request $request): StreamedResponse
    {
        abort_unless($request->user()->isLecturer(), 403);

        $courseIds = Course::query()
            ->where('lecturer_id', $request->user()->id)
            ->pluck('id');

        $query = AttendanceRecord::query()
            ->whereIn('course_id', $courseIds)
            ->with(['student', 'course', 'attendanceSession']);

        if ($request->filled('course_id')) {
            $query->where('course_id', $request->integer('course_id'));
        }

        if ($request->filled('student_id')) {
            $query->where('student_id', $request->integer('student_id'));
        }

        if ($request->filled('date_from')) {
            $query->whereDate('checked_in_at', '>=', $request->date('date_from'));
        }

        if ($request->filled('date_to')) {
            $query->whereDate('checked_in_at', '<=', $request->date('date_to'));
        }

        $filename = 'attendance-report-'.now()->format('Ymd-His').'.csv';

        return response()->streamDownload(function () use ($query) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, [
                'Matric Number',
                'Student Name',
                'Course Code',
                'Course Title',
                'Session Date',
                'Check-in Time',
                'IP Address',
            ]);

            $query->orderBy('checked_in_at')->chunk(200, function ($rows) use ($handle) {
                foreach ($rows as $row) {
                    fputcsv($handle, [
                        $row->student->matric_number,
                        $row->student->name,
                        $row->course->code,
                        $row->course->title,
                        optional($row->attendanceSession->session_date)->format('Y-m-d'),
                        optional($row->checked_in_at)->format('Y-m-d H:i:s'),
                        $row->ip_address,
                    ]);
                }
            });

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv',
        ]);
    }

    public function sessionExport(AttendanceSession $session): View
    {
        $this->authorize('viewReport', $session);

        $session->load(['course', 'attendanceRecords.student']);
        $enrolled = $session->course->students()->orderBy('name')->get();
        $presentIds = $session->attendanceRecords->pluck('student_id');

        return view('reports.session-print', compact('session', 'enrolled', 'presentIds'));
    }
}
