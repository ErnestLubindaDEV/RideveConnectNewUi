<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AttendanceLog extends Model
{
    // Define which attributes can be mass-assigned
    protected $fillable = [
        'employee_id',
        'event_time',
        'device_ip',
    ];

    // Cast the event_time string from the XML into a Carbon/Datetime object
    protected $casts = [
        'event_time' => 'datetime',
    ];

    /**
     * Relationship to the Employee/User (if applicable)
     */
    public function employee(): BelongsTo
    {
        // Assuming you have a User model where 'employee_id' matches
        return $this->belongsTo(User::class, 'employee_id', 'employee_id');
    }
}