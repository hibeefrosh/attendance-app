<?php

namespace App\Policies;

use App\Models\AttendanceSession;
use App\Models\User;

class AttendanceSessionPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isLecturer() || $user->isStudent();
    }

    public function view(User $user, AttendanceSession $attendanceSession): bool
    {
        if ($user->isLecturer()) {
            return (int) $attendanceSession->lecturer_id === (int) $user->id;
        }

        return $user->isStudent()
            && $attendanceSession->course->hasStudent($user);
    }

    public function create(User $user): bool
    {
        return $user->isLecturer();
    }

    public function update(User $user, AttendanceSession $attendanceSession): bool
    {
        return $user->isLecturer()
            && (int) $attendanceSession->lecturer_id === (int) $user->id;
    }

    public function delete(User $user, AttendanceSession $attendanceSession): bool
    {
        return $this->update($user, $attendanceSession);
    }

    public function manageQr(User $user, AttendanceSession $attendanceSession): bool
    {
        return $this->update($user, $attendanceSession);
    }

    public function viewReport(User $user, AttendanceSession $attendanceSession): bool
    {
        return $this->update($user, $attendanceSession);
    }
}
