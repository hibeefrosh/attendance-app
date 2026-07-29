<?php

namespace App\Services;

use App\Models\AttendanceSession;
use App\Models\Course;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class AttendanceSessionService
{
    /**
     * Create an attendance session with a secure random token.
     */
    public function create(Course $course, User $lecturer, array $data): AttendanceSession
    {
        $expiresAt = Carbon::parse($data['session_date'].' '.$data['end_time']);

        if (! empty($data['expires_at'])) {
            $expiresAt = Carbon::parse($data['expires_at']);
        }

        return AttendanceSession::create([
            'course_id' => $course->id,
            'lecturer_id' => $lecturer->id,
            'title' => $data['title'] ?? null,
            'session_date' => $data['session_date'],
            'start_time' => $data['start_time'],
            'end_time' => $data['end_time'],
            'expires_at' => $expiresAt,
            'token' => $this->generateToken(),
            'status' => $data['status'] ?? 'scheduled',
        ]);
    }

    public function activate(AttendanceSession $session): AttendanceSession
    {
        if ($session->status === 'active') {
            return $session->fresh();
        }

        $session->update(['status' => 'active']);

        return $session->fresh();
    }

    public function close(AttendanceSession $session): AttendanceSession
    {
        $session->update(['status' => 'closed']);

        return $session->fresh();
    }

    /**
     * Generate SVG QR code containing only the session token.
     */
    public function generateQrSvg(AttendanceSession $session, int $size = 280): string
    {
        return QrCode::format('svg')
            ->size($size)
            ->margin(1)
            ->generate($session->qrPayload());
    }

    public function generateToken(): string
    {
        return hash('sha256', Str::uuid()->toString().Str::random(32).microtime(true));
    }
}
