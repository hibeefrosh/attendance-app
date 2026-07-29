# QR Code Student Attendance Management System

Production-style Laravel academic project for QR-based class attendance.

## Stack

- Laravel 13 / PHP 8.3
- MySQL
- Bootstrap 5 + Blade
- SimpleSoftwareIO QRCode
- html5-qrcode (camera scanner)

## Architecture notes

- **`attendance_sessions` table** is used instead of `sessions` because Laravel already uses `sessions` for HTTP session storage.
- QR codes encode **only a secure SHA-256 token**, never database IDs.
- Attendance marking is centralized in `App\Services\AttendanceService` (validation + duplicate prevention).
- Authorization uses **role middleware** + **policies** for course/session ownership.
- Duplicate attendance is blocked in service logic **and** by a unique DB constraint on `(student_id, attendance_session_id)`.

## Setup

1. Configure `.env` for MySQL:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=attendance_app
DB_USERNAME=root
DB_PASSWORD=
```

2. Create the database (if needed), then:

```bash
composer install
php artisan key:generate
php artisan migrate --seed
php artisan serve
```

Open `http://127.0.0.1:8000`

## Demo accounts

| Role | Login | Password |
|------|-------|----------|
| Lecturer | lecturer@demo.com | password |
| Student | CS/2022/001 | password |
| Student | CS/2022/002 | password |

Students log in with **matric number**. Lecturers can use email.

## Typical test flow

1. Login as **lecturer** → create/activate a session → open **Display QR**.
2. Login as **student1** (enrolled) → **Scan QR** → allow camera → confirm success toast.
3. Scan again → should return “already recorded”.
4. Login as a student not enrolled in that course → should be rejected.
5. Close/expire session → QR should be rejected.
6. Lecturer → **Reports** → filter + export CSV; open session print view for PDF via browser print.

## Main modules

- Auth (custom login + student registration)
- Courses (CRUD + student assignment)
- Attendance sessions + QR generation
- Student camera scan (AJAX)
- Dashboards + reports

## Key paths

- Controllers: `app/Http/Controllers`
- Services: `app/Services`
- Policies: `app/Policies`
- Requests: `app/Http/Requests`
- Views: `resources/views`
- Migrations/Seeders: `database/`
