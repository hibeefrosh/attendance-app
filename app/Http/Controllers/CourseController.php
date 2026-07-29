<?php

namespace App\Http\Controllers;

use App\Http\Requests\Course\AssignStudentsRequest;
use App\Http\Requests\Course\StoreCourseRequest;
use App\Http\Requests\Course\UpdateCourseRequest;
use App\Models\Course;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CourseController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();

        if ($user->isLecturer()) {
            $courses = Course::query()
                ->where('lecturer_id', $user->id)
                ->withCount(['students', 'attendanceSessions'])
                ->latest()
                ->paginate(10);
        } else {
            $courses = $user->registeredCourses()
                ->withCount(['attendanceSessions'])
                ->paginate(10);
        }

        return view('courses.index', compact('courses'));
    }

    public function create(): View
    {
        $this->authorize('create', Course::class);

        return view('courses.create');
    }

    public function store(StoreCourseRequest $request): RedirectResponse
    {
        $course = Course::create([
            ...$request->validated(),
            'lecturer_id' => $request->user()->id,
        ]);

        return redirect()
            ->route('courses.show', $course)
            ->with('success', 'Course created successfully.');
    }

    public function show(Course $course): View
    {
        $this->authorize('view', $course);

        $course->load(['lecturer', 'students']);
        $course->loadCount(['students', 'attendanceSessions', 'attendanceRecords']);

        return view('courses.show', compact('course'));
    }

    public function edit(Course $course): View
    {
        $this->authorize('update', $course);

        return view('courses.edit', compact('course'));
    }

    public function update(UpdateCourseRequest $request, Course $course): RedirectResponse
    {
        $course->update($request->validated());

        return redirect()
            ->route('courses.show', $course)
            ->with('success', 'Course updated successfully.');
    }

    public function destroy(Course $course): RedirectResponse
    {
        $this->authorize('delete', $course);
        $course->delete();

        return redirect()
            ->route('courses.index')
            ->with('success', 'Course deleted successfully.');
    }

    public function students(Course $course): View
    {
        $this->authorize('assignStudents', $course);

        $course->load('students');

        $enrolledIds = $course->students->pluck('id');

        $availableStudents = User::query()
            ->whereHas('role', fn ($q) => $q->where('slug', Role::STUDENT))
            ->when($enrolledIds->isNotEmpty(), fn ($q) => $q->whereNotIn('id', $enrolledIds))
            ->orderBy('name')
            ->get();

        return view('courses.students', compact('course', 'availableStudents'));
    }

    public function assignStudents(AssignStudentsRequest $request, Course $course): RedirectResponse
    {
        $studentIds = collect($request->validated('student_ids'))
            ->filter(fn ($id) => User::query()
                ->whereKey($id)
                ->whereHas('role', fn ($q) => $q->where('slug', Role::STUDENT))
                ->exists());

        $syncData = $studentIds->mapWithKeys(fn ($id) => [
            $id => ['registered_at' => now()],
        ])->all();

        $course->students()->syncWithoutDetaching($syncData);

        return back()->with('success', 'Students assigned successfully.');
    }

    public function removeStudent(Course $course, User $student): RedirectResponse
    {
        $this->authorize('assignStudents', $course);
        $course->students()->detach($student->id);

        return back()->with('success', 'Student removed from course.');
    }
}
