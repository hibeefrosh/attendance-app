# MAPOLY Smart Attendance

QR Code Student Attendance Monitoring System for **Moshood Abiola Polytechnic (MAPOLY)**.

## Project owners

| Name | Matric Number |
|------|---------------|
| Ojebiyi Samson Oluwaferanmi | 24/145/0196 |
| Adeniyi Gbenga Daniel | 24/145/0082 |
| Akinwale Elijah Idowu | 24/145/0100 |

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

1. Configure `.env` for MySQL, then:

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

## Main modules

- Auth (custom login + student registration)
- Courses (CRUD + student assignment)
- Attendance sessions + QR generation
- Student camera scan (AJAX)
- Dashboards + reports
- About page (MAPOLY branding + project owners)

## Key paths

- Controllers: `app/Http/Controllers`
- Services: `app/Services`
- Views: `resources/views`
- Institution config: `config/mapoly.php`
