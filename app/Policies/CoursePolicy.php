<?php

namespace App\Policies;

use App\Models\Course;
use App\Models\User;

class CoursePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isLecturer() || $user->isStudent();
    }

    public function view(User $user, Course $course): bool
    {
        if ($user->isLecturer()) {
            return $course->isOwnedBy($user);
        }

        return $user->isStudent() && $course->hasStudent($user);
    }

    public function create(User $user): bool
    {
        return $user->isLecturer();
    }

    public function update(User $user, Course $course): bool
    {
        return $user->isLecturer() && $course->isOwnedBy($user);
    }

    public function delete(User $user, Course $course): bool
    {
        return $user->isLecturer() && $course->isOwnedBy($user);
    }

    public function assignStudents(User $user, Course $course): bool
    {
        return $user->isLecturer() && $course->isOwnedBy($user);
    }
}
