<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class DailyAttendance extends Model
{

    protected $table = 'daily_attendance';
    
    protected $fillable = [
        'employee_id',
        'work_date',
        'check_in',
        'check_out',
        'total_hours',
    ];

    // Ensure dates and times are handled as objects
    protected $casts = [
        'work_date' => 'date',
        'check_in'  => 'datetime',
        'check_out' => 'datetime',
        'total_hours' => 'decimal:2',
    ];

    /**
     * Accessor to get a formatted duration (e.g., "8h 30m")
     */
    public function getFormattedDurationAttribute(): string
    {
        if (!$this->check_in || !$this->check_out) {
            return '0h 0m';
        }

        $hours = floor($this->total_hours);
        $minutes = ($this->total_hours - $hours) * 60;

        return "{$hours}h " . round($minutes) . "m";
    }

    /**
     * Scope to filter records for a specific date range
     */
    public function scopeForPeriod($query, $startDate, $endDate)
    {
        return $query->whereBetween('work_date', [$startDate, $endDate]);
    }

    public function employee()
{
    // We link the employee_id in this table to the employee_id in the HRM table
    return $this->belongsTo(HRM::class, 'employee_id', 'employee_id');
}
}