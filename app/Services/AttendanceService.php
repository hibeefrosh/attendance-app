<?php

namespace App\Services;

use App\Mail\AttendanceSessionActivatedMail;
use App\Models\AttendanceRecord;
use App\Models\AttendanceSession;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;

class AttendanceService
{
    /**
     * Validate token and mark attendance for a student.
     *
     * @return array{success: bool, message: string, record?: AttendanceRecord}
     */
    public function markAttendance(string $token, User $student, Request $request): array
    {
        if (! $student->isStudent()) {
            throw ValidationException::withMessages([
                'token' => 'Only students can mark attendance.',
            ]);
        }

        $session = AttendanceSession::query()
            ->with('course')
            ->where('token', $token)
            ->first();

        if (! $session) {
            return $this->fail('Invalid QR code. Session not found.');
        }

        $session->markExpiredIfNeeded();
        $session->refresh();

        if ($session->status === 'closed') {
            return $this->fail('This attendance session has been closed.');
        }

        if ($session->isExpired()) {
            return $this->fail('This QR code has expired.');
        }

        if ($session->status !== 'active') {
            return $this->fail('This attendance session is not active yet.');
        }

        if (! $session->course->hasStudent($student)) {
            return $this->fail('You are not enrolled in this course.');
        }

        if ($this->alreadyMarked($student, $session)) {
            return $this->fail('Attendance already recorded for this session.');
        }

        try {
            $record = DB::transaction(function () use ($session, $student, $request) {
                // Re-check inside transaction to reduce race conditions
                if ($this->alreadyMarked($student, $session)) {
                    throw ValidationException::withMessages([
                        'token' => 'Attendance already recorded for this session.',
                    ]);
                }

                return AttendanceRecord::create([
                    'student_id' => $student->id,
                    'course_id' => $session->course_id,
                    'attendance_session_id' => $session->id,
                    'checked_in_at' => now(),
                    'ip_address' => $request->ip(),
                    'device_info' => substr((string) $request->userAgent(), 0, 500),
                ]);
            });
        } catch (ValidationException $e) {
            return $this->fail(collect($e->errors())->flatten()->first() ?: 'Unable to mark attendance.');
        } catch (QueryException $e) {
            // Unique constraint violation (duplicate attendance)
            if ($this->isUniqueViolation($e)) {
                return $this->fail('Attendance already recorded for this session.');
            }

            throw $e;
        }

        $record->loadMissing(['course', 'attendanceSession']);

        if (filled($student->email)) {
            try {
                // Send immediately after a successful scan; do not queue.
                Mail::to($student->email)->send(
                    new AttendanceSessionActivatedMail($record->attendanceSession, $student)
                );
            } catch (\Throwable $e) {
                Log::warning('Attendance email failed to send after scan.', [
                    'student_id' => $student->id,
                    'record_id' => $record->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        return [
            'success' => true,
            'message' => 'Attendance marked successfully.',
            'record' => $record,
        ];
    }

    public function alreadyMarked(User $student, AttendanceSession $session): bool
    {
        return AttendanceRecord::query()
            ->where('student_id', $student->id)
            ->where('attendance_session_id', $session->id)
            ->exists();
    }

    /**
     * Attendance percentage for a student across enrolled courses (or one course).
     */
    public function studentAttendancePercentage(User $student, ?int $courseId = null): float
    {
        $courseIds = $courseId
            ? collect([$courseId])
            : $student->registeredCourses()->pluck('courses.id');

        if ($courseIds->isEmpty()) {
            return 0.0;
        }

        $totalSessions = AttendanceSession::query()
            ->whereIn('course_id', $courseIds)
            ->whereIn('status', ['active', 'closed', 'expired'])
            ->count();

        if ($totalSessions === 0) {
            return 0.0;
        }

        $attended = AttendanceRecord::query()
            ->where('student_id', $student->id)
            ->whereIn('course_id', $courseIds)
            ->count();

        return round(($attended / $totalSessions) * 100, 1);
    }

    /**
     * Attendance percentage for a course (all enrolled students vs sessions held).
     */
    public function courseAttendancePercentage(int $courseId): float
    {
        $enrolled = DB::table('course_registrations')->where('course_id', $courseId)->count();
        $sessions = AttendanceSession::query()
            ->where('course_id', $courseId)
            ->whereIn('status', ['active', 'closed', 'expired'])
            ->count();

        $expected = $enrolled * $sessions;

        if ($expected === 0) {
            return 0.0;
        }

        $actual = AttendanceRecord::query()->where('course_id', $courseId)->count();

        return round(($actual / $expected) * 100, 1);
    }

    private function fail(string $message): array
    {
        return [
            'success' => false,
            'message' => $message,
        ];
    }

    private function isUniqueViolation(QueryException $e): bool
    {
        // MySQL duplicate entry error code
        return (string) $e->getCode() === '23000'
            || str_contains(strtolower($e->getMessage()), 'unique')
            || str_contains(strtolower($e->getMessage()), 'duplicate');
    }
}
