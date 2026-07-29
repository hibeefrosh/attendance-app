<?php

namespace App\Http\Controllers;

use App\Http\Requests\AttendanceSession\StoreAttendanceSessionRequest;
use App\Models\AttendanceSession;
use App\Models\Course;
use App\Services\AttendanceSessionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

class AttendanceSessionController extends Controller
{
    public function __construct(private AttendanceSessionService $sessionService) {}

    public function index(Request $request): View
    {
        $user = $request->user();

        $query = AttendanceSession::query()->with(['course', 'attendanceRecords']);

        if ($user->isLecturer()) {
            $query->ownedBy($user);
        } else {
            $courseIds = $user->registeredCourses()->pluck('courses.id');
            $query->whereIn('course_id', $courseIds);
        }

        if ($request->filled('course_id')) {
            $query->where('course_id', $request->integer('course_id'));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->string('status'));
        }

        $sessions = $query->latest()->paginate(10)->withQueryString();

        $courses = $user->isLecturer()
            ? Course::query()->where('lecturer_id', $user->id)->orderBy('code')->get()
            : $user->registeredCourses()->orderBy('code')->get();

        return view('sessions.index', compact('sessions', 'courses'));
    }

    public function create(Request $request): View
    {
        $this->authorize('create', AttendanceSession::class);

        $courses = Course::query()
            ->where('lecturer_id', $request->user()->id)
            ->orderBy('code')
            ->get();

        return view('sessions.create', compact('courses'));
    }

    public function store(StoreAttendanceSessionRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $course = Course::findOrFail($data['course_id']);

        $this->authorize('update', $course);

        if (! empty($data['activate_now'])) {
            $data['status'] = 'active';
        }

        $session = $this->sessionService->create($course, $request->user(), $data);

        if (! empty($data['activate_now'])) {
            $this->sessionService->activate($session);
        }

        return redirect()
            ->route('sessions.show', $session)
            ->with('success', 'Attendance session created successfully.');
    }

    public function show(AttendanceSession $session): View
    {
        $this->authorize('view', $session);

        $session->markExpiredIfNeeded();
        $session->load(['course', 'lecturer', 'attendanceRecords.student']);

        $qrSvg = null;
        if (auth()->user()->isLecturer() && $session->isActive()) {
            $qrSvg = $this->sessionService->generateQrSvg($session);
        }

        return view('sessions.show', compact('session', 'qrSvg'));
    }

    public function activate(AttendanceSession $session): RedirectResponse
    {
        $this->authorize('update', $session);
        $this->sessionService->activate($session);

        return back()->with('success', 'Session activated. QR code is now valid.');
    }

    public function close(AttendanceSession $session): RedirectResponse
    {
        $this->authorize('update', $session);
        $this->sessionService->close($session);

        return back()->with('success', 'Session closed.');
    }

    public function destroy(AttendanceSession $session): RedirectResponse
    {
        $this->authorize('delete', $session);
        $session->delete();

        return redirect()
            ->route('sessions.index')
            ->with('success', 'Session deleted.');
    }

    public function qr(AttendanceSession $session): View|RedirectResponse
    {
        $this->authorize('manageQr', $session);

        $session->markExpiredIfNeeded();

        if (! $session->isActive()) {
            return redirect()
                ->route('sessions.show', $session)
                ->with('error', 'Activate the session before displaying the QR code.');
        }

        $qrSvg = $this->sessionService->generateQrSvg($session, 360);

        return view('sessions.qr', compact('session', 'qrSvg'));
    }

    public function qrImage(AttendanceSession $session): Response
    {
        $this->authorize('manageQr', $session);

        abort_unless($session->isActive(), 403, 'Session is not active.');

        $svg = $this->sessionService->generateQrSvg($session, 300);

        return response($svg, 200)->header('Content-Type', 'image/svg+xml');
    }
}
