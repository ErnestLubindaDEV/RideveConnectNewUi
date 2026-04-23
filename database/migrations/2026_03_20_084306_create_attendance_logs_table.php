<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
     // Migration for raw transactions
Schema::create('attendance_logs', function (Blueprint $table) {
    $table->id();
    $table->string('employee_id'); // employeeNoString from ISAPI
    $table->dateTime('event_time');
    $table->string('device_ip');
    $table->unique(['employee_id', 'event_time']); // Prevent duplicate logs
    $table->timestamps();
});

// Migration for processed attendance
Schema::create('daily_attendance', function (Blueprint $table) {
    $table->id();
    $table->string('employee_id');
    $table->date('work_date');
    $table->dateTime('check_in')->nullable();
    $table->dateTime('check_out')->nullable();
    $table->decimal('total_hours', 5, 2)->default(0);
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendance_logs');
    }
};
