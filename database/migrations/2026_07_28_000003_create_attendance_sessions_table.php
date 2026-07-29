<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Named attendance_sessions to avoid conflict with Laravel's sessions table.
     */
    public function up(): void
    {
        Schema::create('attendance_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained('courses')->cascadeOnDelete();
            $table->foreignId('lecturer_id')->constrained('users')->cascadeOnDelete();
            $table->string('title')->nullable();
            $table->date('session_date');
            $table->time('start_time');
            $table->time('end_time');
            $table->timestamp('expires_at');
            $table->string('token', 64)->unique();
            $table->enum('status', ['scheduled', 'active', 'closed', 'expired'])->default('scheduled');
            $table->timestamps();
            $table->softDeletes();

            $table->index(['token', 'status']);
            $table->index(['session_date', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendance_sessions');
    }
};
