<?php

namespace App\Mail;

use App\Models\AttendanceSession;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AttendanceSessionActivatedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public AttendanceSession $session,
        public User $student
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Attendance Confirmed: '.$this->session->course->code,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.sessions.activated',
        );
    }
}
